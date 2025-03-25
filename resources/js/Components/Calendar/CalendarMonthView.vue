<template>
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Days of week header -->
    <div class="grid grid-cols-7 gap-px bg-gray-200 border-b border-gray-200">
      <div v-for="day in daysOfWeek" :key="day" class="bg-gray-50 py-2 text-center text-sm text-gray-500">
        {{ day }}
      </div>
    </div>
    
    <!-- Calendar grid -->
    <div class="grid grid-cols-7 gap-px bg-gray-200">
      <CalendarDayCell
        v-for="day in calendarDays"
        :key="day.date"
        :date="day.date"
        :isToday="day.isToday"
        :isCurrentMonth="day.isCurrentMonth"
        @add-appointment="$emit('add-appointment', $event)"
      >
        <slot :day="day"></slot>
      </CalendarDayCell>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import CalendarDayCell from './CalendarDayCell.vue';

const props = defineProps({
  currentMonth: {
    type: Number,
    required: true
  },
  currentYear: {
    type: Number,
    required: true
  },
  calendarDays: {
    type: Array,
    required: true
  }
});

defineEmits(['add-appointment']);

const daysOfWeek = computed(() => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']);
</script>