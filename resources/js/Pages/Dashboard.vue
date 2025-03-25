<!-- resources/js/Pages/Dashboard/Index.vue -->
<template>
  <AppLayout 
    :user="auth.user"
    :navigation-items="sidebarItems"
    :quick-actions="quickActions"
  >
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
    <!-- Stats Grid with tooltips -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
      <Tooltip text="Total upcoming appointments for the next 30 days">
        <StatsCard 
          title="Upcoming Appointments" 
          :value="stats.upcoming_appointments" 
          color="primary" 
          icon="fa-calendar-check" 
        />
      </Tooltip>
      
      <Tooltip text="Scheduled appointments for today">
        <StatsCard 
          title="Today's Appointments" 
          :value="stats.today_appointments" 
          color="secondary" 
          icon="fa-calendar-day" 
        />
      </Tooltip>
      
      <Tooltip text="Total revenue from appointments in the last 7 days">
        <StatsCard 
          title="Weekly Revenue" 
          :value="`ZAR ${stats.weekly_revenue || 0}`" 
          color="blue" 
          icon="fa-money-bill-wave" 
        />
      </Tooltip>
      
      <Tooltip text="Number of clients with at least one appointment">
        <StatsCard 
          title="Active Clients" 
          :value="stats.active_clients || 0" 
          color="yellow" 
          icon="fa-users" 
        />
      </Tooltip>
    </div>
    
    <!-- Activity & Charts Grid with tooltips -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Recent Sales Summary (Span 2 cols) -->
      <div class="lg:col-span-2 bg-white dark:bg-neutral-800 rounded-lg shadow dark:shadow-neutral-900/30 p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h2 class="text-lg font-semibold dark:text-white">Today's Schedule</h2>
            <p class="text-sm text-neutral-400 dark:text-neutral-500">Working hours: 8:00 AM - 4:00 PM</p>
          </div>
          <Tooltip text="More options" position="left">
            <button class="text-neutral-400 dark:text-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300">
              <i class="fas fa-ellipsis-v"></i>
            </button>
          </Tooltip>
        </div>
        
        <!-- Utilization summary -->
        <div class="flex justify-between items-center mb-4">
          <div class="text-xl font-bold dark:text-white">
            ZAR {{ stats.weekly_revenue || 0 }}
          </div>
          
          <div v-if="utilizationPercentage !== null" 
               :class="[
                 'text-xs font-medium px-2 py-1 rounded-full',
                 utilizationClass
               ]">
            {{ utilizationPercentage }}% Utilized
          </div>
        </div>
        
        <div class="flex space-x-8 mb-6">
          <div>
            <p class="text-sm text-neutral-400 dark:text-neutral-500">Appointments</p>
            <p class="text-base font-semibold dark:text-neutral-200">{{ stats.today_appointments || 0 }}</p>
          </div>
          <div>
            <p class="text-sm text-neutral-400 dark:text-neutral-500">Available Time</p>
            <p class="text-base font-semibold dark:text-neutral-200">{{ availableHours }} hours</p>
          </div>
        </div>
        
        <!-- Bar chart with vibrant colors -->
        <div class="h-64">
          <SimpleBarChart 
            :chartData="dailyScheduleData"
            :utilizationData="utilizationData"
            :showWorkingHoursSummary="false"
            colorScheme="vibrant"
            :options="{
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  backgroundColor: '#24292f'
                }
              }
            }"
          />
        </div>
      </div>
      
      <!-- Status Chart with tooltip -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow dark:shadow-neutral-900/30 p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-lg font-semibold dark:text-white">Appointments by Status</h2>
          <Tooltip text="More options" position="left">
            <button class="text-neutral-400 dark:text-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300">
              <i class="fas fa-ellipsis-v"></i>
            </button>
          </Tooltip>
        </div>
        
        <div class="h-64">
          <SimpleDoughnutChart :chart-data="{
            labels: statusStats.labels,
            datasets: [{ 
              data: statusStats.data,
              backgroundColor: statusStats.backgrounds
            }]
          }" />
        </div>
      </div>
    </div>
    
    <!-- Activity Log & Services Grid with tooltips -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Appointment Activity with tooltips for status -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow dark:shadow-neutral-900/30 p-6">
        <h2 class="text-lg font-semibold dark:text-white mb-4">Appointments Activity</h2>
        
        <div v-if="todaysAppointments && todaysAppointments.length > 0">
          <div 
            v-for="appointment in todaysAppointments.slice(0, 3)" 
            :key="appointment.id" 
            class="py-4 flex border-b border-neutral-100 last:border-0"
          >
            <div class="flex flex-col items-center mr-4 w-10">
              <div class="text-base font-semibold dark:text-white">{{ formatDate(appointment.start_time, 'dd') }}</div>
              <div class="text-xs text-neutral-400 dark:text-neutral-500">{{ formatDate(appointment.start_time, 'MMM') }}</div>
            </div>
            
            <div class="flex-1">
              <div class="text-sm text-neutral-500 dark:text-neutral-400 mb-1">
                {{ formatDate(appointment.start_time, 'EEE, dd MMM yyyy HH:mm') }}
                <Tooltip :text="getStatusDescription(appointment.status)" position="right">
                  <span class="ml-2 text-xs font-medium" :class="getStatusColorClass(appointment.status)">
                    {{ capitalize(appointment.status) }}
                  </span>
                </Tooltip>
              </div>
              <div class="font-semibold dark:text-white mb-1">{{ appointment.service?.name || 'Service' }}</div>
              <div class="text-sm text-neutral-500 dark:text-neutral-400">
                {{ appointment.client?.name || 'Client' }}, 
                {{ appointment.duration || '60' }}min with 
                {{ appointment.staff?.name || 'Staff' }}
              </div>
            </div>
            
            <div class="font-semibold dark:text-white self-center">
              ZAR {{ appointment.price || '0' }}
            </div>
          </div>
        </div>
        
        <div v-else class="py-10 flex flex-col items-center justify-center text-center">
          <div class="text-4xl text-neutral-300 dark:text-neutral-500 mb-4">
            <i class="fas fa-calendar"></i>
          </div>
          <h3 class="text-lg font-semibold dark:text-white mb-2">No Recent Activity</h3>
          <p class="text-sm text-neutral-500 dark:text-neutral-400 max-w-xs">
            Create appointments to see your activity history here
          </p>
        </div>
      </div>
      
      <!-- Top Services -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow dark:shadow-neutral-900/30 p-6">
        <h2 class="text-lg font-semibold dark:text-white mb-4">Top Services</h2>
        
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left text-sm font-medium text-neutral-500 dark:text-neutral-400 pb-3">Service</th>
              <th class="text-left text-sm font-medium text-neutral-500 dark:text-neutral-400 pb-3">This month</th>
              <th class="text-left text-sm font-medium text-neutral-500 dark:text-neutral-400 pb-3">Last month</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="topServices && topServices.length">
              <td v-for="service in topServices" :key="service.name" class="py-3 border-b border-neutral-100 dark:border-neutral-700">
                <div class="font-medium dark:text-white">{{ service.name }}</div>
                <div class="text-sm dark:text-neutral-400">{{ service.thisMonth }}</div>
                <div class="text-sm dark:text-neutral-400">{{ service.lastMonth }}</div>
              </td>
            </tr>
            <tr v-else>
              <td colspan="3" class="py-10">
                <div class="flex flex-col items-center justify-center text-center">
                  <div class="text-4xl text-neutral-300 dark:text-neutral-500 mb-4">
                    <i class="fas fa-chart-bar"></i>
                  </div>
                  <h3 class="text-lg font-semibold dark:text-white mb-2">No Services Data</h3>
                  <p class="text-sm text-neutral-500 dark:text-neutral-400 max-w-xs">
                    Complete appointments to see service statistics
                  </p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>

  <!-- Add this anywhere visible in your Dashboard.vue -->
  <button 
    @click="$page.props.showDebugModal = true" 
    class="fixed bottom-4 right-4 bg-primary-500 text-white px-4 py-2 rounded-full z-50"
  >
    Debug Modal
  </button>

  <!-- Then add this just above the closing </template> tag -->
  <QuickActionsMenu 
    :is-open="$page.props.showDebugModal"
    :actions="[
      {
        label: 'Test Action',
        description: 'Just a test action',
        icon: 'fa-bug',
        color: 'primary',
        href: '#'
      }
    ]"
    @close="$page.props.showDebugModal = false"
  />
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';
import { onMounted, onBeforeUnmount } from 'vue';

