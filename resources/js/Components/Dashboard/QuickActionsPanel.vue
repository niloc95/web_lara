// resources/js/Components/Dashboard/QuickActionsPanel.vue
<template>
  <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
    <div class="p-4 border-b border-gray-200">
      <h2 class="font-semibold text-lg text-gray-800">{{ title }}</h2>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <slot></slot>
      </div>
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: 'Quick Actions'
  }
});
</script>