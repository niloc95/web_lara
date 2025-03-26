<!-- filepath: /home/nilo/projects/web_lara/resources/js/Components/Settings/BusinessHours.vue -->
<template>
  <div class="business-hours-container space-y-4">
    <!-- Loop through each day -->
    <div v-for="(dayData, dayIndex) in workingHours" :key="dayIndex" class="day-row bg-white dark:bg-neutral-800 p-4 rounded-lg border border-neutral-200 dark:border-neutral-700">
      <div class="flex items-start justify-between flex-wrap gap-4">
        <!-- Day Header -->
        <div class="day-header flex items-center">
          <label class="day-label flex items-center space-x-2 cursor-pointer">
            <div class="checkbox-container relative">
              <input 
                type="checkbox" 
                :name="`workingHours[${dayIndex}].isOpen`" 
                v-model="dayData.isOpen" 
                class="sr-only peer"
              >
              <span class="inline-block w-5 h-5 border border-neutral-300 dark:border-neutral-600 rounded peer-checked:bg-primary-500 peer-checked:border-primary-500 transition-colors">
                <svg v-if="dayData.isOpen" class="w-5 h-5 text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                  <path fill-rule="evenodd" d="M28.394 10.06a1.5 1.5 0 0 0-2.121-2.12l-12.94 12.939-5.94-5.94a1.5 1.5 0 0 0-2.12 2.122l7 7a1.5 1.5 0 0 0 2.12 0z" clip-rule="evenodd"></path>
                </svg>
              </span>
            </div>
            <div class="day-name font-medium">{{ dayNames[dayIndex] }}</div>
          </label>
        </div>
        
        <!-- Time Selection -->
        <div v-if="dayData.isOpen" class="time-selection flex-1 space-y-4">
          <!-- One or more time ranges per day -->
          <div 
            v-for="(timeRange, rangeIndex) in dayData.ranges" 
            :key="`${dayIndex}-${rangeIndex}`" 
            class="time-range flex flex-wrap items-end gap-3"
          >
            <!-- Start Time -->
            <div class="time-select flex-1 min-w-[120px]">
              <label :for="`${dayNames[dayIndex].toLowerCase()}-start-time-${rangeIndex}`" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                Start Time
              </label>
              <div class="select-wrapper relative">
                <select 
                  :id="`${dayNames[dayIndex].toLowerCase()}-start-time-${rangeIndex}`" 
                  :name="`workingHours[${dayIndex}].ranges[${rangeIndex}].startTime`"
                  v-model="timeRange.startTime"
                  class="appearance-none w-full pl-3 pr-8 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 rounded-md text-neutral-800 dark:text-white"
                >
                  <option v-for="time in timeOptions" :key="time.value" :value="time.value">
                    {{ time.label }}
                  </option>
                </select>
                <span class="absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none text-neutral-500">
                  <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path fill-rule="evenodd" d="M9.043 12.293a1 1 0 0 1 1.414 0L16 17.836l5.543-5.543a1 1 0 0 1 1.414 1.414l-6.25 6.25a1 1 0 0 1-1.414 0l-6.25-6.25a1 1 0 0 1 0-1.414" clip-rule="evenodd"></path>
                  </svg>
                </span>
              </div>
            </div>
            
            <span class="time-separator font-medium">-</span>
            
            <!-- End Time -->
            <div class="time-select flex-1 min-w-[120px]">
              <label :for="`${dayNames[dayIndex].toLowerCase()}-end-time-${rangeIndex}`" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                End Time
              </label>
              <div class="select-wrapper relative">
                <select 
                  :id="`${dayNames[dayIndex].toLowerCase()}-end-time-${rangeIndex}`" 
                  :name="`workingHours[${dayIndex}].ranges[${rangeIndex}].endTime`"
                  v-model="timeRange.endTime"
                  class="appearance-none w-full pl-3 pr-8 py-2 border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 rounded-md text-neutral-800 dark:text-white"
                >
                  <option v-for="time in timeOptions" :key="time.value" :value="time.value">
                    {{ time.label }}
                  </option>
                </select>
                <span class="absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none text-neutral-500">
                  <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path fill-rule="evenodd" d="M9.043 12.293a1 1 0 0 1 1.414 0L16 17.836l5.543-5.543a1 1 0 0 1 1.414 1.414l-6.25 6.25a1 1 0 0 1-1.414 0l-6.25-6.25a1 1 0 0 1 0-1.414" clip-rule="evenodd"></path>
                  </svg>
                </span>
              </div>
            </div>
            
            <!-- Remove time range button (only show if there's more than one range) -->
            <button 
              v-if="dayData.ranges.length > 1" 
              type="button" 
              @click="removeTimeRange(dayIndex, rangeIndex)"
              class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 flex items-center space-x-1 self-center mb-[5px]"
            >
              <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                <path fill-rule="evenodd" d="M16 5.333a1 1 0 0 1 1 1v9h9a1 1 0 1 1 0 2h-9v9a1 1 0 1 1-2 0v-9H6a1 1 0 1 1 0-2h9v-9a1 1 0 0 1 1-1z" clip-rule="evenodd" transform="rotate(45 16 16)"></path>
              </svg>
            </button>
          </div>
          
          <!-- Add Shift Button -->
          <button 
            type="button"
            @click="addTimeRange(dayIndex)"
            class="add-shift-btn text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 flex items-center space-x-1"
          >
            <span>Add Shift</span>
            <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
              <path fill-rule="evenodd" d="M16 5.333a1 1 0 0 1 1 1V15h8.667a1 1 0 0 1 0 2H17v8.667a1 1 0 1 1-2 0V17H6.333a1 1 0 1 1 0-2H15V6.333a1 1 0 0 1 1-1" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
        
        <!-- Closed Message -->
        <div v-else class="time-selection flex-1">
          <p class="text-neutral-500 dark:text-neutral-400 italic">Closed</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['update:modelValue']);