// Import layout
import AppLayout from '@/Layouts/AppLayout.vue';

// Import chart components
import SimpleDoughnutChart from '../Components/Charts/SimpleDoughnutChart.vue';
import SimpleBarChart from '../Components/Charts/SimpleBarChart.vue';

// Import dashboard components
import StatsCard from '@/Components/Dashboard/StatsCard.vue';
import AppointmentsTable from '../Components/Dashboard/AppointmentsTable.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';

// Props
const props = defineProps({
  auth: Object,
  stats: Object,
  todaysAppointments: Array,
  statusStats: Object,
  appointmentsByDay: Object,
  topServices: Array
});

// Computed properties - MUST BE DEFINED BEFORE THEY ARE USED
const userInitials = computed(() => {
  if (!props.user || !props.user.name) return 'U';
  return props.user.name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
});

const formattedDateYmd = computed(() => {
  return format(new Date(), 'yyyy-MM-dd');
});

// State
const showQuickActions = ref(false);
const jumpDate = ref(format(new Date(), 'yyyy-MM-dd'));
const activeDropdown = ref(null);

// NOW we can use formattedDateYmd in sidebarItems
const sidebarItems = [
  { 
    label: 'Dashboard', 
    icon: 'fa-home', 
    href: route('dashboard'),
    active: route().current('dashboard')
  }
];

