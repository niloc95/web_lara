<!-- resources/js/Pages/Dashboard/Index.vue -->
<template>
  <AppLayout title="Dashboard">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
          <!-- Upcoming Appointments Card -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-l-4 border-indigo-500 h-full flex flex-col justify-between">
              <div>
                <div class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-1">Upcoming Appointments</div>
                <div class="text-2xl font-bold text-gray-800">{{ stats.upcoming_appointments }}</div>
              </div>
              <div class="mt-2 text-gray-400">
                <i class="fas fa-calendar-check text-xl"></i>
              </div>
            </div>
          </div>
          
          <!-- Today's Appointments Card -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-l-4 border-green-500 h-full flex flex-col justify-between">
              <div>
                <div class="text-xs font-semibold text-green-500 uppercase tracking-wider mb-1">Today's Appointments</div>
                <div class="text-2xl font-bold text-gray-800">{{ stats.today_appointments }}</div>
              </div>
              <div class="mt-2 text-gray-400">
                <i class="fas fa-calendar-day text-xl"></i>
              </div>
            </div>
          </div>
          
          <!-- Tomorrow's Appointments Card -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-l-4 border-blue-500 h-full flex flex-col justify-between">
              <div>
                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-1">Tomorrow's Appointments</div>
                <div class="text-2xl font-bold text-gray-800">{{ stats.tomorrow_appointments }}</div>
              </div>
              <div class="mt-2 text-gray-400">
                <i class="fas fa-calendar-plus text-xl"></i>
              </div>
            </div>
          </div>
          
          <!-- Today's Date Card -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 border-l-4 border-yellow-500 h-full flex flex-col justify-between">
              <div>
                <div class="text-xs font-semibold text-yellow-500 uppercase tracking-wider mb-1">Today's Date</div>
                <div class="text-2xl font-bold text-gray-800">{{ formattedDate }}</div>
              </div>
              <div class="mt-2 text-gray-400">
                <i class="fas fa-calendar text-xl"></i>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
          <div class="p-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg text-gray-800">Quick Actions</h2>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
              <Link :href="route('appointments.create')" class="flex items-center justify-center p-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Appointment
              </Link>
              <Link :href="route('clients.create')" class="flex items-center justify-center p-4 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                New Client
              </Link>
              <Link :href="route('services.create')" class="flex items-center justify-center p-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                New Service
              </Link>
              <Link :href="route('appointments.index')" class="flex items-center justify-center p-4 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                View All Appointments
              </Link>
            </div>
            
            <div class="mt-6">
              <h3 class="font-medium text-gray-800 mb-2">Jump to Date</h3>
              <div class="flex">
                <input 
                  v-model="jumpDate" 
                  type="date" 
                  class="rounded-l-md border-r-0 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                />
                <button 
                  @click="goToDate" 
                  class="px-4 py-2 bg-indigo-500 text-white rounded-r-md hover:bg-indigo-600 transition"
                >
                  View Day
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800">Today's Schedule</h2>
            <Link :href="route('calendar.day', { date: formattedDateYmd })" class="px-3 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600">
              View Full Day
            </Link>
          </div>
          <div class="p-6">
            <div v-if="!todaysAppointments.length" class="text-center py-8">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-gray-500">No appointments scheduled for today.</p>
              <Link :href="route('appointments.create')" class="inline-block mt-4 px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Schedule Appointment
              </Link>
            </div>
            <div v-else class="relative overflow-visible">
              <table class="min-w-full divide-y divide-gray-200">
                <thead>
                  <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="appointment in todaysAppointments" :key="appointment.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatTime(appointment.start_time) }} - {{ formatTime(appointment.end_time || 
                        new Date(new Date(appointment.start_time).getTime() + 3600000).toISOString()) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ appointment.client.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <span v-if="appointment.service?.color" class="inline-block w-3 h-3 rounded-full mr-2" :style="{ backgroundColor: appointment.service.color }"></span>
                      {{ appointment.service?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(appointment.status)" class="px-2 py-1 text-xs rounded-full">
                        {{ capitalize(appointment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="relative">
                        <button 
                          @click.stop="toggleDropdown(appointment.id)" 
                          :id="`dropdown-btn-${appointment.id}`"
                          class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none"
                        >
                          Actions
                          <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Status Chart -->
          <div class="md:col-span-1 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-4 border-b border-gray-200">
              <h2 class="font-semibold text-lg text-gray-800">Appointments by Status</h2>
            </div>
            <div class="p-4">
              <SimpleDoughnutChart :chart-data="{
                labels: statusStats.labels,
                datasets: [{ 
                  data: statusStats.data,
                  backgroundColor: statusStats.backgrounds
                }]
              }" />
            </div>
          </div>

          <!-- Day of Week Chart -->
          <div class="md:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-4 border-b border-gray-200">
              <h2 class="font-semibold text-lg text-gray-800">Appointments by Day of Week</h2>
            </div>
            <div class="p-4">
              <SimpleBarChart :chart-data="{
                labels: appointmentsByDay.labels,
                datasets: [{ 
                  data: appointmentsByDay.data,
                  backgroundColor: '#4e73df'
                }]
              }" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Add this at the end of your template -->
  <Teleport to="body">
    <div 
      v-for="appointment in todaysAppointments" 
      :key="`dropdown-${appointment.id}`"
      v-show="activeDropdown === appointment.id"
      class="fixed z-50 bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none w-56"
      :style="getDropdownPosition(appointment.id)"
    >
      <div class="py-1">
        <Link :href="route('appointments.edit', appointment.id)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          Edit
        </Link>
      </div>
      <div class="py-1">
        <a 
          v-for="status in ['pending', 'confirmed', 'completed', 'cancelled']" 
          :key="status"
          href="#" 
          @click.prevent="updateStatus(appointment.id, status)" 
          :class="[
            'block px-4 py-2 text-sm hover:bg-gray-100',
            appointment.status === status ? 'font-bold' : '',
            status === 'cancelled' ? 'text-red-600' : 'text-gray-700'
          ]"
        >
          Mark as {{ capitalize(status) }}
        </a>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format, parseISO } from 'date-fns';
import SimpleDoughnutChart from '@/Components/Charts/SimpleDoughnutChart.vue';
import SimpleBarChart from '@/Components/Charts/SimpleBarChart.vue';
import { Teleport } from 'vue'; // Add this import

// Props with defaults to avoid errors
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      upcoming_appointments: 0,
      today_appointments: 0,
      tomorrow_appointments: 0
    })
  },
  todaysAppointments: {
    type: Array,
    default: () => []
  },
  statusStats: {
    type: Object,
    default: () => ({
      labels: [],
      data: [],
      backgrounds: []
    })
  },
  appointmentsByDay: {
    type: Object,
    default: () => ({
      labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
      data: [0, 0, 0, 0, 0, 0, 0]
    })
  },
  recentClients: {
    type: Array,
    default: () => []
  }
});

