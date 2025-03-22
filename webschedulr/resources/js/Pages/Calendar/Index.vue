<template>
  <AppLayout title="Calendar">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <!-- Calendar Navigation -->
          <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
              <button @click="previousMonth" class="p-2 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
              </button>
              <h2 class="text-xl font-semibold">{{ currentMonthName }} {{ currentYear }}</h2>
              <button @click="nextMonth" class="p-2 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
            </div>
            <div class="flex space-x-2">
              <Link :href="route('appointments.create')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                New Appointment
              </Link>
            </div>
          </div>
          
          <!-- Calendar View Selector -->
          <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
              <button 
                @click="viewMode = 'month'" 
                :class="viewMode === 'month' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
              >
                Month
              </button>
              <button 
                @click="viewMode = 'week'" 
                :class="viewMode === 'week' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
              >
                Week
              </button>
              <button 
                @click="viewMode = 'day'" 
                :class="viewMode === 'day' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
              >
                Day
              </button>
            </nav>
          </div>
          
          <!-- Month View -->
          <div v-if="viewMode === 'month'" class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Days of Week Headers -->
            <div class="grid grid-cols-7 gap-px bg-gray-200 border-b border-gray-200">
              <div v-for="day in daysOfWeek" :key="day" class="bg-gray-50 py-2 text-center text-sm text-gray-500">
                {{ day }}
              </div>
            </div>
            
            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
              <div 
                v-for="(day, index) in calendarDays" 
                :key="index" 
                :class="[
                  'bg-white min-h-[100px] p-2', 
                  day.isCurrentMonth ? 'bg-white' : 'bg-gray-50 text-gray-400',
                  day.isToday ? 'bg-blue-50' : ''
                ]"
              >
                <div class="flex justify-between">
                  <span :class="day.isToday ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center' : ''">
                    {{ day.date.getDate() }}
                  </span>
                  <button 
                    v-if="day.isCurrentMonth" 
                    @click="showAppointmentForm(day.date)" 
                    class="text-blue-500 hover:text-blue-700"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </button>
                </div>
                
                <!-- Appointments for this day -->
                <div class="mt-1 space-y-1 max-h-24 overflow-y-auto">
                  <div 
                    v-for="appointment in getAppointmentsForDay(day.date)" 
                    :key="appointment.id" 
                    :class="getStatusClass(appointment.status)"
                    class="text-xs p-1 rounded mb-1 cursor-pointer truncate"
                    @click="goToAppointment(appointment.id)"
                  >
                    {{ formatTime(appointment.start_time) }} - {{ appointment.client.name }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Week View -->
          <div v-if="viewMode === 'week'" class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Week header -->
            <div class="grid grid-cols-7 gap-px bg-gray-200 border-b border-gray-200">
              <div 
                v-for="(day, index) in weekDays" 
                :key="index" 
                class="bg-gray-50 py-2 text-center text-sm"
                :class="day.isToday ? 'bg-blue-50 font-bold' : 'text-gray-500'"
              >
                <div>{{ daysOfWeek[day.date.getDay()] }}</div>
                <div :class="day.isToday ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center mx-auto' : ''">
                  {{ day.date.getDate() }}
                </div>
              </div>
            </div>
            
            <!-- Week calendar -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
              <div 
                v-for="(day, dayIndex) in weekDays" 
                :key="dayIndex" 
                :class="['bg-white min-h-[400px] p-2', day.isToday ? 'bg-blue-50' : '']"
              >
                <div class="space-y-1">
                  <div 
                    v-for="appointment in getAppointmentsForDay(day.date)" 
                    :key="appointment.id" 
                    :class="getStatusClass(appointment.status)"
                    class="text-xs p-2 rounded mb-1 cursor-pointer"
                    @click="goToAppointment(appointment.id)"
                  >
                    <div class="font-bold">{{ formatTime(appointment.start_time) }}</div>
                    <div>{{ appointment.client.name }}</div>
                    <div class="text-xs">{{ appointment.service?.name || 'No service' }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Day View -->
          <div v-if="viewMode === 'day'" class="bg-white rounded-lg shadow overflow-hidden">
            <h3 class="text-lg font-medium p-4 bg-gray-50 border-b border-gray-200">
              {{ formatDayViewHeader(currentDay) }}
            </h3>
            
            <div class="divide-y divide-gray-200">
              <div 
                v-for="hour in dayHours" 
                :key="hour.value" 
                class="flex p-2 hover:bg-gray-50"
              >
                <div class="w-20 text-right pr-4 text-sm text-gray-500">
                  {{ hour.label }}
                </div>
                <div class="flex-1">
                  <div 
                    v-for="appointment in getAppointmentsForHour(hour.value)" 
                    :key="appointment.id" 
                    :class="getStatusClass(appointment.status)"
                    class="p-2 rounded mb-1 cursor-pointer text-sm"
                    @click="goToAppointment(appointment.id)"
                  >
                    <div class="font-bold">{{ formatTimeRange(appointment.start_time, appointment.end_time) }}</div>
                    <div>{{ appointment.client.name }}</div>
                    <div class="text-xs">{{ appointment.service?.name || 'No service' }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  appointments: Array,
  currentDate: String
});

// State
const viewMode = ref('month');
const currentMonth = ref(new Date().getMonth());
const currentYear = ref(new Date().getFullYear());
const currentDay = ref(new Date());

if (props.currentDate) {
  const date = new Date(props.currentDate);
  currentMonth.value = date.getMonth();
  currentYear.value = date.getFullYear();
  currentDay.value = date;
}

// Computed
const currentMonthName = computed(() => {
  return new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long' });
});

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const calendarDays = computed(() => {
  const days = [];
  const firstDay = new Date(currentYear.value, currentMonth.value, 1);
  const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0);
  
  // Get days from previous month to fill first week
  const firstDayOfWeek = firstDay.getDay();
  for (let i = firstDayOfWeek; i > 0; i--) {
    const date = new Date(currentYear.value, currentMonth.value, 1 - i);
    days.push({
      date,
      isCurrentMonth: false,
      isToday: isSameDate(date, new Date())
    });
  }
  
  // Current month days
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const date = new Date(currentYear.value, currentMonth.value, i);
    days.push({
      date,
      isCurrentMonth: true,
      isToday: isSameDate(date, new Date())
    });
  }
  
  // Get days from next month to fill last week
  const lastDayOfWeek = lastDay.getDay();
  for (let i = 1; i < 7 - lastDayOfWeek; i++) {
    const date = new Date(currentYear.value, currentMonth.value + 1, i);
    days.push({
      date,
      isCurrentMonth: false,
      isToday: isSameDate(date, new Date())
    });
  }
  
  return days;
});

