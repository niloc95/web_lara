<!-- filepath: /home/nilo/projects/web_lara/resources/js/Components/Settings/BusinessHoursManager.vue -->
<template>
  <div class="business-hours-manager">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Business Hours</h3>
    
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
      <h4 class="font-medium text-gray-700 mb-2">Current Business Hours</h4>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div v-for="day in parsedHours" :key="day.dayName" class="flex space-x-2">
          <div class="w-24 font-medium">{{ day.dayName }}:</div>
          <div v-if="day.isOpen">
            <div v-for="(range, i) in day.ranges" :key="i">
              {{ formatTime(range.startTime) }} - {{ formatTime(range.endTime) }}
            </div>
          </div>
          <div v-else class="italic text-gray-500">Closed</div>
        </div>
      </div>
    </div>
    
    <p class="text-sm text-gray-500 mb-4">
      Set your regular business hours. Customers will be able to book appointments during these times.
    </p>
    
    <!-- Business Hours Component -->
    <BusinessHours v-model="localHours" />
    
    <div class="mt-4 flex justify-end">
      <button 
        type="button" 
        @click="saveHours"
        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
        :disabled="loading"
      >
        <span v-if="loading">Saving...</span>
        <span v-else>Save Business Hours</span>
      </button>
    </div>
    
    <div v-if="error" class="mt-2 text-red-500 text-sm">
      {{ error }}
    </div>
    <div v-if="success" class="mt-2 text-green-500 text-sm">
      {{ success }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import BusinessHours from './BusinessHours.vue';

// Add this line to define emits
const emit = defineEmits(['updated']);

const props = defineProps({
  initialHours: {
    type: Array,
    default: () => []
  }
});

const parsedHours = computed(() => {
  if (!props.initialHours) return [];
  
  // If it's already an array, use it
  if (Array.isArray(props.initialHours)) {
    return props.initialHours;
  }
  
  // If it's a string, try to parse it
  if (typeof props.initialHours === 'string') {
    try {
      return JSON.parse(props.initialHours);
    } catch (e) {
      console.error('Error parsing business hours:', e);
      return [];
    }
  }
  
  return [];
});

// Use ref instead of reactive to avoid Vue 3 reactivity issues with arrays
const localHours = ref(parsedHours.value);
const loading = ref(false);
const error = ref(null);
const success = ref(null);

function saveHours() {
  loading.value = true;
  error.value = null;
  success.value = null;
  
  console.log('Saving business hours:', localHours.value);
  
  axios.post(route('settings.update.hours'), {
    business_hours: localHours.value
  })
  .then(response => {
    success.value = 'Business hours saved successfully';
    loading.value = false;
    
    // Emit event to parent for toast notification
    emit('updated');
    
    // Refresh data from server (if needed)
    setTimeout(() => {
      window.location.reload();
    }, 1500);
  })
  .catch(err => {
    console.error('Error saving business hours:', err);
    error.value = err.response?.data?.message || 'An error occurred while saving business hours';
    loading.value = false;
  });
}

function formatTime(minutes) {
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  const period = hours >= 12 ? 'PM' : 'AM';
  const hour12 = hours % 12 || 12;
  return `${hour12}:${mins.toString().padStart(2, '0')} ${period}`;
}
</script>