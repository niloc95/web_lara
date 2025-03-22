<!-- resources/js/Components/Charts/SimpleBarChart.vue -->
<template>
  <div class="h-full w-full p-4">
    <h3 class="text-sm font-medium text-gray-700 mb-4">Appointments by Day</h3>
    <div class="flex items-end h-48 space-x-2">
      <div 
        v-for="(value, index) in chartData.datasets[0].data" 
        :key="index"
        class="bg-indigo-500 w-full rounded-t-md transition-all duration-500 hover:bg-indigo-600 flex flex-col justify-end"
        :style="{ height: `${calculateHeight(value)}%` }"
      >
        <div class="text-xs text-center text-white p-1">{{ value }}</div>
      </div>
    </div>
    <div class="flex justify-between mt-2">
      <div 
        v-for="(label, index) in chartData.labels" 
        :key="index"
        class="text-xs text-center w-full"
      >
        {{ label.substring(0, 3) }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  chartData: {
    type: Object,
    default: () => ({
      labels: [],
      datasets: [{ data: [] }]
    })
  }
});

function calculateHeight(value) {
  const maxValue = Math.max(...props.chartData.datasets[0].data, 1);
  return (value / maxValue) * 95; // 95% to leave room for the number
}
</script>