// Add Calendar link ONLY if the route exists
try {
  sidebarItems.push({ 
    label: 'Calendar', 
    icon: 'fa-calendar-alt', 
    href: route('calendar.day', { date: formattedDateYmd.value }), // NOTE: .value is required
    active: route().current('calendar.*')
  });
} catch (e) {
  console.warn('Route calendar.day not available');
}

// Add other links only if their routes exist
try {
  sidebarItems.push({ 
    label: 'Booking', 
    icon: 'fa-calendar-check', 
    href: route('appointments.create'),
    active: route().current('appointments.*') 
  });
} catch (e) {
  console.warn('Route appointments.create not available');
}

try {
  sidebarItems.push({ 
    label: 'Clients', 
    icon: 'fa-users', 
    href: route('clients.index'),
    active: route().current('clients.*')
  });
} catch (e) {
  console.warn('Route clients.index not available');
}

try {
  sidebarItems.push({ 
    label: 'Settings', 
    icon: 'fa-cog', 
    href: route('settings.index'),
    active: route().current('settings.*')
  });
} catch (e) {
  console.warn('Route settings.index not available');
}

// Quick actions
const quickActions = [
  { 
    label: 'New Appointment', 
    description: 'Schedule a new appointment',
    icon: 'fa-calendar-plus', 
    color: 'indigo',
    href: '#'  // Default fallback
  }
];

// Try to add routes that exist
try {
  quickActions[0].href = route('appointments.create');
} catch (e) {}

// Rest of your methods...
function formatDate(dateString, formatString = 'MMM d, yyyy') {
  if (!dateString) return '';
  return format(parseISO(dateString), formatString);
}

function goToDate() {
  try {
    router.get(route('calendar.day', { date: jumpDate.value }));
  } catch (e) {
    console.warn('Route calendar.day not available');
  }
}

// Add other methods you need...

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

// Add this function to check if an appointment is coming up soon
function isUpcoming(dateString) {
  if (!dateString) return false;
  
  const now = new Date();
  const appointmentTime = new Date(dateString);
  const diffMinutes = (appointmentTime - now) / (1000 * 60);
  
  // Return true if appointment is within next 30 minutes
  return diffMinutes > 0 && diffMinutes <= 30;
}

// Add this function to get client initials
function getInitials(name) {
  if (!name) return '';
  
  return name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
}

// Add this function for delete confirmation
function confirmDelete(appointment) {
  if (confirm(`Are you sure you want to delete the appointment with ${appointment.client.name}?`)) {
    router.delete(route('appointments.destroy', appointment.id), {
      preserveScroll: true,
      onSuccess: () => {
        // Show success toast or notification if desired
      }
    });
  }
}

// Add these methods to handle appointment deletion and status change
function handleAppointmentDeleted(appointmentId) {
  // Logic to handle appointment deletion
}

function handleStatusChanged(appointmentId, newStatus) {
  // Logic to handle status change
}

// Define capitalize as a constant so it's exposed to the template
const capitalize = (string) => {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
};

// Keep the existing script, just add topServices
const topServices = [
  { name: 'Haircut', thisMonth: 12, lastMonth: 8 },
  { name: 'Blow Dry', thisMonth: 8, lastMonth: 5 },
  { name: 'Color', thisMonth: 5, lastMonth: 7 }
];

