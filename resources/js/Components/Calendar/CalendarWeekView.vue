<template>
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Week header -->
    <div class="grid grid-cols-7 gap-px bg-gray-200 border-b border-gray-200">
      <div 
        v-for="(day, index) in weekDays" 
        :key="index" 
        class="bg-gray-50 py-2 text-center text-sm"
        :class="day.isToday ? 'bg-blue-50 font-bold' : 'text-gray-500'"
      >
        <div>{{ daysOfWeek[day.date.getDay()] }}</div>
        <div 
          class="mx-auto w-6 h-6 flex items-center justify-center" 
          :class="day.isToday ? 'bg-blue-500 text-white rounded-full' : ''"
        >
          {{ day.date.getDate() }}
        </div>
      </div>
    </div>
    
    <!-- Week calendar -->
    <div class="grid grid-cols-7 gap-px bg-gray-200">
      <div 
        v-for="(day, dayIndex) in weekDays" 
        :key="dayIndex" 
        :class="['bg-white min-h-[400px] p-2', day.isToday ? 'bg-blue-50' : '']"
      >
        <div class="space-y-1">
          <slot :day="day"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  weekDays: {
    type: Array,
    required: true
  },
  daysOfWeek: {
    type: Array,
    default: () => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  }
});
</script>