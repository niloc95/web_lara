<!-- resources/js/Components/UI/DarkModeToggle.vue -->
<template>
  <button 
    @click="toggleDarkMode" 
    class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
    :class="[
      isDark 
        ? 'bg-primary-900 text-primary-100 hover:bg-primary-800' 
        : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'
    ]"
    aria-label="Toggle dark mode"
  >
    <i class="fas" :class="[isDark ? 'fa-sun' : 'fa-moon']"></i>
    <span class="sr-only">{{ isDark ? 'Switch to light mode' : 'Switch to dark mode' }}</span>
  </button>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const isDark = ref(false);

function toggleDarkMode() {
  isDark.value = !isDark.value;
  updateTheme();
}

function updateTheme() {
  // Update document class
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  
  // Save preference to localStorage
  localStorage.setItem('color-theme', isDark.value ? 'dark' : 'light');
}

// Check for system preference and stored preference
onMounted(() => {
  // Check localStorage first
  const storedTheme = localStorage.getItem('color-theme');
  
  if (storedTheme === 'dark' || 
      (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true;
  } else {
    isDark.value = false;
  }
  
  updateTheme();
  
  // Listen for system preference changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    // Only update if user hasn't explicitly set a preference
    if (!localStorage.getItem('color-theme')) {
      isDark.value = e.matches;
      updateTheme();
    }
  });
});
</script>