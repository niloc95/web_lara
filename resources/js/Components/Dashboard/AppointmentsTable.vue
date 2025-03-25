// resources/js/Components/Dashboard/AppointmentsTable.vue
<template>
  <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
      <h2 class="font-semibold text-lg text-gray-800">{{ title }}</h2>
      <div class="flex items-center space-x-2">
        <Link :href="newAppointmentRoute" class="inline-flex items-center px-3 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          New
        </Link>
        <Link :href="fullDayRoute" class="inline-flex items-center px-3 py-1 bg-gray-500 text-white text-sm rounded hover:bg-gray-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          Full Day
        </Link>
      </div>
    </div>
    
    <div v-if="!appointments.length" class="text-center py-8">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <p class="text-gray-500">No appointments scheduled.</p>
    </div>
    
    <div v-else>
      <!-- Simple appointment list for now -->
      <div v-for="appointment in appointments" :key="appointment.id" class="p-4 border-b border-gray-200">
        <div class="flex justify-between">
          <div>
            <div class="font-medium">{{ appointment.client?.name || 'Client' }}</div>
            <div class="text-sm text-gray-500">
              {{ formatTime(appointment.start_time) }} - {{ formatTime(appointment.end_time) }}
            </div>
          </div>
          <div>
            <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getStatusClass(appointment.status)]">
              {{ capitalize(appointment.status) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';

defineProps({
  appointments: {
    type: Array,
    required: true
  },
  title: {
    type: String,
    default: "Today's Schedule"
  },
  newAppointmentRoute: {
    type: String,
    default: () => route('appointments.create')
  },
  fullDayRoute: {
    type: String,
    default: () => route('calendar.day', { date: format(new Date(), 'yyyy-MM-dd') })
  }
});

const emit = defineEmits(['appointment-deleted', 'status-changed']);

// Methods
function formatTime(dateString) {
  if (!dateString) return '';
  return format(parseISO(dateString), 'h:mm a');
}

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': 
      return 'bg-green-100 text-green-800';
    case 'completed': 
      return 'bg-blue-100 text-blue-800';
    case 'cancelled': 
      return 'bg-red-100 text-red-800';
    case 'pending':
    default: 
      return 'bg-yellow-100 text-yellow-800';
  }
}
</script>