// resources/js/Components/Dashboard/StatsCard.vue
<template>
  <div class="bg-white rounded-lg shadow">
    <div class="p-5 flex items-center">
      <div :class="`h-12 w-12 rounded-lg flex items-center justify-center text-${color}-600 bg-${color}-100 mr-4`">
        <i :class="['fas', icon, 'text-xl']"></i>
      </div>
      <div>
        <h4 class="text-sm font-medium text-neutral-400">{{ title }}</h4>
        <div class="mt-1 text-2xl font-bold text-neutral-900">{{ value }}</div>
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