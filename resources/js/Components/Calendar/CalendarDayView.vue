<template>
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="border-b border-gray-200 p-4 bg-gray-50">
      <div class="text-lg font-medium">
        {{ dateDisplay }}
      </div>
    </div>
    
    <div class="divide-y divide-gray-100">
      <div 
        v-for="hour in hourSlots" 
        :key="hour.time"
        class="flex border-b border-gray-100"
      >
        <div class="w-20 py-2 px-2 text-xs text-gray-500 text-right border-r border-gray-100">
          {{ hour.label }}
        </div>
        <div class="flex-1 min-h-[60px] relative p-1">
          <slot :hour="hour"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { format, addHours, startOfDay, isValid } from 'date-fns';

const props = defineProps({
  date: {
    type: Date,
    required: true
  },
  startHour: {
    type: Number,
    default: 8 // 8 AM
  },
  endHour: {
    type: Number,
    default: 18 // 6 PM
  }
});

// Safely format the date with validation
const dateDisplay = computed(() => {
  try {
    // Make sure the date is a valid Date object
    if (props.date instanceof Date && isValid(props.date)) {
      return format(props.date, 'EEEE, MMMM d, yyyy');
    }
    return 'Invalid Date';
  } catch (e) {
    console.error('Error formatting date:', e);
    return 'Error formatting date';
  }
});

const hourSlots = computed(() => {
  try {
    const slots = [];
    // Ensure we have a valid date before processing
    if (!(props.date instanceof Date) || !isValid(props.date)) {
      console.error('Invalid date provided to CalendarDayView');
      return slots;
    }

    const dayStart = startOfDay(props.date);
    
    for (let i = props.startHour; i <= props.endHour; i++) {
      const time = addHours(dayStart, i);
      slots.push({
        time: time.toISOString(),
        label: format(time, 'h:mm a')
      });
    }
    
    return slots;
  } catch (e) {
    console.error('Error generating hour slots:', e);
    return [];
  }
});
</script>