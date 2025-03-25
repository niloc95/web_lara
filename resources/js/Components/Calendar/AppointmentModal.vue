<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { format, parseISO, addMinutes } from 'date-fns';
import VueDatepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    appointment: Object,
    selectedDate: String,
    selectedHour: Number,
    clients: Array,
    services: Array,
});

const emit = defineEmits(['close', 'saved']);

const isLoading = ref(false);
const isEditing = computed(() => !!props.appointment);
const statusOptions = [
    { value: 'pending', label: 'Pending' },
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' }
];

// Form data
const form = ref({
    client_id: '',
    service_id: '',
    date: props.selectedDate || format(new Date(), 'yyyy-MM-dd'),
    time: props.selectedHour ? `${props.selectedHour}:00` : '09:00',
    duration: 60,
    status: 'pending',
    notes: '',
});

// Load appointment data if editing
watch(() => props.appointment, (newVal) => {
    if (newVal) {
        form.value = {
            client_id: newVal.client_id.toString(),
            service_id: newVal.service_id.toString(),
            date: format(parseISO(newVal.start_time), 'yyyy-MM-dd'),
            time: format(parseISO(newVal.start_time), 'HH:mm'),
            duration: calculateDurationInMinutes(newVal.start_time, newVal.end_time),
            status: newVal.status,
            notes: newVal.notes || '',
        };
    }
}, { immediate: true });

// Update duration when service changes
watch(() => form.value.service_id, (newVal) => {
    if (newVal) {
        const selectedService = props.services.find(service => service.id.toString() === newVal.toString());
        if (selectedService) {
            form.value.duration = selectedService.duration;
        }
    }
});

// Computed properties
const modalTitle = computed(() => isEditing.value ? 'Edit Appointment' : 'New Appointment');
const submitButtonText = computed(() => isEditing.value ? 'Update Appointment' : 'Create Appointment');
const timeSlots = computed(() => {
    const slots = [];
    for (let hour = 8; hour <= 20; hour++) {
        for (let minute = 0; minute < 60; minute += 15) {
            slots.push(`${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`);
        }
    }
    return slots;
});

// Helper functions
function calculateDurationInMinutes(startTime, endTime) {
    const start = parseISO(startTime);
    const end = parseISO(endTime);
    return Math.round((end - start) / 1000 / 60);
}

// Form submission
function submitForm() {
    isLoading.value = true;
    
    // Calculate start and end times
    const startDateTime = `${form.value.date}T${form.value.time}`;
    const endDateTime = format(
        addMinutes(new Date(startDateTime), parseInt(form.value.duration)), 
        "yyyy-MM-dd'T'HH:mm:ss"
    );
    
    const formData = {
        client_id: form.value.client_id,
        service_id: form.value.service_id,
        start_time: startDateTime,
        end_time: endDateTime,
        status: form.value.status,
        notes: form.value.notes,
    };
    
    if (isEditing.value) {
        // Update existing appointment
        router.patch(route('appointments.update', props.appointment.id), formData, {
            onSuccess: () => {
                isLoading.value = false;
                emit('saved');
            },
            onError: () => {
                isLoading.value = false;
            }
        });
    } else {
        // Create new appointment
        router.post(route('appointments.store'), formData, {
            onSuccess: () => {
                isLoading.value = false;
                emit('saved');
            },
            onError: () => {
                isLoading.value = false;
            }
        });
    }
}
</script>

<template>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 md:mx-0 overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">{{ modalTitle }}</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client *</label>
                        <select id="client_id" v-model="form.client_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select Client</option>
                            <option v-for="client in clients" :key="client.id" :value="client.id.toString()">
                                {{ client.name }}
                            </option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Service *</label>
                        <select id="service_id" v-model="form.service_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select Service</option>
                            <option v-for="service in services" :key="service.id" :value="service.id.toString()">
                                {{ service.name }} ({{ service.duration }} min)
                            </option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                            <VueDatepicker v-model="form.date" input-format="yyyy-MM-dd" model-type="format" />
                        </div>
                        
                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Time *</label>
                            <select id="time" v-model="form.time" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option v-for="slot in timeSlots" :key="slot" :value="slot">{{ slot }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (min)</label>
                            <input type="number" id="duration" v-model="form.duration" min="15" step="15" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required />
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" v-model="form.status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" v-model="form.notes" rows="3" 
                                  class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="$emit('close')" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 rounded-md text-white hover:bg-blue-700"
                                :disabled="isLoading">
                            <span v-if="isLoading">
                                <i class="bi bi-arrow-repeat animate-spin inline-block"></i> Saving...
                            </span>
                            <span v-else>{{ submitButtonText }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>