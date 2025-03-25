<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    appointment: Object,
});

const emit = defineEmits(['close', 'updated']);

const statusOptions = [
    { value: 'pending', label: 'Pending', icon: 'bi-clock' },
    { value: 'confirmed', label: 'Confirmed', icon: 'bi-check-circle' },
    { value: 'completed', label: 'Completed', icon: 'bi-check-all' },
    { value: 'cancelled', label: 'Cancelled', icon: 'bi-x-circle' }
];

function updateStatus(status) {
    router.patch(route('appointments.status', props.appointment.id), { status }, {
        onSuccess: () => {
            emit('updated');
        }
    });
}

function deleteAppointment() {
    if (confirm('Are you sure you want to delete this appointment?')) {
        router.delete(route('appointments.destroy', props.appointment.id), {
            onSuccess: () => {
                emit('updated');
            }
        });
    }
}
</script>

<template>
    <div class="dropdown-menu absolute right-0 mt-1 bg-white shadow-lg rounded-md border z-50 w-48">
        <div class="py-1">
            <Link :href="route('appointments.edit', appointment.id)" 
                  class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                <i class="bi bi-pencil me-2"></i> Edit
            </Link>
            
            <div class="border-t my-1"></div>
            
            <button v-for="status in statusOptions" :key="status.value"
                    @click="updateStatus(status.value)"
                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                    :class="{'font-medium': appointment.status === status.value}">
                <i :class="status.icon + ' me-2'"></i> Mark as {{ status.label }}
            </button>
            
            <div class="border-t my-1"></div>
            
            <button @click="deleteAppointment"
                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                <i class="bi bi-trash me-2"></i> Delete
            </button>
        </div>
    </div>
</template>