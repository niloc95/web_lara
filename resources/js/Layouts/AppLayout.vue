<script>
// Regular script for props definition
import { usePage as inertiaPage } from '@inertiajs/vue3'; // Renamed to avoid conflict

export default {
  props: {
    companyName: {
      type: String,
      default: 'WebSchedulr'
    },
    title: {
      type: String,
      default: ''
    },
    user: {
      type: Object,
      required: false,
      default: () => inertiaPage().props.value.auth?.user || {} // Use renamed import
    }
  }
};
</script>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import TopHeader from '@/Components/Layout/TopHeader.vue';
import Sidebar from '@/Components/Layout/Sidebar.vue';
import QuickActionsMenu from '@/Components/Dashboard/QuickActionsMenu.vue';
import eventBus from '@/eventBus';

// Debug the imports
console.log('QuickActionsMenu loaded:', !!QuickActionsMenu);

const page = usePage();

// Make sure this is declared and initialized BEFORE it's used
const showQuickActions = ref(false);

// Add a watcher to debug state changes
watch(showQuickActions, (newVal) => {
  console.log('Quick actions visibility changed:', newVal);
});

// Function to open the quick actions menu
function openQuickActionsMenu() {
  console.log('Opening quick actions menu from AppLayout');
  showQuickActions.value = true;
}

// Safely get current component name with fallback
const currentRouteName = computed(() => {
  if (page.component && typeof page.component.value === 'string') {
    return page.component.value.split('/')[0];
  }
  return '';
});

// Navigation items with added Appointments entry
const navigationItems = computed(() => [
  {
    label: 'Dashboard',
    icon: 'fa-chart-line',
    href: route('dashboard'),
    active: currentRouteName.value === 'Dashboard'
  },
  {
    label: 'Calendar',
    icon: 'fa-calendar-alt',
    href: route('calendar'),
    active: currentRouteName.value === 'Calendar'
  },
  {
    label: 'Bookings',
    icon: 'fa-calendar-check',
    href: route('appointments.index'),
    active: currentRouteName.value === 'Appointments'
  },
  {
    label: 'Clients',
    icon: 'fa-users',
    href: route('clients.index'),
    active: currentRouteName.value === 'Clients'
  },
  {
    label: 'Services',
    icon: 'fa-concierge-bell',
    href: route('services.index'),
    active: currentRouteName.value === 'Services'
  },
  {
    label: 'Settings',
    icon: 'fa-cog',
    href: route('settings.index'),
    active: currentRouteName.value === 'Settings'
  }
]);

// Make sure quickActions is defined AFTER showQuickActions
const quickActions = [
  {
    label: 'New Appointment',
    description: 'Schedule a new appointment',
    icon: 'fa-calendar-plus',
    color: 'primary',
    href: route('appointments.create')
  },
  {
    label: 'New Client',
    description: 'Add a new client',
    icon: 'fa-user-plus',
    color: 'secondary',
    href: route('clients.create')
  }
];

// Listen for business name changes
onMounted(() => {
  eventBus.on('business-name-changed', updateBusinessName);
  eventBus.on('business-logo-changed', updateBusinessLogo);
});

onUnmounted(() => {
  eventBus.off('business-name-changed', updateBusinessName);
  eventBus.off('business-logo-changed', updateBusinessLogo);
});

function updateBusinessName(newName) {
  // Update the business name in your layout
  // This depends on how your layout is structured
  console.log('Business name updated to:', newName);
  
  // If you're using Inertia.js shared props, you might need to:
  // usePage().props.value.appName = newName;
  
  // Or update your own reactive variable
}

function updateBusinessLogo(newLogoPath) {
  // Update the logo in your layout
  console.log('Business logo updated to:', newLogoPath);
  
  // If you're using a reactive variable for the logo:
  // logo.value = newLogoPath;
}
</script>

<template>
  <div class="flex flex-col min-h-screen bg-neutral-50 dark:bg-neutral-950">
    <!-- TopHeader no longer needs company name -->
    <TopHeader 
      :user="user"
    />
    
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar - Notice we're using the function directly -->
      <Sidebar 
        :navigation-items="navigationItems" 
        @open-quick-actions="openQuickActionsMenu"
      />
      
      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto">
        <div class="p-6">
          <slot></slot>
        </div>
      </main>
    </div>
    
    <!-- Debug info -->
    <div v-if="false" class="fixed bottom-0 right-0 bg-black text-white p-2 z-[100]">
      showQuickActions: {{ showQuickActions }}
    </div>
    
    <!-- Quick Actions Menu - Make sure we're passing the right props -->
    <QuickActionsMenu 
      :is-open="showQuickActions"
      :actions="quickActions"
      @close="showQuickActions = false"
    />
  </div>

</template>