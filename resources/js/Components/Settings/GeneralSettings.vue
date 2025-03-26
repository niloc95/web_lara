<template>
  <form @submit.prevent="submit">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="business_name">
          Business Name
        </label>
        <input
          id="business_name"
          v-model="form.business_name"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
          required
        >
        <div v-if="form.errors.business_name" class="text-red-500 text-xs mt-1">
          {{ form.errors.business_name }}
        </div>
      </div>
      
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="business_email">
          Business Email
        </label>
        <input
          id="business_email"
          v-model="form.business_email"
          type="email"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
        >
        <div v-if="form.errors.business_email" class="text-red-500 text-xs mt-1">
          {{ form.errors.business_email }}
        </div>
      </div>
      
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="business_phone">
          Business Phone
        </label>
        <input
          id="business_phone"
          v-model="form.business_phone"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
        >
        <div v-if="form.errors.business_phone" class="text-red-500 text-xs mt-1">
          {{ form.errors.business_phone }}
        </div>
      </div>

      <!-- Business Mobile (new) -->
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="business_mobile">
          Business Mobile
        </label>
        <input
          id="business_mobile"
          v-model="form.business_mobile"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
        >
        <div v-if="form.errors.business_mobile" class="text-red-500 text-xs mt-1">
          {{ form.errors.business_mobile }}
        </div>
      </div>

      <!-- WhatsApp Business (new) -->
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="business_whatsapp">
          WhatsApp Business
        </label>
        <input
          id="business_whatsapp"
          v-model="form.business_whatsapp"
          type="text"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
        >
        <div v-if="form.errors.business_whatsapp" class="text-red-500 text-xs mt-1">
          {{ form.errors.business_whatsapp }}
        </div>
      </div>
      
      <div class="col-span-2">
        <label class="block font-medium text-sm text-gray-700" for="business_address">
          Business Address
        </label>
        <textarea
          id="business_address"
          v-model="form.business_address"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
          rows="3"
        ></textarea>
        <div v-if="form.errors.business_address" class="text-red-500 text-xs mt-1">
          {{ form.errors.business_address }}
        </div>
      </div>
      
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="appointment_lead_time">
          Appointment Lead Time (hours)
        </label>
        <input
          id="appointment_lead_time"
          v-model="form.appointment_lead_time"
          type="number"
          min="1"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
        >
        <div v-if="form.errors.appointment_lead_time" class="text-red-500 text-xs mt-1">
          {{ form.errors.appointment_lead_time }}
        </div>
      </div>
      
      <div class="col-span-1">
        <label class="block font-medium text-sm text-gray-700" for="appointment_duration">
          Default Appointment Duration (minutes)
        </label>
        <input
          id="appointment_duration"
          v-model="form.appointment_duration"
          type="number"
          min="5"
          step="5"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
        >
        <div v-if="form.errors.appointment_duration" class="text-red-500 text-xs mt-1">
          {{ form.errors.appointment_duration }}
        </div>
      </div>
      
      <div class="col-span-2">
        <label class="flex items-center">
          <input
            type="checkbox"
            v-model="form.notification_email"
            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200"
          >
          <span class="ml-2 text-sm text-gray-700">Receive email notifications for new appointments</span>
        </label>
      </div>
    </div>
    
    <div class="mt-6 flex justify-end">
      <button
        type="submit"
        class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        :disabled="form.processing"
      >
        <span v-if="form.processing">Saving...</span>
        <span v-else>Save General Settings</span>
      </button>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import eventBus from '@/eventBus';

const props = defineProps({
  settings: Object,
});

const form = useForm({
  business_name: props.settings?.business_name || '',
  business_email: props.settings?.business_email || '',
  business_phone: props.settings?.business_phone || '',
  business_mobile: props.settings?.business_mobile || '', // Add this line
  business_whatsapp: props.settings?.business_whatsapp || '', // Add this line
  business_address: props.settings?.business_address || '',
  appointment_lead_time: props.settings?.appointment_lead_time || 24,
  appointment_duration: props.settings?.appointment_duration || 60,
  notification_email: props.settings?.notification_email || false,
});

function submit() {
  form.post(route('settings.update.general'), {
    preserveScroll: true,
    onSuccess: () => {
      eventBus.emit('business-name-changed', form.business_name);
    }
  });
}
</script>