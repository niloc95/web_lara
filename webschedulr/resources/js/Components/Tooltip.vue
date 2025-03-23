<!-- components/Tooltip.vue -->
<template>
  <div class="relative inline-block">
    <div @mouseenter="show = true" @mouseleave="show = false">
      <slot></slot>
    </div>
    <div 
      v-show="show" 
      class="absolute z-10 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm tooltip dark:bg-gray-700"
      :class="[positionClass]"
    >
      {{ text }}
      <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  text: {
    type: String,
    required: true
  },
  position: {
    type: String,
    default: 'top',
    validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value)
  }
});

const show = ref(false);

const positionClass = computed(() => {
  switch (props.position) {
    case 'top': return 'bottom-full left-1/2 transform -translate-x-1/2 mb-1';
    case 'bottom': return 'top-full left-1/2 transform -translate-x-1/2 mt-1';
    case 'left': return 'right-full top-1/2 transform -translate-y-1/2 mr-1';
    case 'right': return 'left-full top-1/2 transform -translate-y-1/2 ml-1';
    default: return 'bottom-full left-1/2 transform -translate-x-1/2 mb-1';
  }
});
</script>

<style scoped>
.tooltip::after {
  content: '';
  position: absolute;
}
</style>