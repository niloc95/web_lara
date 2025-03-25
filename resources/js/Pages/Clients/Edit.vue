<!-- resources/js/Pages/Clients/Edit.vue -->
<template>
  <AppLayout :title="`Edit Client: ${client.name}`" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Client Edit Form Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
          <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Edit Client</h1>
            <Link :href="route('clients.index')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition">
              Back to Clients
            </Link>
          </div>
          
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Client Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input v-model="form.name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
              </div>
              
              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input v-model="form.email" type="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
              </div>
              
              <!-- Phone -->
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input v-model="form.phone" type="tel" id="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</div>
              </div>
              
              <!-- Birth Date -->
              <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                <input v-model="form.birth_date" type="date" id="birth_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div v-if="form.errors.birth_date" class="text-red-500 text-xs mt-1">{{ form.errors.birth_date }}</div>
              </div>
              
              <!-- Address -->
              <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea v-model="form.address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
              </div>
              
              <!-- Notes -->
              <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea v-model="form.notes" id="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <div v-if="form.errors.notes" class="text-red-500 text-xs mt-1">{{ form.errors.notes }}</div>
              </div>
            </div>
            
            <div class="mt-6 flex justify-between">
              <button @click.prevent="confirmDelete" type="button" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition">
                Delete Client
              </button>
              
              <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : 'Update Client' }}
              </button>
            </div>
          </form>
        </div>
        
        <!-- Appointment History Section (same as Show.vue) -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
          <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800">Appointment History</h2>
            <Link :href="route('appointments.create', { client_id: client.id })" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
              New Appointment
            </Link>
          </div>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="appointment in appointments" :key="appointment.id" class="hover:bg-gray-50">
                  <!-- Time Cell - Links to Calendar Day View -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <Link 
                      :href="route('calendar.day', { date: formatDateYmd(appointment.start_time) })"
                      class="hover:text-indigo-600 hover:underline transition-colors flex items-center cursor-pointer"
                    >
                      {{ formatDateTime(appointment.start_time) }}
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </Link>
                  </td>
                  
                  <!-- Service Cell - Links to Service Detail -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <Link 
                      v-if="appointment.service?.id"
                      :href="route('services.show', appointment.service.id)"
                      class="hover:text-indigo-600 hover:underline transition-colors flex items-center"
                    >
                      <span 
                        v-if="appointment.service?.color" 
                        class="inline-block w-3 h-3 rounded-full mr-2" 
                        :style="{ backgroundColor: appointment.service.color }"
                      ></span>
                      {{ appointment.service?.name || 'N/A' }}
                    </Link>
                    <span v-else>
                      <span 
                        v-if="appointment.service?.color" 
                        class="inline-block w-3 h-3 rounded-full mr-2" 
                        :style="{ backgroundColor: appointment.service.color }"
                      ></span>
                      {{ appointment.service?.name || 'N/A' }}
                    </span>
                  </td>
                  
                  <!-- Status Cell - With dropdown -->
                  <td class="px-6 py-4 whitespace-nowrap relative">
                    <button 
                      @click.stop="toggleDropdown(appointment.id)" 
                      :id="`dropdown-btn-${appointment.id}`"
                      class="relative inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium w-auto cursor-pointer"
                      :class="getStatusClass(appointment.status)"
                    >
                      {{ capitalize(appointment.status) }}
                      <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Empty state -->
            <div v-if="appointments.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments</h3>
              <p class="mt-1 text-sm text-gray-500">This client doesn't have any appointments yet.</p>
              <div class="mt-6">
                <Link :href="route('appointments.create', { client_id: client.id })" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                  <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  New Appointment
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-6">Are you sure you want to delete this client? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
            Cancel
          </button>
          <button @click="deleteClient" class="px-4 py-2 bg-red-500 text-white rounded-md">
            Delete
          </button>
        </div>
      </div>
    </div>
    
    <!-- Add the dropdown teleport section -->
    <Teleport to="body">
      <div 
        v-for="appointment in appointments" 
        :key="`dropdown-${appointment.id}`"
        :style="getDropdownPosition(appointment.id)"
        class="absolute z-50"
        v-show="activeDropdown === appointment.id"
      >
        <div class="bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5 overflow-hidden">
          <div role="menu" aria-orientation="vertical" class="py-1">
            <button 
              v-for="status in ['pending', 'confirmed', 'completed', 'cancelled']" 
              :key="status"
              @click="updateStatus(appointment.id, status)"
              class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
              :class="{ 'font-semibold': appointment.status === status }"
            >
              {{ capitalize(status) }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format } from 'date-fns';
