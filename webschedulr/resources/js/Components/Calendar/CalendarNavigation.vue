<template>
  <div class="flex items-center justify-between mb-4">
    <div class="flex-1">
      <h2 class="text-xl font-semibold text-gray-800">
        {{ title }}
      </h2>
      <div class="text-sm text-gray-500">{{ subtitle }}</div>
    </div>
    
    <div class="flex items-center space-x-1">
      <button 
        @click="$emit('previous')" 
        class="p-2 rounded hover:bg-gray-100"
        aria-label="Previous"
      >
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>
      
      <button 
        @click="$emit('today')" 
        class="px-3 py-1 text-sm font-medium rounded hover:bg-gray-100"
      >
        Today
      </button>
      
      <button 
        @click="$emit('next')" 
        class="p-2 rounded hover:bg-gray-100"
        aria-label="Next"
      >
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
    </div>
    
    <div class="flex items-center space-x-2">
      <button 
        v-for="view in viewOptions" 
        :key="view.value"
        @click="$emit('change-view', view.value)"
        class="px-3 py-1 text-sm rounded" 
        :class="currentView === view.value ? 'bg-blue-100 text-blue-800 font-medium' : 'hover:bg-gray-100'"
      >
        {{ view.label }}
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  currentView: {
    type: String,
    default: 'month'
  },
  viewOptions: {
    type: Array,
    default: () => [
      { label: 'Month', value: 'month' },
      { label: 'Week', value: 'week' },
      { label: 'Day', value: 'day' }
    ]
  }
});

defineEmits(['previous', 'next', 'today', 'change-view']);
</script>