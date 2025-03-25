<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue'; // Add this import
import TopHeader from '../Components/Layout/TopHeader.vue';
import Sidebar from '../Components/Layout/Sidebar.vue';
import QuickActionsMenu from '../Components/Dashboard/QuickActionsMenu.vue';

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};

const props = defineProps({
  user: {
    type: Object,
    default: () => ({
      name: 'Demo User',
      email: 'user@example.com'
    })
  },
  companyName: {
    type: String,
    default: 'WebSchedulr'
  },
  logoUrl: {
    type: String,
    default: null
  },
  navigationItems: {
    type: Array,
    required: true
  },
  quickActions: {
    type: Array,
    required: true
  }
});

const showQuickActions = ref(false);
</script>

<template>
  <div class="flex flex-col min-h-screen bg-neutral-50 dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100">
    <!-- Top Header -->
    <TopHeader 
      :company-name="companyName" 
      :logo-url="logoUrl" 
      :user="user" 
    />
    
    <!-- Content Area with Sidebar -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar 
        :navigation-items="navigationItems" 
        @open-quick-actions="showQuickActions = true"
      />
      
      <!-- Main Content -->
      <div class="flex-1 overflow-y-auto">
        <div class="p-6">
          <slot></slot>
        </div>
      </div>
    </div>
    
    <!-- Quick Actions Menu -->
    <QuickActionsMenu 
      :is-open="showQuickActions" 
      :actions="quickActions"
      @close="showQuickActions = false"
    />
  </div>
</template>