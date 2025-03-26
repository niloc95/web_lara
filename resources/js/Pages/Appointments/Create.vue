<!-- resources/js/Pages/Appointments/Create.vue -->
<template>
  <AppLayout title="Create Appointment" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Schedule New Appointment</h1>
            <Link :href="route('appointments.index')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition">
              Back to Appointments
            </Link>
          </div>
          
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Client Selection - Updated with New button -->
              <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                <div class="flex mt-1 space-x-2">
                  <select 
                    v-model="form.client_id" 
                    id="client_id" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :disabled="!!preselectedClient"
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
                    :disabled="!!preselectedClient"
                  >
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New
                  </button>
                </div>
                <div v-if="form.errors.client_id" class="text-red-500 text-xs mt-1">{{ form.errors.client_id }}</div>
                <div v-if="preselectedClient" class="text-gray-500 text-xs mt-1">Client pre-selected from client page</div>
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
                <div v-if="form.errors['start_time'] && form.errors['start_time'].includes('date')" class="text-red-500 text-xs mt-1">{{ form.errors['start_time'] }}</div>
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
                <div v-if="form.errors['start_time'] && !form.errors['start_time'].includes('date')" class="text-red-500 text-xs mt-1">{{ form.errors['start_time'] }}</div>
              </div>
              
              <!-- End Time (Auto-calculated based on service duration) -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Duration</label>
                <div class="mt-1 py-2 px-3 bg-gray-100 border border-gray-300 rounded-md text-gray-600">
                  {{ selectedService ? `${selectedService.duration} minutes` : 'Select a service' }}
                </div>
              </div>
              
              <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input 
                  v-model="endTime" 
                  type="time" 
                  id="end_time" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                <div v-if="form.errors.end_time" class="text-red-500 text-xs mt-1">{{ form.errors.end_time }}</div>
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
            
            <div class="mt-6 flex justify-end">
              <button 
                type="submit" 
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition" 
                :disabled="form.processing"
              >
                {{ form.processing ? 'Creating...' : 'Schedule Appointment' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- New Client Modal -->
    <div v-if="showNewClientModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Add New Client</h2>
          <button @click="closeClientModal" class="text-gray-400 hover:text-gray-500">
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
              @click="closeClientModal" 
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              class="px-4 py-2 bg-blue-500 text-white rounded-md"
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
import { ref, computed, watch, onMounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format, addMinutes, parse } from 'date-fns';
import axios from 'axios';

const props = defineProps({
  clients: Array,
  services: Array,
  preselectedClient: Object,
  preselectedService: Object
});

// Form data
const startDate = ref(format(new Date(), 'yyyy-MM-dd'));
const startTime = ref('09:00');
const endTime = ref('09:30');

// Client creation functionality
const showNewClientModal = ref(false);
const creatingClient = ref(false);
const clientErrors = ref({});
const clientsList = ref(props.clients || []);
const newClient = ref({
  name: '',
  email: '',
  phone: ''
});

const form = useForm({
  client_id: props.preselectedClient ? props.preselectedClient.id : '',
  service_id: props.preselectedService ? props.preselectedService.id : '',
  start_time: '',
  end_time: '',
  status: 'pending',
  notes: ''
});

// Computed properties
const selectedService = computed(() => {
  if (!form.service_id) return null;
  return props.services.find(service => service.id === form.service_id);
});

// Methods
function updateEndTime() {
  if (startDate.value && startTime.value && selectedService.value) {
    const startDateTime = parse(
      `${startDate.value} ${startTime.value}`, 
      'yyyy-MM-dd HH:mm', 
      new Date()
    );
    
    const endDateTime = addMinutes(startDateTime, selectedService.value.duration);
    endTime.value = format(endDateTime, 'HH:mm');
  }
}

function combineDateTime() {
  if (startDate.value && startTime.value) {
    form.start_time = `${startDate.value} ${startTime.value}:00`;
  }
  
  if (startDate.value && endTime.value) {
    form.end_time = `${startDate.value} ${endTime.value}:00`;
  }
}

function submit() {
  combineDateTime();
  form.post(route('appointments.store'));
}

function closeClientModal() {
  showNewClientModal.value = false;
  newClient.value = { name: '', email: '', phone: '' };
  clientErrors.value = {};
}

// Function to handle client creation
async function submitNewClient() {
  creatingClient.value = true;
  clientErrors.value = {};
  
  try {
    // Make sure CSRF token is included
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Send the request to create a new client
    const response = await axios.post(route('clients.api.store'), newClient.value);
    
    if (response.data && response.data.success) {
      // Add the new client to the list
      const createdClient = response.data.client;
      clientsList.value.push(createdClient);
      
      // Sort clients alphabetically
      clientsList.value.sort((a, b) => a.name.localeCompare(b.name));
      
      // Select the new client
      form.client_id = createdClient.id;
      
      // Close the modal and reset form
      closeClientModal();
      
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

// Watchers
watch(() => form.service_id, updateEndTime);

// Lifecycle hooks
onMounted(() => {
  updateEndTime();
});
</script>