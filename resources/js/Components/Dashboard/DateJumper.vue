<template>
  <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
    <label 
      for="date-jumper" 
      :class="['text-sm', labelClass || 'text-gray-700']"
    >
      Go to date:
    </label>
    <div class="flex items-center w-full">
      <input 
        id="date-jumper"
        type="date" 
        v-model="localDate"
        :class="[
          'text-sm block w-full p-2', 
          inputClass || 'bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-indigo-500 focus:border-indigo-500'
        ]"
      />
      <button 
        @click="$emit('jump')"
        :class="[
          'ml-2 inline-flex items-center p-2 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2', 
          buttonClass || 'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'
        ]"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    required: true
  },
  inputClass: {
    type: String,
    default: ''
  },
  labelClass: {
    type: String,
    default: ''
  },
  buttonClass: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue', 'jump']);

const localDate = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});
</script>