<!-- resources/js/Components/Pagination.vue Nilo -->
<template>
  <!-- Add this at the top for debugging -->
  <!-- <pre v-if="debug" class="text-xs p-4 bg-gray-100 rounded">
    {{ JSON.stringify(links, null, 2) }}
  </pre> -->
  
  <div class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <a v-if="links[0].url" 
         :href="links[0].url" 
         class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        Previous
      </a>
      <span v-else
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed">
        Previous
      </span>
      
      <a v-if="links[links.length - 1].url" 
         :href="links[links.length - 1].url" 
         class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        Next
      </a>
      <span v-else
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed">
        Next
      </span>
    </div>
    
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ links[1].label.split(' ')[1] }}</span>
          to
          <span class="font-medium">{{ links[1].label.split(' ')[3] }}</span>
          of
          <span class="font-medium">{{ links[1].label.split(' ')[5] }}</span>
          results
        </p>
      </div>
      
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
          <template v-for="(link, key) in links" :key="key">
            <!-- Use Link for prev/next if active -->
            <Link v-if="(key === 0 || key === links.length - 1) && link.url" 
               :href="link.url" 
               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span v-if="key === 0">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </span>
              <span v-else>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </span>
            </Link>
            
            <!-- Show disabled prev/next if not active -->
            <span v-else-if="key === 0 || key === links.length - 1" 
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
              <span v-if="key === 0">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </span>
              <span v-else>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </span>
            </span>
            
            <!-- Regular page link or indicator -->
            <template v-else-if="key !== 1">
              <Link v-if="link.url" 
                  :href="link.url" 
                  :class="{'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': link.active, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active}"
                  class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                {{ link.label }}
              </Link>
              <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                ...
              </span>
            </template>
          </template>
        </nav>
      </div>
    </div>
  </div>
  
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
  links: Array
});

// Add debugging functionality
// const debug = ref(true); // Set to false in production
</script>