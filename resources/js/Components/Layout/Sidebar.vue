<!-- resources/js/Components/Layout/Sidebar.vue -->
<template>
  <aside class="w-[72px] bg-white dark:bg-neutral-900 border-r border-neutral-100 dark:border-neutral-800 flex flex-col items-center py-5">
    <Tooltip 
      v-for="(item, index) in navigationItems" 
      :key="index"
      :text="item.label"
      position="right"
    >
      <Link 
        :href="item.href" 
        class="w-full"
      >
        <div 
          :class="[
            'w-full py-4 flex flex-col items-center text-xs font-medium transition-colors relative',
            item.active
              ? 'text-primary-600 dark:text-primary-400' 
              : 'text-neutral-500 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400'
          ]"
        >
          <!-- Active indicator -->
          <div v-if="item.active" class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-1 bg-primary-600 dark:bg-primary-400 rounded-r"></div>
          
          <!-- Content container with padding to avoid overlap -->
          <div class="flex flex-col items-center w-full">
            <div class="text-[20px] mb-1">
              <i :class="['fas', item.icon]"></i>
            </div>
            <span class="text-[11px]">{{ item.label }}</span>
          </div>
        </div>
      </Link>
    </Tooltip>
    
    <!-- Quick Action Button at bottom -->
    <Tooltip text="Create new appointment or client" position="right">
      <div class="mt-auto w-full flex flex-col items-center py-4">
        <button 
          @click="openQuickActions"
          class="flex flex-col items-center text-xs font-medium text-neutral-500 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400"
        >
          <div class="h-8 w-8 bg-primary-500 text-white rounded-full flex items-center justify-center mb-1">
            <i class="fas fa-plus"></i>
          </div>
          <span class="text-[11px]">New</span>
        </button>
      </div>
    </Tooltip>
  </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Tooltip from '../UI/Tooltip.vue';

defineProps({
  navigationItems: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['open-quick-actions']);

function openQuickActions(event) {
  // Prevent default to avoid page reloads
  event.preventDefault();
  console.log('Opening quick actions menu');
  emit('open-quick-actions');
}
</script>