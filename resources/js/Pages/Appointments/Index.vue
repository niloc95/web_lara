<!-- resources/js/Pages/Appointments/Index.vue -->
<template>
  <AppLayout title="Appointments">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-6">
          <div class="flex flex-wrap gap-4 justify-between items-center">
            <!-- Search bar -->
            <div class="flex items-center w-full md:w-auto">
              <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input 
                  v-model="filters.search" 
                  @input="debouncedSearch"
                  class="pl-10 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  placeholder="Search clients or services..." 
                />
              </div>
            </div>
            
            <!-- Date range and Status filters -->
            <div class="flex flex-wrap gap-3 items-center w-full md:w-auto">
              <label class="text-sm text-gray-600">Date Range:</label>
              <select 
                v-model="filters.dateRange" 
                @change="applyFilters"
                class="mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
              </select>
              
              <label class="text-sm text-gray-600 ml-4">Status:</label>
              <select 
                v-model="filters.status" 
                @change="applyFilters"
                class="mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            
            <!-- View toggle and Add appointment -->
            <div class="flex space-x-3 w-full md:w-auto justify-between md:justify-end">
              <div class="inline-flex rounded-md shadow-sm" role="group">
                <button 
                  @click="viewMode = 'list'" 
                  :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                  class="py-2 px-4 text-sm font-medium rounded-l-md border border-gray-300 hover:bg-gray-50"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                  </svg>
                </button>
                <button 
                  @click="viewMode = 'grid'" 
                  :class="viewMode === 'grid' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                  class="py-2 px-4 text-sm font-medium rounded-r-md border border-gray-300 hover:bg-gray-50"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                  </svg>
                </button>
              </div>
              
              <Link :href="route('appointments.create')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 flex items-center">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New Appointment
              </Link>
            </div>
          </div>
        </div>
        
        <!-- Success Message -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
          {{ $page.props.flash.success }}
        </div>
        
        <!-- List View -->
        <div v-if="viewMode === 'list'" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th @click="sort('start_time')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Date & Time
                    <span v-if="sortField === 'start_time'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th @click="sort('client_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Client
                    <span v-if="sortField === 'client_name'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th @click="sort('service_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Service
                    <span v-if="sortField === 'service_name'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th @click="sort('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Status
                    <span v-if="sortField === 'status'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="appointment in sortedAppointments" :key="appointment.id" class="hover:bg-gray-50">
                  <!-- Date & Time Cell -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <Link :href="route('calendar.day', { date: formatDateYmd(appointment.start_time) })" class="text-sm text-gray-500 hover:text-indigo-600">
                      <div class="font-medium">{{ formatDate(appointment.start_time) }}</div>
                      <div>{{ formatTime(appointment.start_time) }} - {{ formatTime(appointment.end_time) }}</div>
                    </Link>
                  </td>
                  
                  <!-- Client Cell -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <Link :href="route('clients.show', appointment.client.id)" class="text-sm font-medium text-gray-900 hover:text-indigo-600 flex items-center">
                      {{ appointment.client?.name || 'N/A' }}
                    </Link>
                    <div class="text-xs text-gray-500">{{ appointment.client?.phone || '' }}</div>
                  </td>
                  
                  <!-- Service Cell -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <span 
                        v-if="appointment.service?.color" 
                        class="inline-block w-3 h-3 rounded-full mr-2" 
                        :style="{ backgroundColor: appointment.service.color }"
                      ></span>
                      <span class="text-sm text-gray-500">
                        {{ appointment.service?.name || 'N/A' }}
                        <span v-if="appointment.service?.duration" class="text-xs ml-1">({{ appointment.service.duration }}min)</span>
                      </span>
                    </div>
                  </td>
                  
                  <!-- Status Cell -->
                  <td class="px-6 py-4 whitespace-nowrap">
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
                  
                  <!-- Actions Cell -->
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center space-x-3">
                      <Link :href="route('calendar.day', { date: formatDateYmd(appointment.start_time) })" class="text-indigo-600 hover:text-indigo-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </Link>
                      
                      <Link :href="route('clients.show', appointment.client.id)" class="text-blue-600 hover:text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </Link>
                      
                      <Link :href="route('appointments.edit', appointment.id)" class="text-indigo-600 hover:text-indigo-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </Link>
                      
                      <button @click="confirmDelete(appointment)" class="text-red-600 hover:text-red-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="sortedAppointments.length === 0">
                  <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                      <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <p class="mt-2">No appointments found.</p>
                      <p class="text-sm text-gray-400">Try changing your search or filters.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div v-if="appointments.links && appointments.links.length > 3" class="px-6 py-4 border-t border-gray-200">
            <Pagination :links="appointments.links" />
          </div>
        </div>
        
        <!-- Grid View -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="appointment in sortedAppointments" :key="appointment.id" class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-4 border-b border-gray-200">
              <div class="flex justify-between items-center">
                <span :class="getStatusClass(appointment.status)" class="px-2 py-1 text-xs rounded-full">
                  {{ capitalize(appointment.status) }}
                </span>
                <div class="flex space-x-2">
                  <Link :href="route('appointments.edit', appointment.id)" class="text-indigo-600 hover:text-indigo-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Link>
                  <button @click="confirmDelete(appointment)" class="text-red-600 hover:text-red-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <div class="p-4">
              <Link :href="route('calendar.day', { date: formatDateYmd(appointment.start_time) })" class="block mb-3">
                <div class="text-sm font-semibold text-indigo-600">
                  {{ formatDate(appointment.start_time) }}
                </div>
                <div class="text-sm text-gray-600">
                  {{ formatTime(appointment.start_time) }} - {{ formatTime(appointment.end_time) }}
                </div>
              </Link>
              
              <div class="mb-3">
                <Link :href="route('clients.show', appointment.client.id)" class="block text-sm font-medium text-gray-900 hover:text-indigo-600">
                  {{ appointment.client?.name || 'N/A' }}
                </Link>
                <div class="text-xs text-gray-500">{{ appointment.client?.phone || '' }}</div>
              </div>
              
              <div v-if="appointment.service" class="flex items-center mb-2">
                <span 
                  v-if="appointment.service?.color" 
                  class="inline-block w-3 h-3 rounded-full mr-2" 
                  :style="{ backgroundColor: appointment.service.color }"
                ></span>
                <span class="text-sm text-gray-600">
                  {{ appointment.service?.name || 'N/A' }}
                  <span v-if="appointment.service?.duration" class="text-xs ml-1">({{ appointment.service.duration }}min)</span>
                </span>
              </div>
              
              <div v-if="appointment.notes" class="mt-2 text-xs text-gray-500 line-clamp-2">
                {{ appointment.notes }}
              </div>
            </div>
          </div>
          
          <!-- Empty state for grid view -->
          <div v-if="sortedAppointments.length === 0" class="col-span-full bg-white p-10 rounded-lg text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments found</h3>
            <p class="mt-1 text-sm text-gray-500">Try changing your search or filters.</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Status Dropdown Teleport -->
    <Teleport to="body">
      <div 
        v-for="appointment in appointments.data" 
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
    
    <!-- Delete Confirmation Modal -->
    <Modal :show="deleteModal" @close="deleteModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">Delete Appointment</h2>
        <p class="mt-1 text-sm text-gray-600">
          Are you sure you want to delete this appointment? This action cannot be undone.
        </p>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="deleteModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
            Cancel
          </button>
          <button @click="deleteAppointment" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm text-white hover:bg-red-700">
            Delete
          </button>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { format } from 'date-fns';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

// Custom debounce function
function createDebounce(func, wait) {
  let timeout;
  return function(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

const props = defineProps({
  appointments: Object,
  filters: Object
});

// State variables
const viewMode = ref('list');
const activeDropdown = ref(null);
const deleteModal = ref(false);
const appointmentToDelete = ref(null);
const sortField = ref('start_time');
const sortDirection = ref('desc');
const filters = ref({
  search: props.filters?.search || '',
  dateRange: props.filters?.dateRange || 'all',
  status: props.filters?.status || ''
});

// Computed properties
const sortedAppointments = computed(() => {
  if (!props.appointments || !props.appointments.data) return [];
  
  return [...props.appointments.data].sort((a, b) => {
    let aValue, bValue;
    
    switch (sortField.value) {
      case 'client_name':
        aValue = a.client?.name || '';
        bValue = b.client?.name || '';
        break;
      case 'service_name':
        aValue = a.service?.name || '';
        bValue = b.service?.name || '';
        break;
      case 'start_time':
        aValue = new Date(a.start_time).getTime();
        bValue = new Date(b.start_time).getTime();
        break;
      case 'status':
        aValue = a.status || '';
        bValue = b.status || '';
        break;
      default:
        aValue = a[sortField.value] || '';
        bValue = b[sortField.value] || '';
    }
    
    if (sortDirection.value === 'asc') {
      return aValue > bValue ? 1 : -1;
    } else {
      return aValue < bValue ? 1 : -1;
    }
  });
});

// Methods
function formatDate(dateTime) {
  if (!dateTime) return '';
  return format(new Date(dateTime), 'EEE, MMM d, yyyy');
}

function formatTime(dateTime) {
  if (!dateTime) return '';
  return format(new Date(dateTime), 'h:mm a');
}

function formatDateYmd(dateString) {
  if (!dateString) return '';
  return format(new Date(dateString), 'yyyy-MM-dd');
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': return 'bg-green-100 text-green-800 hover:bg-green-200';
    case 'completed': return 'bg-blue-100 text-blue-800 hover:bg-blue-200';
    case 'cancelled': return 'bg-red-100 text-red-800 hover:bg-red-200';
    case 'pending':
    default: return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
  }
}

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function sort(field) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
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
  const appointmentIndex = props.appointments.data.findIndex(a => a.id === appointmentId);
  if (appointmentIndex === -1) return;
  
  // Store the old status in case we need to revert
  const oldStatus = props.appointments.data[appointmentIndex].status;
  
  // Optimistically update the UI
  props.appointments.data[appointmentIndex].status = status;
  
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
    props.appointments.data[appointmentIndex].status = oldStatus;
    console.error('Error updating status:', error);
    alert('Failed to update status: ' + (error.response?.data?.message || 'Unknown error'));
  });
}

function confirmDelete(appointment) {
  appointmentToDelete.value = appointment;
  deleteModal.value = true;
}

function deleteAppointment() {
  if (!appointmentToDelete.value) return;
  
  router.delete(route('appointments.destroy', appointmentToDelete.value.id), {
    onSuccess: () => {
      deleteModal.value = false;
      appointmentToDelete.value = null;
    }
  });
}

const debouncedSearch = createDebounce(() => {
  applyFilters();
}, 500);

function applyFilters() {
  router.get(
    route('appointments.index'), 
    {
      search: filters.value.search,
      dateRange: filters.value.dateRange,
      status: filters.value.status
    },
    {
      preserveState: true,
      replace: true
    }
  );
}

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', closeDropdowns);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdowns);
  
  if (activeDropdown.value !== null) {
    togglePageScroll(false);
  }
});

function closeDropdowns(event) {
  // Only close if clicking outside dropdown
  if (activeDropdown.value !== null && !event.target.closest('.dropdown-container')) {
    activeDropdown.value = null;
    togglePageScroll(false);
  }
}
</script>

<style>
/* Optional: Add this class for limiting lines in descriptions */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>