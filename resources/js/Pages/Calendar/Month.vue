<!-- resources/js/Pages/Calendar/Month.vue -->
<template>
  <AppLayout title="Monthly Calendar">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h1 class="text-2xl font-semibold mb-6">Monthly Calendar</h1>
          
          <!-- Redirect to the main calendar view with month as default -->
          <p>Redirecting to main calendar...</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

onMounted(() => {
  // Redirect to the main calendar
  router.visit(route('calendar'));
});
</script>