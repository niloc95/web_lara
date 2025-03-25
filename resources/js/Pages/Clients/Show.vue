<!-- resources/js/Pages/Clients/Show.vue -->
<template>
  <AppLayout :title="client ? `Client: ${client.name}` : 'Client Details'">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <!-- Header with actions -->
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-semibold mb-4 md:mb-0">{{ client.name }}</h1>
            <div class="flex space-x-3">
              <Link :href="route('clients.edit', client.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Edit Client
              </Link>
              <Link :href="route('clients.index')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md">
                Back to Clients
              </Link>
            </div>
          </div>
          
          <!-- Client Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Client Information</h2>
              <dl class="divide-y divide-gray-200">
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Name</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.name }}</dd>
                </div>
                <div v-if="client.email" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Email</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a :href="`mailto:${client.email}`" class="text-blue-600 hover:underline">{{ client.email }}</a>
                  </dd>
                </div>
                <div v-if="client.phone" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a :href="`tel:${client.phone}`" class="text-blue-600 hover:underline">{{ client.phone }}</a>
                  </dd>
                </div>
                <div v-if="client.birth_date" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(client.birth_date) }}</dd>
                </div>
                <div v-if="client.address" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Address</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">{{ client.address }}</dd>
                </div>
              </dl>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Notes</h2>
              <div v-if="client.notes" class="prose prose-sm max-w-none">
                <p class="whitespace-pre-line">{{ client.notes }}</p>
              </div>
              <div v-else class="text-gray-500 italic">
                No notes available for this client.
              </div>
            </div>
          </div>
          
          <!-- Appointment History -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
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
            
            <!-- Pagination if you have it -->
            <div v-if="appointments.links && appointments.links.length > 3" class="px-6 py-4 border-t border-gray-200">
              <Pagination :links="appointments.links" />
            </div>
          </div>
          
          <!-- Add the dropdown teleport section (identical to Dashboard) -->
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
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { format } from 'date-fns'; // Add this import
import axios from 'axios'; // Add this for the API call

const props = defineProps({
  client: Object,
  appointments: Array
});

const activeDropdown = ref(null);

function formatDate(date) {
  if (!date) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString(undefined, options);
}

function formatDateTime(dateString) {
  return format(new Date(dateString), 'MMM d, yyyy h:mm a');
}

function formatDateYmd(dateString) {
  return format(new Date(dateString), 'yyyy-MM-dd');
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': 
      return 'bg-green-100 text-green-800 hover:bg-green-200';
    case 'completed': 
      return 'bg-blue-100 text-blue-800 hover:bg-blue-200';
    case 'cancelled': 
      return 'bg-red-100 text-red-800 hover:bg-red-200';
    case 'pending':
    default: 
      return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
  }
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

// Complete the updateStatus function
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