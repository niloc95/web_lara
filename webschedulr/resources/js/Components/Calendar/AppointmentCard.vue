<template>
  <div 
    :class="statusClasses"
    class="p-2 rounded-md cursor-pointer text-xs hover:opacity-90 transition-opacity"
    @click="$emit('click')"
  >
    <div class="font-medium">{{ timeDisplay }}</div>
    <div class="truncate">{{ appointment.client.name }}</div>
    <div v-if="appointment.service?.name" class="text-xs opacity-80 truncate">
      {{ appointment.service.name }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  appointment: {
    type: Object,
    required: true
  }
});

defineEmits(['click']);

const timeDisplay = computed(() => {
  if (!props.appointment.start_time) return '';
  return new Date(props.appointment.start_time)
    .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
});

const statusClasses = computed(() => {
  switch (props.appointment.status) {
    case 'confirmed': return 'bg-green-100 text-green-800';
    case 'completed': return 'bg-blue-100 text-blue-800';
    case 'cancelled': return 'bg-red-100 text-red-800';
    case 'pending':
    default: return 'bg-yellow-100 text-yellow-800';
  }
});
</script>