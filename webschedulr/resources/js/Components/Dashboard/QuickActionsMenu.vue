// resources/js/Components/Dashboard/QuickActionsMenu.vue
<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40" @click="$emit('close')"></div>
    
    <div 
      v-if="isOpen" 
      class="fixed inset-y-0 left-20 right-0 md:right-auto md:w-80 z-50 bg-white shadow-xl transform transition-all"
      :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }"
    >
      <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="p-6">
        <div class="grid grid-cols-1 gap-4">
          <Link 
            v-for="(action, index) in safeActions" 
            :key="index"
            :href="action.href"
            class="flex items-center p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
            @click="$emit('close')"
          >
            <div :class="`w-10 h-10 rounded-full bg-${action.color}-100 flex items-center justify-center mr-4`">
              <i :class="['fas', action.icon, `text-${action.color}-600`]"></i>
            </div>
            <div>
              <div class="font-medium text-gray-900">{{ action.label }}</div>
              <div class="text-sm text-gray-500">{{ action.description }}</div>
            </div>
          </Link>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
          <DateJumper v-model="jumpDate" @jump="goToDate" />
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
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

// Check if routes exist before using them
const safeActions = computed(() => {
  return props.actions.filter(action => {
    // If the action already has href, it's safe to use
    return !!action.href;
  });
});

defineEmits(['close']);

const jumpDate = ref(format(new Date(), 'yyyy-MM-dd'));

function goToDate() {
  try {
    router.get(route('calendar.day', { date: jumpDate.value }));
  } catch (e) {
    console.warn('Route calendar.day not available');
  }
}
</script>