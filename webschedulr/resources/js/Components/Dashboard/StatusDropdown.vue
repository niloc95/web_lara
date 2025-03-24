// resources/js/Components/Dashboard/StatusDropdown.vue
<template>
  <div class="relative">
    <button 
      @click.stop="toggleDropdown"
      :id="`dropdown-btn-${appointment.id}`"
      :class="[
        'relative inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium w-auto cursor-pointer',
        getStatusClass(appointment.status)
      ]"
    >
      {{ capitalize(appointment.status) }}
      <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>
    
    <div 
      v-if="isOpen"
      class="absolute z-10 mt-1 bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none w-56"
      :style="dropdownPosition"
    >
      <div class="py-1">
        <Link :href="route('appointments.edit', appointment.id)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          Edit
        </Link>
      </div>
      <div class="py-1">
        <a 
          v-for="status in statusOptions" 
          :key="status"
          href="#" 
          @click.prevent="updateStatus(status)" 
          :class="[
            'block px-4 py-2 text-sm hover:bg-gray-100',
            appointment.status === status ? 'font-bold' : '',
            status === 'cancelled' ? 'text-red-600' : 'text-gray-700'
          ]"
        >
          Mark as {{ capitalize(status) }}
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  appointment: {
    type: Object,
    required: true
  },
  statusOptions: {
    type: Array,
    default: () => ['pending', 'confirmed', 'completed', 'cancelled']
  }
});

const emit = defineEmits(['status-changed']);

const isOpen = ref(false);
const dropdownPosition = ref({});

function toggleDropdown() {
  isOpen.value = !isOpen.value;
  
  if (isOpen.value) {
    calculatePosition();
    document.addEventListener('click', handleOutsideClick);
  } else {
    document.removeEventListener('click', handleOutsideClick);
  }
}

function calculatePosition() {
  const button = document.getElementById(`dropdown-btn-${props.appointment.id}`);
  if (!button) return;
  
  const rect = button.getBoundingClientRect();
  const viewportHeight = window.innerHeight;
  const spaceBelow = viewportHeight - rect.bottom;
  const dropdownHeight = 200; // Approximate height of dropdown
  
  // Show above if not enough space below
  if (spaceBelow < dropdownHeight) {
    dropdownPosition.value = {
      bottom: '100%',
      marginBottom: '5px',
      right: '0'
    };
  } else {
    // Otherwise show below
    dropdownPosition.value = {
      top: '100%',
      marginTop: '5px',
      right: '0'
    };
  }
}

function handleOutsideClick(event) {
  const dropdown = event.target.closest(`[id^="dropdown-btn-${props.appointment.id}"]`);
  if (!dropdown) {
    isOpen.value = false;
    document.removeEventListener('click', handleOutsideClick);
  }
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': 
      return 'bg-green-100 text-green-800 hover:bg-green-200 border border-green-300';
    case 'completed': 
      return 'bg-blue-100 text-blue-800 hover:bg-blue-200 border border-blue-300';
    case 'cancelled': 
      return 'bg-red-100 text-red-800 hover:bg-red-200 border border-red-300';
    case 'pending':
    default: 
      return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 border border-yellow-300';
  }
}

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function updateStatus(status) {
  router.patch(
    route('appointments.status', props.appointment.id), 
    { status }, 
    { 
      preserveScroll: true,
      onSuccess: () => {
        isOpen.value = false;
        emit('status-changed', { 
          id: props.appointment.id,
          status: status
        });
      }
    }
  );
}

onBeforeUnmount(() => {
  document.removeEventListener('click', handleOutsideClick);
});
</script>