// State
const jumpDate = ref(format(new Date(), 'yyyy-MM-dd'));
const activeDropdown = ref(null); // Add this for dropdown state management

// Computed
const formattedDate = computed(() => {
  return format(new Date(), 'MMM d, yyyy');
});

const formattedDateYmd = computed(() => {
  return format(new Date(), 'yyyy-MM-dd');
});

// Methods
function formatDate(dateString, formatString = 'MMM d, yyyy') {
  if (!dateString) return '';
  return format(parseISO(dateString), formatString);
}

function formatTime(dateString) {
  if (!dateString) return '';
  return format(parseISO(dateString), 'h:mm a');
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

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Add these new methods for dropdown functionality
function toggleDropdown(id) {
  if (activeDropdown.value === id) {
    activeDropdown.value = null;
    enableBodyScroll(); // Enable scrolling when dropdown closes
  } else {
    // If there was already an open dropdown, close it first
    if (activeDropdown.value !== null) {
      activeDropdown.value = id;
    } else {
      activeDropdown.value = id;
      disableBodyScroll(); // Disable scrolling when dropdown opens
    }
  }
}

function updateStatus(appointmentId, status) {
  router.patch(
    route('appointments.status', appointmentId), 
    { status }, 
    { 
      preserveScroll: true,
      onSuccess: () => {
        activeDropdown.value = null;
        enableBodyScroll(); // Enable scrolling when dropdown closes
      }
    }
  );
}

// Add this method to position the teleported dropdown
function getDropdownPosition(id) {
  const button = document.getElementById(`dropdown-btn-${id}`);
  if (!button) return { top: '0px', left: '0px' };
  
  const rect = button.getBoundingClientRect();
  const viewportHeight = window.innerHeight;
  const spaceBelow = viewportHeight - rect.bottom;
  const dropdownHeight = 200; // Approximate height of dropdown
  
  // Show above if not enough space below
  if (spaceBelow < dropdownHeight) {
    return {
      top: `${rect.top + window.scrollY - dropdownHeight - 5}px`,
      left: `${rect.right + window.scrollX - 156}px` // 156 = dropdown width
    };
  }
  
  // Otherwise show below
  return {
    top: `${rect.bottom + window.scrollY + 5}px`,
    left: `${rect.right + window.scrollX - 156}px` // 156 = dropdown width
  };
}

// Click handler to close dropdowns when clicking outside
function closeDropdowns(e) {
  // Check if clicked element is a button with ID starting with 'dropdown-btn-'
  const isDropdownButton = e.target.closest('[id^="dropdown-btn-"]');
  
  if (!isDropdownButton) {
    activeDropdown.value = null;
    enableBodyScroll(); // Re-enable scrolling when dropdown closes on outside click
  }
}

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', closeDropdowns);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', closeDropdowns);
});

// Add these functions to your script setup
function disableBodyScroll() {
  // Save current scroll position
  const scrollY = window.scrollY;
  
  // Add styles to body to prevent scrolling but maintain position
  document.body.style.position = 'fixed';
  document.body.style.top = `-${scrollY}px`;
  document.body.style.width = '100%';
  document.body.style.overflowY = 'hidden';
  
  // Store the scroll position for later restoration
  document.body.dataset.scrollY = scrollY;
}

function enableBodyScroll() {
  // Get saved scroll position
  const scrollY = document.body.dataset.scrollY || '0';
  
  // Remove styles that prevented scrolling
  document.body.style.position = '';
  document.body.style.top = '';
  document.body.style.width = '';
  document.body.style.overflowY = '';
  
  // Scroll to the saved position
  window.scrollTo(0, parseInt(scrollY || '0'));
}

function goToDate() {
  router.get(route('calendar.day', { date: jumpDate.value }));
}
</script>