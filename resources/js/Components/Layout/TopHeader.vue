<!-- resources/js/Components/Layout/TopHeader.vue -->
<template>
  <header class="w-full bg-white dark:bg-neutral-900 border-b border-neutral-100 dark:border-neutral-800 h-16 px-6 flex items-center justify-between z-10">
    <div class="flex items-center">
      <div v-if="logoUrl" class="h-10 w-auto">
        <img :src="logoUrl" alt="Company Logo" class="h-full w-auto object-contain">
      </div>
      <div v-else class="h-10 w-10 bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-300 rounded-md flex items-center justify-center font-bold text-lg">
        {{ companyInitials }}
      </div>
      <!-- Company name now directly reads from Inertia props -->
      <h1 class="ml-3 text-lg font-bold text-primary-600 dark:text-primary-400">
        {{ businessName }}
      </h1>
    </div>
    
    <div class="flex items-center space-x-5">
      <!-- Dark Mode Toggle -->
      <DarkModeToggle />
      
      <button class="w-10 h-10 rounded-full flex items-center justify-center text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-800">
        <i class="fas fa-search text-lg"></i>
      </button>
      <button class="w-10 h-10 rounded-full flex items-center justify-center text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-800 relative">
        <i class="fas fa-bullhorn text-lg"></i>
        <span class="absolute -top-1 -right-1 h-5 w-5 bg-primary-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
      </button>
      <button class="w-10 h-10 rounded-full flex items-center justify-center text-neutral-500 dark:text-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-800 relative">
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

<script>
// Regular script for props definition
import { usePage as inertiaPage } from '@inertiajs/vue3';

export default {
  props: {
    title: {
      type: String,
      default: ''
    },
    user: {
      type: Object,
      required: false,
      default: () => inertiaPage().props.value.auth?.user || {}
    }
  }
};
</script>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import DarkModeToggle from '../UI/DarkModeToggle.vue';
import eventBus from '@/eventBus';

const page = usePage();

// Props for other data
const props = defineProps({
  logoUrl: {
    type: String,
    default: null
  },
  user: {
    type: Object,
    required: true
  }
});

// Initialize from localStorage if available
const businessNameOverride = ref(localStorage.getItem('business_name_override'));
const logoOverride = ref(localStorage.getItem('logo_override'));

// Listen for changes
onMounted(() => {
  eventBus.on('business-name-changed', (newName) => {
    businessNameOverride.value = newName;
    // Save to localStorage for persistence across navigation
    localStorage.setItem('business_name_override', newName);
  });
  
  eventBus.on('business-logo-changed', (newLogo) => {
    logoOverride.value = newLogo;
    // Save to localStorage if not null
    if (newLogo) {
      localStorage.setItem('logo_override', newLogo);
    } else {
      localStorage.removeItem('logo_override');
    }
  });
});

// Update the computed property to use the override
const businessName = computed(() => {
  // Use the override if available
  if (businessNameOverride.value) {
    return businessNameOverride.value;
  }
  
  // Otherwise use the normal logic
  if (!page || !page.props || !page.props.value) {
    return 'WebSchedulr';
  }
  
  return page.props.value.businessSettings?.name || 
         page.props.value.appName || 
         'WebSchedulr';
});

// Listen for business name changes
onMounted(() => {
  eventBus.on('business-name-changed', (newName) => {
    // Force refresh of the computed property by triggering component update
    businessNameOverride.value = newName;
  });
  
  // Listen for logo changes
  eventBus.on('business-logo-changed', (newLogo) => {
    logoOverride.value = newLogo;
  });
});

onUnmounted(() => {
  // Clean up event listeners
  eventBus.off('business-name-changed');
  eventBus.off('business-logo-changed');
});

// Get logo URL with override support
const logoUrl = computed(() => {
  if (logoOverride.value) {
    return logoOverride.value;
  }
  
  return page.props.value?.businessSettings?.logo || null;
});

// Company initials now uses the direct businessName
const companyInitials = computed(() => {
  if (!businessName.value) return 'WS';
  
  return businessName.value
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .substring(0, 2);
});

const isUserMenuOpen = ref(false);
const userMenuRef = ref(null);

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