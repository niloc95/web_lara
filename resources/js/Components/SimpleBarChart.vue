<template>
  <div class="chart-container">
    <BarChart :chartData="chartData" />
  </div>
</template>

<script setup>
import BarChart from '@/Components/Charts/BarChart.vue';

defineProps({
  'chart-data': {
    type: Object,
    required: true
  }
});
</script>