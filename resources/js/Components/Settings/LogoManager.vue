<template>
  <div>
    
    <div class="mt-1 flex items-center">
      <!-- Logo preview -->
      <div v-if="preview || currentLogo" class="mr-4 h-20 w-20 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
        <img :src="preview || `/storage/${currentLogo}`" alt="Business logo preview" class="h-full w-full object-contain">
      </div>
      <div v-else class="mr-4 h-20 w-20 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      
      <!-- Upload/Remove buttons -->
      <div class="flex flex-col space-y-2">
        <label class="cursor-pointer px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-md text-sm flex items-center justify-center">
          <span>Select Logo</span>
          <input 
            type="file" 
            class="hidden" 
            accept="image/*"
            @change="handleFileChange"
          >
        </label>
        <button 
          v-if="preview || currentLogo"
          type="button" 
          @click="removeLogo" 
          class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-md text-sm flex items-center justify-center"
        >
          Remove Logo
        </button>
      </div>
    </div>
    <p class="text-sm text-gray-500 mt-1">
      Upload a square image (PNG, JPG) up to 2MB. Recommended size: 200x200px.
    </p>
    <div v-if="error" class="text-red-500 text-sm mt-1">
      {{ error }}
    </div>
    <div v-if="success" class="text-green-500 text-sm mt-1">
      {{ success }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import eventBus from '@/eventBus';

const props = defineProps({
  currentLogo: String
});

// Add emit definition
const emit = defineEmits(['updated']);

const preview = ref(null);
const error = ref(null);
const success = ref(null);
const loading = ref(false);

function handleFileChange(e) {
  const file = e.target.files[0];
  
  if (!file) return;
  
  // Validate file type
  if (!file.type.match(/image\/.*/)) {
    error.value = 'Please select an image file';
    return;
  }
  
  // Validate file size (2MB max)
  if (file.size > 2 * 1024 * 1024) {
    error.value = 'Image must be less than 2MB';
    return;
  }
  
  // Create preview URL
  preview.value = URL.createObjectURL(file);
  error.value = null;
  
  // Upload immediately
  uploadLogo(file);
}

function uploadLogo(file) {
  loading.value = true;
  error.value = null;
  success.value = null;
  
  const formData = new FormData();
  formData.append('logo', file);
  
  axios.post(route('settings.update.logo'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    success.value = response.data.message || 'Logo uploaded successfully';
    
    // Emit event to parent
    emit('updated');
    
    loading.value = false;
    
    // Emit event for header update with the URL
    eventBus.emit('business-logo-changed', `/storage/${response.data.logo}`);
  })
  .catch(err => {
    console.error('Error uploading logo:', err);
    error.value = err.response?.data?.message || 'An error occurred while uploading the logo';
    loading.value = false;
  });
}

function removeLogo() {
  loading.value = true;
  error.value = null;
  success.value = null;
  
  axios.post(route('settings.update.logo'), {
    remove: true
  })
  .then(response => {
    preview.value = null;
    success.value = response.data.message || 'Logo removed successfully';
    
    // Emit event to parent
    emit('updated');
    
    loading.value = false;
    
    // Emit event for header update
    eventBus.emit('business-logo-changed', null);
  })
  .catch(err => {
    console.error('Error removing logo:', err);
    error.value = err.response?.data?.message || 'An error occurred while removing the logo';
    loading.value = false;
  });
}
</script>