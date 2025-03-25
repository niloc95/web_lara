<template>
  <DoughnutChart :chartData="chartData" />
</template>

<script setup>
import DoughnutChart from '@/Components/Charts/DoughnutChart.vue';

defineProps({
  'chart-data': {
    type: Object,
    required: true
  }
});
</script>