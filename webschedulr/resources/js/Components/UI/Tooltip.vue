<!-- resources/js/Components/UI/Tooltip.vue -->
<template>
  <div class="inline-block relative" @mouseenter="show = true" @mouseleave="show = false">
    <!-- Default slot (the element that triggers the tooltip) -->
    <slot></slot>
    
    <!-- Tooltip content -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-150 ease-in" 
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="show" 
        :class="[
          'absolute z-50 px-2 py-1 text-xs font-medium text-white bg-neutral-800 rounded whitespace-nowrap',
          positionClasses
        ]"
      >
        {{ text }}
        <!-- Arrow -->
        <div :class="[
          'absolute w-2 h-2 bg-neutral-800 transform rotate-45',
          arrowPositionClasses
        ]"></div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

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

const positionClasses = computed(() => {
  switch (props.position) {
    case 'top':
      return 'bottom-full left-1/2 -translate-x-1/2 mb-2';
    case 'bottom':
      return 'top-full left-1/2 -translate-x-1/2 mt-2';
    case 'left':
      return 'right-full top-1/2 -translate-y-1/2 mr-2';
    case 'right':
      return 'left-full top-1/2 -translate-y-1/2 ml-2';
    default:
      return 'bottom-full left-1/2 -translate-x-1/2 mb-2';
  }
});

const arrowPositionClasses = computed(() => {
  switch (props.position) {
    case 'top':
      return 'left-1/2 -translate-x-1/2 -bottom-1';
    case 'bottom':
      return 'left-1/2 -translate-x-1/2 -top-1';
    case 'left':
      return 'top-1/2 -translate-y-1/2 -right-1';
    case 'right':
      return 'top-1/2 -translate-y-1/2 -left-1';
    default:
      return 'left-1/2 -translate-x-1/2 -bottom-1';
  }
});
</script>