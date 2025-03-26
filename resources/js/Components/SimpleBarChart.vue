<template>
  <div 
    :class="[
      'chart-container rounded-lg p-4', 
      wrapperClass,
      bordered ? 'border border-github-border dark:border-github-dark-border' : ''
    ]"
    :style="{ height: height }"
  >
    <div class="flex justify-between items-start mb-2">
      <div>
        <h3 v-if="title" class="text-base font-semibold text-github-heading dark:text-github-dark-heading">{{ title }}</h3>
        <div v-if="subtitle" class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">{{ subtitle }}</div>
      </div>
      
      <!-- Utilization badge -->
      <div v-if="showUtilization && utilizationPercentage !== null" 
           :class="[
             'text-xs font-medium px-2 py-1 rounded-full',
             utilizationClass
           ]">
        {{ utilizationPercentage }}% Utilized
      </div>
    </div>
    
    <div v-if="loading" class="flex items-center justify-center h-full">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
    </div>
    
    <div v-else-if="noData" class="flex items-center justify-center h-full">
      <div class="text-neutral-400 dark:text-neutral-500 text-center">
        <i class="fas fa-chart-bar text-2xl mb-2"></i>
        <p>{{ noDataText }}</p>
      </div>
    </div>
    
    <div v-else class="h-full">
      <BarChart 
        :chartData="processedChartData" 
        :chartOptions="mergedOptions"
      />
    </div>
    
    <!-- Business hours utilization summary -->
    <div v-if="showWorkingHoursSummary && !loading && !noData" class="mt-4 pt-3 border-t border-github-border/30 dark:border-github-dark-border/30">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <div class="text-xs flex flex-col">
          <span class="text-neutral-500 dark:text-neutral-400">Working Hours</span>
          <span class="text-github-heading dark:text-github-dark-heading font-medium">
            {{ formattedWorkingHours }}
          </span>
        </div>
        <div class="text-xs flex flex-col">
          <span class="text-neutral-500 dark:text-neutral-400">Most Busy</span>
          <span class="text-github-heading dark:text-github-dark-heading font-medium">
            {{ mostBusyHour || 'N/A' }}
          </span>
        </div>
        <div class="text-xs flex flex-col">
          <span class="text-neutral-500 dark:text-neutral-400">Available Time</span>
          <span class="text-github-heading dark:text-github-dark-heading font-medium">
            {{ availableHours }} hours
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import BarChart from '@/Components/Charts/BarChart.vue';

const props = defineProps({
  chartData: {
    type: Object,
    required: true
  },
  title: {
    type: String,
    default: ''
  },
  subtitle: {
    type: String,
    default: ''
  },
  bordered: {
    type: Boolean,
    default: true
  },
  height: {
    type: String,
    default: '300px'
  },
  wrapperClass: {
    type: String,
    default: 'bg-white dark:bg-github-dark-bg'
  },
  loading: {
    type: Boolean,
    default: false
  },
  noDataText: {
    type: String,
    default: 'No data available'
  },
  options: {
    type: Object,
    default: () => ({})
  },
  colorScheme: {
    type: String,
    default: 'vibrant', // 'vibrant', 'pastel', 'monochrome', 'github'
  },
  showUtilization: {
    type: Boolean,
    default: false
  },
  utilizationData: {
    type: Object,
    default: null
  },
  workingHoursStart: {
    type: Number,
    default: 8 // 8:00 AM
  },
  workingHoursEnd: {
    type: Number,
    default: 16 // 4:00 PM
  },
  showWorkingHoursSummary: {
    type: Boolean,
    default: false
  }
});

// Determine if there's no data to display
const noData = computed(() => {
  if (!props.chartData || !props.chartData.datasets) return true;
  
  // Check if any dataset has data
  return props.chartData.datasets.every(dataset => {
    return !dataset.data || dataset.data.length === 0 || dataset.data.every(item => item === 0);
  });
});

// Vibrant color palettes
const colorPalettes = {
  vibrant: [
    '#0969da', // Blue
    '#cf222e', // Red
    '#8250df', // Purple
    '#bf8700', // Yellow
    '#2da44e', // Green
    '#d73a49', // Coral
    '#6f42c1', // Indigo
    '#ea4aaa', // Pink
  ],
  pastel: [
    '#79c0ff', // Pastel blue
    '#ffa198', // Pastel red
    '#d2a8ff', // Pastel purple
    '#ffdf5d', // Pastel yellow
    '#7ee787', // Pastel green
    '#ffadbe', // Pastel pink
    '#a5d6ff', // Pastel cyan
    '#fddf68', // Pastel gold
  ],
  monochrome: [
    '#0969da', // Primary blue
    '#218bff', // Lighter blue
    '#388bfd', // Light blue
    '#1f6feb', // Medium blue
    '#0550ae', // Dark blue
    '#033d8a', // Darker blue
    '#0a2540', // Very dark blue
    '#58a6ff', // Bright blue
  ],
  github: [
    '#0969da', // GitHub blue
    '#238636', // GitHub green
    '#cf222e', // GitHub red
    '#bf8700', // GitHub yellow
    '#6f42c1', // GitHub purple
    '#6e7781', // GitHub neutral
  ]
};

