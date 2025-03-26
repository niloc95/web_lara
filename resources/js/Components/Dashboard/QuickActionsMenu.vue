// resources/js/Components/Dashboard/QuickActionsMenu.vue
<template>
  <Teleport to="body">
    <transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="transform translate-x-full"
      enter-to-class="transform translate-x-0"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="transform translate-x-0"
      leave-to-class="transform translate-x-full"
    >
      <div v-if="isOpen" class="fixed inset-y-0 right-0 z-[1000] w-96 bg-white dark:bg-neutral-800 shadow-xl overflow-y-auto">
        <div class="p-6 border-b border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
          <h3 class="text-lg font-medium text-github-heading dark:text-github-dark-heading">Quick Actions</h3>
          <button @click="$emit('close')" class="text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="p-6">
          <!-- Date Jumper Section -->
          <div class="mb-6 border-b border-neutral-200 dark:border-neutral-700 pb-6">
            <h4 class="text-sm font-medium text-github-heading dark:text-github-dark-heading mb-3">Jump to Date</h4>
            <DateJumper 
              v-model="jumpDate" 
              @jump="goToDate"
              :input-class="'bg-github-input dark:bg-github-dark-input border-github-border dark:border-github-dark-border text-github-text dark:text-github-dark-text rounded-lg focus:ring-github-accent focus:border-github-accent'"
              :label-class="'text-github-label dark:text-github-dark-label'"
              :button-class="'bg-primary hover:bg-primary-600 focus:ring-primary text-white'"
            />
          </div>
          
          <!-- Quick Actions Section -->
          <h4 class="text-sm font-medium text-github-heading dark:text-github-dark-heading mb-3">Common Tasks</h4>
          <div class="space-y-4">
            <div v-for="(action, index) in actions" :key="index"
                 class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition cursor-pointer"
                 @click="navigateTo(action.href)">
              <div class="flex items-center">
                <div :class="`text-${action.color || 'primary'} text-xl w-8`">
                  <i :class="['fas', action.icon]"></i>
                </div>
                <div class="ml-4">
                  <h4 class="text-sm font-medium text-github-heading dark:text-github-dark-heading">{{ action.label }}</h4>
                  <p class="text-xs text-github-text dark:text-github-dark-text mt-1">{{ action.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity z-[999]" @click="$emit('close')"></div>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { format } from 'date-fns';
import DateJumper from './DateJumper.vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  actions: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['close']);

// Set up the date jumper with today's date
const jumpDate = ref(format(new Date(), 'yyyy-MM-dd'));

// Function to navigate to the selected date
function goToDate() {
  try {
    router.visit(route('calendar.day', { date: jumpDate.value }));
    // Close the menu after navigation
    setTimeout(() => {
      emit('close');
    }, 100);
  } catch (e) {
    console.warn('Route calendar.day not available:', e);
  }
}

// Function to navigate to action links
const navigateTo = (href) => {
  if (href) {
    router.visit(href);
    // Close the menu after navigation
    setTimeout(() => {
      emit('close');
    }, 100);
  }
};
</script>

<style scoped>
/* No problematic :deep() styles */
</style>