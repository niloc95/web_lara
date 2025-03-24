<!-- resources/js/Components/Layout/TopHeader.vue -->
<template>
  <header class="w-full bg-white border-b border-neutral-100 h-16 px-6 flex items-center justify-between z-10">
    <div class="flex items-center">
      <div v-if="logoUrl" class="h-10 w-auto">
        <img :src="logoUrl" alt="Company Logo" class="h-full w-auto">
      </div>
      <div v-else class="h-10 w-10 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center font-bold text-lg">
        {{ companyInitials }}
      </div>
      <h1 class="ml-3 text-lg font-bold text-primary-600">{{ companyName }}</h1>
    </div>
    
    <div class="flex items-center space-x-5">
      <button class="w-10 h-10 rounded-full flex items-center justify-center text-neutral-500 hover:bg-neutral-50">
        <i class="fas fa-search text-lg"></i>
      </button>
      <button class="w-10 h-10 rounded-full flex items-center justify-center text-neutral-500 hover:bg-neutral-50 relative">
        <i class="fas fa-bullhorn text-lg"></i>
        <span class="absolute -top-1 -right-1 h-5 w-5 bg-primary-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
      </button>
      <button class="w-10 h-10 rounded-full flex items-center justify-center text-neutral-500 hover:bg-neutral-50 relative">
        <i class="fas fa-bell text-lg"></i>
        <span class="absolute -top-1 -right-1 h-5 w-5 bg-primary-500 text-white text-xs rounded-full flex items-center justify-center">2</span>
      </button>
      
      <div class="relative" ref="userMenuRef">
        <button 
          @click="isUserMenuOpen = !isUserMenuOpen"
          class="flex items-center space-x-2"
        >
          <div v-if="user.avatar" class="h-10 w-10 rounded-full overflow-hidden">
            <img :src="user.avatar" alt="User avatar" class="h-full w-full object-cover">
          </div>
          <div v-else class="h-10 w-10 rounded-full bg-neutral-200 flex items-center justify-center text-neutral-700 font-medium">
            {{ userInitials }}
          </div>
          <span class="hidden md:block text-sm font-medium text-neutral-700">{{ user.name }}</span>
          <i class="fas fa-chevron-down text-neutral-400 text-xs ml-1"></i>
        </button>
        
        <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5">
          <a href="/profile" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
            Your Profile
          </a>
          <a href="/settings" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
            Settings
          </a>
          <a href="#" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50" @click.prevent="logout">
            Sign out
          </a>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  companyName: {
    type: String,
    default: 'WebSchedulr'
  },
  logoUrl: {
    type: String,
    default: null
  },
  user: {
    type: Object,
    default: () => ({
      name: 'Demo User',
      email: 'user@example.com',
      avatar: null
    })
  }
});

const isUserMenuOpen = ref(false);
const userMenuRef = ref(null);

const companyInitials = computed(() => {
  return props.companyName
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
});

const userInitials = computed(() => {
  if (!props.user || !props.user.name) return 'U';
  return props.user.name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .slice(0, 2)
    .join('');
});

function logout() {
  // You can emit an event or handle logout directly
}

// Close menu when clicking outside
function closeOnClickOutside(event) {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    isUserMenuOpen.value = false;
  }
}

onMounted(() => {
  document.addEventListener('click', closeOnClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', closeOnClickOutside);
});
</script>