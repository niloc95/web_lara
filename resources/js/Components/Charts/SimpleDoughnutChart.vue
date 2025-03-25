<!-- resources/js/Components/Charts/SimpleDoughnutChart.vue -->
<template>
  <div class="h-full w-full relative p-4">
    <div class="flex flex-col items-center text-center mb-4">
      <div class="text-2xl font-bold">{{ total }}</div>
      <div class="text-sm text-gray-500">Total Appointments</div>
    </div>
    
    <div class="flex flex-wrap justify-center gap-2">
      <div 
        v-for="(label, i) in chartData.labels" 
        :key="i" 
        class="flex items-center px-2 py-1 rounded-md"
        :style="{ backgroundColor: `${chartData.datasets[0].backgroundColor[i]}30` }"
      >
        <span 
          class="inline-block w-3 h-3 rounded-full mr-2" 
          :style="{ backgroundColor: chartData.datasets[0].backgroundColor[i] }"
        ></span>
        <span class="text-sm font-medium">{{ label }}: {{ chartData.datasets[0].data[i] }}</span>
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
      datasets: [{ data: [], backgroundColor: [] }]
    })
  }
});

const total = computed(() => {
  if (!props.chartData?.datasets[0]?.data) return 0;
  return props.chartData.datasets[0].data.reduce((sum, value) => sum + value, 0);
});
</script>