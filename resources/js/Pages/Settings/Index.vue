<!-- resources/js/Pages/Settings/Index.vue -->
<template>
  <AppLayout title="Settings" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h1 class="text-2xl font-semibold mb-6">Business Settings</h1>
          
          <form @submit.prevent="submit">
            <!-- Business Information -->
            <div class="mb-8">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Business Information</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Business Name -->
                <div>
                  <label class="block font-medium text-sm text-gray-700" for="business_name">
                    Business Name
                  </label>
                  <input v-model="form.business_name" type="text" id="business_name" 
                         class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                  <div v-if="form.errors.business_name" class="text-red-500 text-sm mt-1">
                    {{ form.errors.business_name }}
                  </div>
                </div>
                
                <!-- Business Logo -->
                <div>
                  <label class="block font-medium text-sm text-gray-700">
                    Business Logo
                  </label>
                  <div class="mt-1 flex items-center">
                    <!-- Logo preview -->
                    <div v-if="logoPreview || form.logo" class="mr-4 h-20 w-20 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                      <img :src="logoPreview || `/storage/${form.logo}`" alt="Business logo preview" class="h-full w-full object-contain">
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
                          @change="handleLogoUpload"
                        >
                      </label>
                      <button 
                        v-if="logoPreview || form.logo"
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
                  <div v-if="form.errors.logo" class="text-red-500 text-sm mt-1">
                    {{ form.errors.logo }}
                  </div>
                </div>
                
                <!-- Business Email -->
                <div>
                  <label class="block font-medium text-sm text-gray-700" for="business_email">
                    Business Email
                  </label>
                  <input v-model="form.business_email" type="email" id="business_email" 
                         class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                  <div v-if="form.errors.business_email" class="text-red-500 text-sm mt-1">
                    {{ form.errors.business_email }}
                  </div>
                </div>
                
                <!-- Business Phone -->
                <div>
                  <label class="block font-medium text-sm text-gray-700" for="business_phone">
                    Business Phone
                  </label>
                  <input v-model="form.business_phone" type="text" id="business_phone" 
                         class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                  <div v-if="form.errors.business_phone" class="text-red-500 text-sm mt-1">
                    {{ form.errors.business_phone }}
                  </div>
                </div>
              </div>
              
              <!-- Business Address -->
              <div class="mt-6">
                <label class="block font-medium text-sm text-gray-700" for="business_address">
                  Business Address
                </label>
                <textarea v-model="form.business_address" id="business_address" rows="3"
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                <div v-if="form.errors.business_address" class="text-red-500 text-sm mt-1">
                  {{ form.errors.business_address }}
                </div>
              </div>
              
              <!-- Business Hours -->
              <div class="mt-6">
                <label class="block font-medium text-sm text-gray-700" for="business_hours">
                  Business Hours
                </label>
                <textarea v-model="form.business_hours" id="business_hours" rows="3"
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                          placeholder="Mon-Fri: 9am-5pm, Sat: 10am-2pm, Sun: Closed"></textarea>
                <div v-if="form.errors.business_hours" class="text-red-500 text-sm mt-1">
                  {{ form.errors.business_hours }}
                </div>
              </div>
            </div>
            
            <!-- Appointment Settings -->
            <div class="mb-8 border-t border-gray-200 pt-8">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Appointment Settings</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Default Appointment Duration -->
                <div>
                  <label class="block font-medium text-sm text-gray-700" for="appointment_duration">
                    Default Appointment Duration (minutes)
                  </label>
                  <input v-model="form.appointment_duration" type="number" id="appointment_duration" 
                         class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="5">
                  <div v-if="form.errors.appointment_duration" class="text-red-500 text-sm mt-1">
                    {{ form.errors.appointment_duration }}
                  </div>
                </div>
                
                <!-- Appointment Lead Time -->
                <div>
                  <label class="block font-medium text-sm text-gray-700" for="appointment_lead_time">
                    Appointment Lead Time (hours)
                  </label>
                  <input v-model="form.appointment_lead_time" type="number" id="appointment_lead_time" 
                         class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" min="0">
                  <div v-if="form.errors.appointment_lead_time" class="text-red-500 text-sm mt-1">
                    {{ form.errors.appointment_lead_time }}
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Notification Settings -->
            <div class="mb-8 border-t border-gray-200 pt-8">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Notification Settings</h2>
              <div>
                <label class="flex items-center">
                  <input v-model="form.notification_email" type="checkbox" 
                         class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                  <span class="ml-2 text-sm text-gray-600">Send email notifications for new and updated appointments</span>
                </label>
              </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-center justify-end mt-8">
              <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue transition ease-in-out duration-150" :disabled="form.processing">
                <span v-if="form.processing">Saving...</span>
                <span v-else>Save Settings</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import eventBus from '@/eventBus';

const props = defineProps({
  settings: Object,
});

// Add a ref for logo preview
const logoPreview = ref(null);

// Initialize form with logo field
const form = useForm({
  business_name: props.settings?.business_name || '',
  business_email: props.settings?.business_email || '',
  business_phone: props.settings?.business_phone || '',
  business_address: props.settings?.business_address || '',
  business_hours: props.settings?.business_hours || '',
  appointment_lead_time: props.settings?.appointment_lead_time || 24,
  appointment_duration: props.settings?.appointment_duration || 60,
  notification_email: props.settings?.notification_email || false,
  logo: props.settings?.logo || null,
  _method: 'POST', // Set method for file upload
});

// Handle logo file selection
function handleLogoUpload(e) {
  const file = e.target.files[0];
  
  if (!file) return;
  
  // Validate file type
  if (!file.type.match(/image\/.*/)) {
    form.errors.logo = 'Please select an image file';
    return;
  }
  
  // Validate file size (2MB max)
  if (file.size > 2 * 1024 * 1024) {
    form.errors.logo = 'Image must be less than 2MB';
    return;
  }
  
  // Create preview URL
  logoPreview.value = URL.createObjectURL(file);
  
  // Set file in form data
  form.logo = file;
}

// Remove logo
function removeLogo() {
  logoPreview.value = null;
  form.logo = null;
  form._removelogo = true; // Flag for backend to remove logo
}

function submit() {
  // Use form data for file upload
  const formData = new FormData();
  
  // Add all form fields
  Object.keys(form).forEach(key => {
    // Skip internal Inertia properties
    if (key.startsWith('$') || key === 'errors' || key === 'progress' || key === 'processing' || key === 'wasSuccessful') {
      return;
    }
    
    // Add the field to form data
    if (form[key] !== null) {
      formData.append(key, form[key]);
    }
  });
  
  // Post using FormData
  form.post(route('settings.update'), {
    data: formData,
    onSuccess: () => {
      // Emit event with the new business name
      eventBus.emit('business-name-changed', form.business_name);
      
      // If logo changed, emit another event
      if (logoPreview.value || form._removelogo) {
        eventBus.emit('business-logo-changed', form.logo ? logoPreview.value : null);
      }
    }
  });
}
</script>