// Process chart data to apply vibrant colors
const processedChartData = computed(() => {
  if (!props.chartData || !props.chartData.datasets) return props.chartData;
  
  const palette = colorPalettes[props.colorScheme] || colorPalettes.vibrant;
  
  const newData = {
    ...props.chartData,
    datasets: props.chartData.datasets.map((dataset, index) => {
      return {
        ...dataset,
        backgroundColor: dataset.backgroundColor || palette[index % palette.length]
      };
    })
  };
  
  return newData;
});

// Calculate utilization percentage based on working hours
const utilizationPercentage = computed(() => {
  if (!props.utilizationData || !props.showUtilization) return null;
  
  const totalWorkingHours = props.workingHoursEnd - props.workingHoursStart;
  const totalBookedHours = Object.values(props.utilizationData).reduce((sum, hours) => sum + hours, 0);
  
  // Calculate percentage of working hours that are booked
  return Math.round((totalBookedHours / totalWorkingHours) * 100);
});

// CSS class for utilization badge based on percentage
const utilizationClass = computed(() => {
  if (utilizationPercentage.value === null) return '';
  
  if (utilizationPercentage.value < 30) {
    return 'bg-github-success/20 text-github-success dark:bg-github-dark-success/20 dark:text-github-dark-success';
  } else if (utilizationPercentage.value < 70) {
    return 'bg-github-warning/20 text-github-warning dark:bg-github-dark-warning/20 dark:text-github-dark-warning';
  } else {
    return 'bg-github-error/20 text-github-error dark:bg-github-dark-error/20 dark:text-github-dark-error';
  }
});

// Format working hours as readable string
const formattedWorkingHours = computed(() => {
  const formatHour = (hour) => {
    const period = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour > 12 ? hour - 12 : hour;
    return `${displayHour}:00 ${period}`;
  };
  
  return `${formatHour(props.workingHoursStart)} - ${formatHour(props.workingHoursEnd)}`;
});

// Calculate available hours
const availableHours = computed(() => {
  if (!props.utilizationData) return props.workingHoursEnd - props.workingHoursStart;
  
  const totalWorkingHours = props.workingHoursEnd - props.workingHoursStart;
  const totalBookedHours = Object.values(props.utilizationData).reduce((sum, hours) => sum + hours, 0);
  
  return Math.max(0, totalWorkingHours - totalBookedHours);
});

// Determine the most busy hour
const mostBusyHour = computed(() => {
  if (!props.utilizationData) return null;
  
  let maxHour = null;
  let maxValue = 0;
  
  Object.entries(props.utilizationData).forEach(([hour, value]) => {
    if (value > maxValue) {
      maxValue = value;
      maxHour = hour;
    }
  });
  
  if (!maxHour) return null;
  
  // Format hour for display
  const hourNum = parseInt(maxHour);
  const period = hourNum >= 12 ? 'PM' : 'AM';
  const displayHour = hourNum > 12 ? hourNum - 12 : hourNum;
  return `${displayHour}:00 ${period}`;
});

// Default chart options with GitHub-inspired colorblind palette
const defaultOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top',
      labels: {
        usePointStyle: true,
        pointStyle: 'circle',
        boxWidth: 6,
        font: {
          family: "'Figtree', sans-serif",
          size: 12
        }
      }
    },
    tooltip: {
      backgroundColor: '#24292f',
      titleFont: {
        family: "'Figtree', sans-serif",
        size: 13
      },
      bodyFont: {
        family: "'Figtree', sans-serif",
        size: 12
      },
      padding: 10,
      cornerRadius: 4,
      displayColors: true
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          family: "'Figtree', sans-serif",
          size: 11
        }
      }
    },
    y: {
      beginAtZero: true,
      grid: {
        color: 'rgba(208, 215, 222, 0.25)',
      },
      ticks: {
        font: {
          family: "'Figtree', sans-serif",
          size: 11
        }
      }
    }
  }
};

// Merge custom options with defaults
const mergedOptions = computed(() => {
  return { 
    ...defaultOptions, 
    ...props.options,
    plugins: {
      ...defaultOptions.plugins,
      ...(props.options.plugins || {})
    },
    scales: {
      ...defaultOptions.scales,
      ...(props.options.scales || {})
    }
  };
});
</script>

<style scoped>
.chart-container {
  position: relative;
  transition: all 0.2s ease;
}
</style>