const weekDays = computed(() => {
  const days = [];
  const firstDayOfWeek = new Date(currentDay.value);
  firstDayOfWeek.setDate(currentDay.value.getDate() - currentDay.value.getDay());
  
  for (let i = 0; i < 7; i++) {
    const date = new Date(firstDayOfWeek);
    date.setDate(firstDayOfWeek.getDate() + i);
    days.push({
      date,
      isToday: isSameDate(date, new Date())
    });
  }
  
  return days;
});

const dayHours = computed(() => {
  const hours = [];
  for (let i = 7; i < 21; i++) { // 7am to 9pm
    const hour = i % 12 || 12;
    const ampm = i < 12 ? 'AM' : 'PM';
    hours.push({ 
      value: i,
      label: `${hour}:00 ${ampm}`
    });
  }
  return hours;
});

// Methods
function previousMonth() {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value--;
  } else {
    currentMonth.value--;
  }
}

function nextMonth() {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value++;
  } else {
    currentMonth.value++;
  }
}

function isSameDate(date1, date2) {
  return date1.getDate() === date2.getDate() &&
    date1.getMonth() === date2.getMonth() &&
    date1.getFullYear() === date2.getFullYear();
}

function formatTime(dateTime) {
  if (!dateTime) return '';
  const date = new Date(dateTime);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function formatTimeRange(start, end) {
  return `${formatTime(start)} - ${formatTime(end)}`;
}

function formatDayViewHeader(date) {
  if (!date) return '';
  return date.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
}

function getAppointmentsForDay(date) {
  if (!props.appointments) return [];
  return props.appointments.filter(appointment => {
    const appointmentDate = new Date(appointment.start_time);
    return isSameDate(appointmentDate, date);
  });
}

function getAppointmentsForHour(hour) {
  if (!props.appointments) return [];
  return props.appointments.filter(appointment => {
    const appointmentDate = new Date(appointment.start_time);
    return isSameDate(appointmentDate, currentDay.value) && appointmentDate.getHours() === hour;
  });
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

function showAppointmentForm(date) {
  const formattedDate = date.toISOString().split('T')[0];
  router.visit(route('appointments.create', { date: formattedDate }));
}

function goToAppointment(appointmentId) {
  router.visit(route('appointments.edit', appointmentId));
}

// Watch for view mode changes to update the URL
watch(viewMode, (newValue) => {
  if (newValue === 'day') {
    router.get(route('calendar.day', { date: currentDay.value.toISOString().split('T')[0] }), {}, { preserveState: true });
  } else if (newValue === 'week') {
    router.get(route('calendar.week', { date: currentDay.value.toISOString().split('T')[0] }), {}, { preserveState: true });
  } else {
    router.get(route('calendar', { month: currentMonth.value + 1, year: currentYear.value }), {}, { preserveState: true });
  }
});
</script>