// Add helper methods for status colors and descriptions
function getStatusColorClass(status) {
  switch (status) {
    case 'confirmed':
      return 'text-green-500';
    case 'pending':
      return 'text-yellow-500';
    case 'cancelled':
      return 'text-red-500';
    case 'completed':
      return 'text-blue-500';
    default:
      return 'text-neutral-500';
  }
}

function getStatusDescription(status) {
  switch (status) {
    case 'confirmed':
      return 'Appointment has been confirmed by the client';
    case 'pending':
      return 'Waiting for client confirmation';
    case 'cancelled':
      return 'This appointment has been cancelled';
    case 'completed':
      return 'Appointment has been completed';
    default:
      return 'Unknown status';
  }
}

// Transform your appointments into chart data
const dailyScheduleData = computed(() => {
  // Format your appointments into the expected chart data format
  const hours = ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'];
  const appointmentCounts = hours.map(hour => {
    const hourNumber = parseInt(hour);
    // Count appointments starting at this hour
    return props.todaysAppointments.filter(appt => {
      const apptHour = new Date(appt.start_time).getHours();
      return apptHour === hourNumber;
    }).length;
  });
  
  return {
    labels: hours,
    datasets: [
      {
        label: 'Appointments',
        data: appointmentCounts,
        borderRadius: 4
      }
    ]
  };
});

// Calculate hours utilized per hour of the day
const utilizationData = computed(() => {
  const hourUtilization = {};
  
  // Initialize hours from 8-16
  for (let i = 8; i < 16; i++) {
    hourUtilization[i] = 0;
  }
  
  // Calculate utilization for each appointment
  props.todaysAppointments.forEach(appt => {
    const startTime = new Date(appt.start_time);
    const endTime = new Date(appt.end_time);
    const startHour = startTime.getHours();
    const startMinuteFraction = startTime.getMinutes() / 60;
    const durationHours = (endTime - startTime) / (1000 * 60 * 60);
    
    // Add the appointment duration to the correct hour bucket
    let remainingDuration = durationHours;
    let currentHour = startHour;
    let currentMinuteFraction = startMinuteFraction;
    
    while (remainingDuration > 0 && currentHour < 16) {
      // How much of this hour is used
      const hoursInCurrentSlot = Math.min(1 - currentMinuteFraction, remainingDuration);
      
      // Add to utilization
      if (currentHour >= 8) {
        hourUtilization[currentHour] = (hourUtilization[currentHour] || 0) + hoursInCurrentSlot;
      }
      
      // Move to next hour
      remainingDuration -= hoursInCurrentSlot;
      currentHour++;
      currentMinuteFraction = 0; // Reset for subsequent hours
    }
  });
  
  return hourUtilization;
});

// Add this computed property for utilization color
const utilizationPercentage = computed(() => {
  if (!utilizationData.value) return null;
  
  const totalWorkingHours = 8; // 8:00 AM to 4:00 PM
  const totalBookedHours = Object.values(utilizationData.value).reduce((sum, hours) => sum + hours, 0);
  
  // Calculate percentage of working hours that are booked
  return Math.round((totalBookedHours / totalWorkingHours) * 100);
});

// Add a CSS class computed property for utilization badge
const utilizationClass = computed(() => {
  if (utilizationPercentage.value === null) return '';
  
  if (utilizationPercentage.value < 30) {
    return 'bg-github-success/20 text-github-success dark:bg-github-dark-success/20 dark:text-github-dark-success';
  } else if (utilizationPercentage.value < 70) {
    return 'bg-github-warning/20 text-github-warning dark:bg-github-dark-warning/20 dark:text-github-dark-warning';
  } else {
    return 'bg-github-error/20 text-github-error dark:bg-github-dark-error/20 dark:text-github-dark-error';
  }
});

// Calculate available hours
const availableHours = computed(() => {
  if (!utilizationData.value) return 8; // Default working hours (8 to 16)
  
  const totalWorkingHours = 8; // 8:00 AM to 4:00 PM
  const totalBookedHours = Object.values(utilizationData.value).reduce((sum, hours) => sum + hours, 0);
  
  return Math.max(0, Math.round((totalWorkingHours - totalBookedHours) * 10) / 10); // Round to 1 decimal
});
</script>