<template>
  <div 
    class="min-h-[120px] bg-white p-2 border-b border-r relative"
    :class="[
      isToday ? 'bg-blue-50' : '',
      !isCurrentMonth ? 'bg-gray-50 text-gray-400' : ''
    ]"
  >
    <div class="flex justify-between items-center">
      <span 
        :class="isToday ? 'bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center' : ''"
      >
        {{ dayNumber }}
      </span>
      
      <button 
        v-if="isCurrentMonth" 
        @click="$emit('add-appointment', date)" 
        class="text-blue-500 hover:text-blue-700"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
      </button>
    </div>
    
    <!-- Appointments Container -->
    <div class="mt-1 space-y-1 overflow-y-auto max-h-[80px]">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  date: {
    type: String,
    required: true
  },
  isToday: {
    type: Boolean,
    default: false
  },
  isCurrentMonth: {
    type: Boolean,
    default: true
  }
});

defineEmits(['add-appointment']);

const dayNumber = computed(() => {
  return new Date(props.date).getDate();
});
</script>