// Day names
const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// Debug initial props
onMounted(() => {
  console.log('BusinessHours component mounted with:', props.modelValue);
});

// Generate time options in 15-minute increments
const timeOptions = computed(() => {
  const options = [];
  for (let hour = 0; hour < 24; hour++) {
    for (let minute = 0; minute < 60; minute += 15) {
      const value = hour * 60 + minute; // Value in minutes
      const ampm = hour < 12 ? 'AM' : 'PM';
      const hour12 = hour % 12 || 12;
      const label = `${hour12.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')} ${ampm}`;
      options.push({ value, label });
    }
  }
  return options;
});

// Initialize working hours with default values or provided values
const workingHours = ref(initializeWorkingHours());

function initializeWorkingHours() {
  console.log('Initializing with:', props.modelValue);
  
  // If modelValue exists and has content, use it
  if (props.modelValue && Array.isArray(props.modelValue) && props.modelValue.length > 0) {
    console.log('Using provided business hours:', props.modelValue);
    
    return dayNames.map((day, index) => {
      // Get day data from props if available, otherwise use empty object
      const dayData = props.modelValue[index] || {};
      
      // Convert isOpen to boolean
      const isOpen = typeof dayData.isOpen === 'string'
        ? dayData.isOpen === '1' || dayData.isOpen === 'true'
        : Boolean(dayData.isOpen);
      
      // Ensure ranges is an array with numeric values
      let ranges = [];
      
      if (Array.isArray(dayData.ranges)) {
        ranges = dayData.ranges.map(range => ({
          startTime: Number(range.startTime),
          endTime: Number(range.endTime)
        }));
      }
      
      // If open but no ranges, add default
      if (isOpen && ranges.length === 0) {
        ranges = [{ startTime: 540, endTime: 1020 }]; // 9am to 5pm
      }
      
      return {
        dayName: day,
        isOpen,
        ranges
      };
    });
  }
  
  // Create default hours if no model value provided
  return dayNames.map((day, index) => {
    const isWeekday = index > 0 && index < 6;
    return {
      dayName: day,
      isOpen: isWeekday,
      ranges: isWeekday ? [{ startTime: 540, endTime: 1020 }] : [] // 9am to 5pm
    };
  });
}

// Add a new time range to a day
function addTimeRange(dayIndex) {
  const day = workingHours.value[dayIndex];
  // Set default new range (1 hour after the last range or 9 AM if no ranges)
  const lastRange = day.ranges[day.ranges.length - 1];
  const startTime = lastRange ? Math.min(lastRange.endTime + 60, 23 * 60) : 540;
  const endTime = Math.min(startTime + 60, 23 * 60 + 45);
  
  day.ranges.push({ startTime, endTime });
  emitUpdate();
}

// Remove a time range
function removeTimeRange(dayIndex, rangeIndex) {
  workingHours.value[dayIndex].ranges.splice(rangeIndex, 1);
  emitUpdate();
}

// Watch for changes to emit updates
watch(workingHours, () => {
  emitUpdate();
}, { deep: true });

// Emit updated value
function emitUpdate() {
  console.log('Emitting update:', workingHours.value);
  emit('update:modelValue', workingHours.value);
}
</script>