import axios from 'axios';

const props = defineProps({
  client: Object,
  appointments: Array,
});

const showDeleteModal = ref(false);

const form = useForm({
  name: props.client.name,
  email: props.client.email || '',
  phone: props.client.phone || '',
  address: props.client.address || '',
  notes: props.client.notes || '',
  birth_date: props.client.birth_date || '',
});

function submit() {
  form.put(route('clients.update', props.client.id));
}

function confirmDelete() {
  showDeleteModal.value = true;
}

function deleteClient() {
  router.delete(route('clients.destroy', props.client.id));
}

function formatDateTime(dateTime) {
  if (!dateTime) return '';
  const date = new Date(dateTime);
  return date.toLocaleString();
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': return 'bg-green-100 text-green-800';
    case 'completed': return 'bg-blue-100 text-blue-800';
    case 'cancelled': return 'bg-red-100 text-red-800';
    case 'pending':
    default: return 'bg-yellow-100 text-yellow-800';
  }
}

// Store initial appointments
const appointments = ref(props.appointments || []);
const isLoading = ref(false);
const currentPage = ref(1);
const hasMoreAppointments = ref(true);

// Function to load more appointments when needed
function loadMoreAppointments() {
  if (!hasMoreAppointments.value || isLoading.value) return;
  
  isLoading.value = true;
  axios.get(route('api.clients.appointments', props.client.id), {
    params: { page: currentPage.value + 1 }
  })
  .then(response => {
    if (response.data.length === 0) {
      hasMoreAppointments.value = false;
    } else {
      appointments.value = [...appointments.value, ...response.data];
      currentPage.value++;
    }
    isLoading.value = false;
  });
}

// Status dropdown functionality
const activeDropdown = ref(null);

function formatDateYmd(dateString) {
  return format(new Date(dateString), 'yyyy-MM-dd');
}

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function toggleDropdown(id) {
  if (activeDropdown.value === id) {
    activeDropdown.value = null;
    togglePageScroll(false);
  } else {
    activeDropdown.value = id;
    togglePageScroll(true);
  }
}

function getDropdownPosition(appointmentId) {
  if (activeDropdown.value !== appointmentId) return {};
  
  const button = document.getElementById(`dropdown-btn-${appointmentId}`);
  if (!button) return {};
  
  const rect = button.getBoundingClientRect();
  return {
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${rect.left + window.scrollX}px`
  };
}

function togglePageScroll(disable) {
  if (disable) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
}

function updateStatus(appointmentId, status) {
  // Find the appointment in the array
  const appointmentIndex = props.appointments.findIndex(a => a.id === appointmentId);
  if (appointmentIndex === -1) return;
  
  // Store the old status in case we need to revert
  const oldStatus = props.appointments[appointmentIndex].status;
  
  // Optimistically update the UI
  props.appointments[appointmentIndex].status = status;
  
  // Close the dropdown
  activeDropdown.value = null;
  togglePageScroll(false);
  
  // Make the actual API request
  axios.patch(route('appointments.status', appointmentId), { 
    status,
    _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  })
  .catch(error => {
    // If there was an error, revert to the old status
    props.appointments[appointmentIndex].status = oldStatus;
    console.error('Error updating status:', error);
    alert('Failed to update status: ' + (error.response?.data?.message || 'Unknown error'));
  });
}
</script>