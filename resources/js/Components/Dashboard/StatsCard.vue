// resources/js/Components/Dashboard/StatsCard.vue
<template>
  <div class="bg-white dark:bg-neutral-800 rounded-lg shadow dark:shadow-neutral-900/30">
    <div class="p-5 flex items-center">
      <div :class="`h-12 w-12 rounded-lg flex items-center justify-center text-${color}-600 dark:text-${color}-400 bg-${color}-100 dark:bg-${color}-900/30 mr-4`">
        <i :class="['fas', icon, 'text-xl']"></i>
      </div>
      <div>
        <h4 class="text-sm font-medium text-neutral-400 dark:text-neutral-500">{{ title }}</h4>
        <div class="mt-1 text-2xl font-bold text-neutral-900 dark:text-white">{{ value }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  color: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'green', 'blue', 'yellow', 'red', 'purple', 'gray'].includes(value)
  },
  icon: {
    type: String,
    default: 'fa-chart-line'
  }
});
</script>