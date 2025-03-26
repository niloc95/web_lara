<!-- filepath: /home/nilo/projects/web_lara/resources/js/Components/UI/Toast.vue -->
<template>
  <div 
    v-if="show"
    class="fixed bottom-5 right-5 px-4 py-2 rounded-md shadow-lg transition-all duration-300"
    :class="typeClasses"
  >
    <div class="flex items-center">
      <div v-if="type === 'success'" class="mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
      </div>
      <div v-if="type === 'error'" class="mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
      </div>
      <span>{{ message }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  message: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    default: 'success',
    validator: (value) => ['success', 'error', 'info', 'warning'].includes(value)
  },
  duration: {
    type: Number,
    default: 3000
  },
  show: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close']);

const typeClasses = computed(() => {
  switch (props.type) {
    case 'success':
      return 'bg-green-100 text-green-800 border-l-4 border-green-500';
    case 'error':
      return 'bg-red-100 text-red-800 border-l-4 border-red-500';
    case 'info':
      return 'bg-blue-100 text-blue-800 border-l-4 border-blue-500';
    case 'warning':
      return 'bg-yellow-100 text-yellow-800 border-l-4 border-yellow-500';
    default:
      return 'bg-gray-100 text-gray-800 border-l-4 border-gray-500';
  }
});

// Close toast after duration
watch(() => props.show, (newVal) => {
  if (newVal && props.duration > 0) {
    setTimeout(() => {
      emit('close');
    }, props.duration);
  }
});
</script>