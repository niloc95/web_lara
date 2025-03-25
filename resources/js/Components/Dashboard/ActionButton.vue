// resources/js/Components/Dashboard/ActionButton.vue
<template>
  <Link 
    :href="href" 
    :class="[
      'flex flex-col items-center justify-center rounded-lg p-6 text-center transition-colors duration-150',
      `bg-${color}-50 border border-${color}-100 hover:bg-${color}-100`,
      `text-${color}-600 hover:text-${color}-800`
    ]"
  >
    <div :class="`bg-${color}-100 rounded-full p-3 mb-3 w-14 h-14 flex items-center justify-center`">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path v-if="icon === 'plus'" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        <path v-else-if="icon === 'calendar'" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        <path v-else-if="icon === 'user'" stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        <path v-else-if="icon === 'tag'" stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        <path v-else-if="icon === 'settings'" stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path v-else-if="icon === 'settings'" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
      </svg>
    </div>
    <span class="font-medium text-sm">{{ text }}</span>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  href: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    default: 'plus',
    validator: (value) => ['plus', 'calendar', 'user', 'tag', 'settings'].includes(value)
  },
  text: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: 'indigo'
  }
});
</script>