<!-- resources/js/Pages/Appointments/Edit.vue -->
<template>
  <AppLayout title="Edit Appointment" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Appointment</h1>
            <Link :href="route('appointments.index')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition">
              Back to Appointments
            </Link>
          </div>
          
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Client Selection -->
              <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                <div class="flex mt-1 space-x-2">
                  <select 
                    v-model="form.client_id" 
                    id="client_id" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option value="">Select a client</option>
                    <option v-for="client in clientsList" :key="client.id" :value="client.id">
                      {{ client.name }}
                    </option>
                  </select>
                  <button 
                    type="button"
                    @click="showNewClientModal = true"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New
                  </button>
                </div>
                <div v-if="form.errors.client_id" class="text-red-500 text-xs mt-1">{{ form.errors.client_id }}</div>
              </div>
              
              <!-- Service Selection -->
              <div>
                <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
                <select 
                  v-model="form.service_id" 
                  id="service_id" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  @change="updateEndTime"
                >
                  <option value="">Select a service</option>
                  <option v-for="service in services" :key="service.id" :value="service.id">
                    {{ service.name }} ({{ service.duration }} min)
                  </option>
                </select>
                <div v-if="form.errors.service_id" class="text-red-500 text-xs mt-1">{{ form.errors.service_id }}</div>
              </div>
              
              <!-- Start Time -->
              <div>
                <label for="start_time_date" class="block text-sm font-medium text-gray-700">Date</label>
                <input 
                  v-model="startDate" 
                  type="date" 
                  id="start_time_date" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  @change="updateEndTime"
                >
                <div v-if="form.errors.start_time" class="text-red-500 text-xs mt-1">{{ form.errors.start_time }}</div>
              </div>
              
              <div>
                <label for="start_time_time" class="block text-sm font-medium text-gray-700">Time</label>
                <input 
                  v-model="startTime" 
                  type="time" 
                  id="start_time_time" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  @change="updateEndTime"
                >
              </div>
              
              <!-- End Time -->
              <div>
                <label for="end_time_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input 
                  v-model="endDate" 
                  type="date" 
                  id="end_time_date" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                <div v-if="form.errors.end_time" class="text-red-500 text-xs mt-1">{{ form.errors.end_time }}</div>
              </div>
              
              <div>
                <label for="end_time_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input 
                  v-model="endTime" 
                  type="time" 
                  id="end_time_time" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
              </div>
              
              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select 
                  v-model="form.status" 
                  id="status" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option value="pending">Pending</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
              </div>
              
              <!-- Notes -->
              <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea 
                  v-model="form.notes" 
                  id="notes" 
                  rows="4" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                <div v-if="form.errors.notes" class="text-red-500 text-xs mt-1">{{ form.errors.notes }}</div>
              </div>
            </div>
            
            <div class="mt-6 flex justify-between">
              <button 
                type="button" 
                @click="confirmDelete"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition"
              >
                Delete Appointment
              </button>
              
              <button 
                type="submit" 
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition" 
                :disabled="form.processing"
              >
                {{ form.processing ? 'Updating...' : 'Update Appointment' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-6">Are you sure you want to delete this appointment? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
            Cancel
          </button>
          <button @click="deleteAppointment" class="px-4 py-2 bg-red-500 text-white rounded-md">
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- New Client Modal -->
    <div v-if="showNewClientModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Add New Client</h2>
          <button @click="showNewClientModal = false" class="text-gray-400 hover:text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="submitNewClient">
          <div class="grid grid-cols-1 gap-4">
            <!-- Name -->
            <div>
              <label for="new_client_name" class="block text-sm font-medium text-gray-700">Name *</label>
              <input 
                v-model="newClient.name" 
                type="text" 
                id="new_client_name" 
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
              <div v-if="clientErrors.name" class="text-red-500 text-xs mt-1">{{ clientErrors.name }}</div>
            </div>
            
            <!-- Email -->
            <div>
              <label for="new_client_email" class="block text-sm font-medium text-gray-700">Email</label>
              <input 
                v-model="newClient.email" 
                type="email" 
                id="new_client_email" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
              <div v-if="clientErrors.email" class="text-red-500 text-xs mt-1">{{ clientErrors.email }}</div>
            </div>
            
            <!-- Phone -->
            <div>
              <label for="new_client_phone" class="block text-sm font-medium text-gray-700">Phone</label>
              <input 
                v-model="newClient.phone" 
                type="tel" 
                id="new_client_phone" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
              <div v-if="clientErrors.phone" class="text-red-500 text-xs mt-1">{{ clientErrors.phone }}</div>
            </div>
          </div>
          
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              type="button" 
              @click="showNewClientModal = false" 
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              class="px-4 py-2 bg-blue-500 text-white rounded -md"
              :disabled="creatingClient"
            >
              {{ creatingClient ? 'Creating...' : 'Create Client' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format, addMinutes, parseISO } from 'date-fns';
import axios from 'axios';

const props = defineProps({
  appointment: Object,
  clients: Array,
  services: Array
});

// Make clients reactive so we can update it after creating a new one
const clientsList = ref(props.clients || []);
const showDeleteModal = ref(false);
const showNewClientModal = ref(false);
const creatingClient = ref(false);
const clientErrors = ref({});

// New client form - using a plain reactive object, not an Inertia form
const newClient = ref({
  name: '',
  email: '',
  phone: ''
});

// Extract date and time from appointment
const startDateTime = props.appointment.start_time ? parseISO(props.appointment.start_time) : new Date();
const endDateTime = props.appointment.end_time ? parseISO(props.appointment.end_time) : new Date();

const startDate = ref(format(startDateTime, 'yyyy-MM-dd'));
const startTime = ref(format(startDateTime, 'HH:mm'));
const endDate = ref(format(endDateTime, 'yyyy-MM-dd'));
const endTime = ref(format(endDateTime, 'HH:mm'));

const form = useForm({
  client_id: props.appointment.client_id,
  service_id: props.appointment.service_id,
  start_time: props.appointment.start_time,
  end_time: props.appointment.end_time,
  status: props.appointment.status,
  notes: props.appointment.notes || ''
});

const selectedService = computed(() => {
  if (!form.service_id) return null;
  return props.services.find(service => service.id === form.service_id);
});

function updateEndTime() {
  if (selectedService.value && startDate.value && startTime.value) {
    const startDateObj = new Date(`${startDate.value}T${startTime.value}`);
    const endDateObj = addMinutes(startDateObj, selectedService.value.duration || 60);
    
    endDate.value = format(endDateObj, 'yyyy-MM-dd');
    endTime.value = format(endDateObj, 'HH:mm');
  }
}

function combineDateTime() {
  form.start_time = `${startDate.value}T${startTime.value}`;
  form.end_time = `${endDate.value}T${endTime.value}`;
}

function submit() {
  combineDateTime();
  form.put(route('appointments.update', props.appointment.id));
}

function confirmDelete() {
  showDeleteModal.value = true;
}

function deleteAppointment() {
  router.delete(route('appointments.destroy', props.appointment.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
    }
  });
}

// Function to add the CSRF token to axios requests
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ONLY ONE implementation of submitNewClient function
async function submitNewClient() {
  creatingClient.value = true;
  clientErrors.value = {};
  
  try {
    const response = await axios.post(route('clients.api.store'), newClient.value);
    
    // Add new client to the list
    if (response.data && response.data.success) {
      const createdClient = response.data.client;
      clientsList.value.push(createdClient);
      
      // Sort clients alphabetically
      clientsList.value.sort((a, b) => a.name.localeCompare(b.name));
      
      // Select the new client
      form.client_id = createdClient.id;
      
      // Close modal and reset form
      showNewClientModal.value = false;
      newClient.value = { name: '', email: '', phone: '' };
      
      // Show success notification
      alert('Client created successfully!');
    }
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      clientErrors.value = error.response.data.errors;
    } else {
      console.error('Error creating client:', error);
      alert('Failed to create client. Please try again.');
    }
  } finally {
    creatingClient.value = false;
  }
}

onMounted(() => {
  if (selectedService.value) {
    updateEndTime();
  }
});
</script>