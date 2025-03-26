<!-- resources/js/Pages/Settings/Index.vue -->
<template>
  <AppLayout title="Settings" :user="$page.props.auth.user">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Settings
      </h2>
    </template>

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
                
                <!-- Logo and Phone Fields Section -->
                <div class="col-span-1 md:col-span-2 mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Logo on left side -->
                  <div>
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                      Business Logo
                    </label>
                    <LogoManager 
                      :current-logo="settings.logo" 
                      @updated="handleLogoUpdated"
                    />
                  </div>
                  
                  <!-- Phone fields on right side -->
                  <div class="space-y-4">
                    <!-- Business Phone -->
                    <div>
                      <label class="block font-medium text-sm text-gray-700" for="business_phone">
                        Business Phone
                      </label>
                      <input v-model="form.business_phone" type="tel" id="business_phone" 
                             class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                      <div v-if="form.errors.business_phone" class="text-red-500 text-sm mt-1">
                        {{ form.errors.business_phone }}
                      </div>
                    </div>
                    
                    <!-- Business Mobile (new) -->
                    <div>
                      <label class="block font-medium text-sm text-gray-700" for="business_mobile">
                        Business Mobile
                      </label>
                      <input v-model="form.business_mobile" type="tel" id="business_mobile" 
                             class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                      <div v-if="form.errors.business_mobile" class="text-red-500 text-sm mt-1">
                        {{ form.errors.business_mobile }}
                      </div>
                    </div>
                    
                    <!-- WhatsApp Business (new) -->
                    <div>
                      <label class="block font-medium text-sm text-gray-700" for="business_whatsapp">
                        WhatsApp Business
                      </label>
                      <input v-model="form.business_whatsapp" type="tel" id="business_whatsapp" 
                             class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                      <div v-if="form.errors.business_whatsapp" class="text-red-500 text-sm mt-1">
                        {{ form.errors.business_whatsapp }}
                      </div>
                    </div>
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

            <!-- Submit button for the general form -->
            <div class="flex justify-end mt-4">
              <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                Save Settings
              </button>
            </div>
          </form>
          
          <!-- INCLUDE BUSINESS HOURS ONLY ONCE, AFTER THE FORM -->
          <div class="mt-8 pt-8 border-t border-gray-200">
            <BusinessHoursManager 
              :initial-hours="settings.business_hours" 
              @updated="handleBusinessHoursUpdated"
            />
          </div>
          
          <!-- NO OTHER INSTANCES OF BusinessHoursManager SHOULD APPEAR IN THE TEMPLATE -->
        </div>
      </div>
    </div>
    
    <!-- Add this at the end of your AppLayout -->
    <Toast 
      :message="toastMessage" 
      :type="toastType" 
      :show="showToast" 
      @close="showToast = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import eventBus from '@/eventBus';
import LogoManager from '@/Components/Settings/LogoManager.vue';
import axios from 'axios';
import BusinessHoursManager from '@/Components/Settings/BusinessHoursManager.vue'; // Import only once
import Toast from '@/Components/UI/Toast.vue'; // Import the Toast component

const props = defineProps({
  settings: Object,
});

// Add at the beginning of your script section
console.log('Settings props received:', props.settings);
console.log('Business hours received:', props.settings?.business_hours);

// Initialize form with logo field
const form = useForm({
  business_name: props.settings?.business_name || '',
  business_email: props.settings?.business_email || '',
  business_phone: props.settings?.business_phone || '',
  business_mobile: props.settings?.business_mobile || '', // New field
  business_whatsapp: props.settings?.business_whatsapp || '', // New field
  business_address: props.settings?.business_address || '',
  business_hours: Array.isArray(props.settings?.business_hours) && props.settings.business_hours.length > 0
    ? props.settings.business_hours
    : initDefaultBusinessHours(),
  appointment_lead_time: props.settings?.appointment_lead_time || 24,
  appointment_duration: props.settings?.appointment_duration || 60,
  notification_email: props.settings?.notification_email || false,
  logo: props.settings?.logo || null,
  _method: 'POST', // Set method for file upload
});

// Add helper function
function initDefaultBusinessHours() {
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  return days.map((day, index) => {
    const isWeekday = index > 0 && index < 6;
    return {
      dayName: day,
      isOpen: isWeekday,
      ranges: isWeekday ? [{ startTime: 9 * 60, endTime: 17 * 60 }] : []
    };
  });
}

// Add these for toast notifications
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

// Inside your submit function
function submit() {
  // Debug what we're about to submit
  console.log('Submitting business hours:', JSON.stringify(form.business_hours));
  
  // Create a new FormData object
  const formData = new FormData();
  
  // Add all form fields to FormData
  formData.append('business_name', form.business_name);
  formData.append('business_email', form.business_email);
  formData.append('business_phone', form.business_phone);
  formData.append('business_mobile', form.business_mobile); // New field
  formData.append('business_whatsapp', form.business_whatsapp); // New field
  formData.append('business_address', form.business_address);
  
  formData.append('appointment_lead_time', form.appointment_lead_time);
  formData.append('appointment_duration', form.appointment_duration);
  formData.append('notification_email', form.notification_email ? 1 : 0);
  
  // Add logo if it's a File object
  if (form.logo instanceof File) {
    formData.append('logo', form.logo);
  }
  
  // Add remove logo flag if needed
  if (form._removelogo) {
    formData.append('_removelogo', '1');
  }
  
  // Submit the form
  axios.post(route('settings.update'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
    // Show success toast
    toastMessage.value = 'Settings updated successfully';
    toastType.value = 'success';
    showToast.value = true;
    
    // Emit events for header updates
    eventBus.emit('business-name-changed', form.business_name);
    
    // Reset form processing state
    form.processing = false;
    form.wasSuccessful = true;
    
    // Use a less disruptive approach than full page reload
    // This keeps your component state but refreshes the Inertia props
    router.reload({ only: ['settings'] });
  })
  .catch(error => {
    // Show error toast
    toastMessage.value = error.response?.data?.message || 'Error updating settings';
    toastType.value = 'error';
    showToast.value = true;
    
    console.error('Error saving settings:', error);
    form.processing = false;
  });
}

// Add this event handler to listen to events from child components
function handleBusinessHoursUpdated() {
  toastMessage.value = 'Business hours updated successfully';
  toastType.value = 'success';
  showToast.value = true;
}

function handleLogoUpdated() {
  toastMessage.value = 'Logo updated successfully';
  toastType.value = 'success';
  showToast.value = true;
}

// At the bottom of your script setup

</script>