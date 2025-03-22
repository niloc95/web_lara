<!-- resources/js/Pages/Clients/Show.vue -->
<template>
  <AppLayout :title="client ? `Client: ${client.name}` : 'Client Details'">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <!-- Header with actions -->
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-semibold mb-4 md:mb-0">{{ client.name }}</h1>
            <div class="flex space-x-3">
              <Link :href="route('clients.edit', client.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Edit Client
              </Link>
              <Link :href="route('clients.index')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md">
                Back to Clients
              </Link>
            </div>
          </div>
          
          <!-- Client Information -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Client Information</h2>
              <dl class="divide-y divide-gray-200">
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Name</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ client.name }}</dd>
                </div>
                <div v-if="client.email" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Email</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a :href="`mailto:${client.email}`" class="text-blue-600 hover:underline">{{ client.email }}</a>
                  </dd>
                </div>
                <div v-if="client.phone" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Phone</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <a :href="`tel:${client.phone}`" class="text-blue-600 hover:underline">{{ client.phone }}</a>
                  </dd>
                </div>
                <div v-if="client.birth_date" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(client.birth_date) }}</dd>
                </div>
                <div v-if="client.address" class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Address</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">{{ client.address }}</dd>
                </div>
              </dl>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6">
              <h2 class="text-lg font-medium text-gray-900 mb-4">Notes</h2>
              <div v-if="client.notes" class="prose prose-sm max-w-none">
                <p class="whitespace-pre-line">{{ client.notes }}</p>
              </div>
              <div v-else class="text-gray-500 italic">
                No notes available for this client.
              </div>
            </div>
          </div>
          
          <!-- Appointment History -->
          <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-medium text-gray-900">Appointment History</h2>
              <Link :href="route('appointments.create', { client_id: client.id })" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New Appointment
              </Link>
            </div>
            
            <div v-if="appointments && appointments.length > 0">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="appointment in appointments" :key="appointment.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDateTime(appointment.start_time) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ appointment.service?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(appointment.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ appointment.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link :href="route('appointments.edit', appointment.id)" class="text-indigo-600 hover:text-indigo-900">
                        View/Edit
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="bg-gray-50 rounded-lg p-8 text-center">
              <p class="text-gray-500">No appointments yet.</p>
              <p class="mt-2">
                <Link :href="route('appointments.create', { client_id: client.id })" class="text-blue-600 hover:underline">
                  Schedule the first appointment
                </Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  client: Object,
  appointments: Array
});

function formatDate(date) {
  if (!date) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString(undefined, options);
}

function formatDateTime(dateTime) {
  if (!dateTime) return '';
  const options = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(dateTime).toLocaleString(undefined, options);
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': return 'bg-green-100 text-green-800';
    case 'completed': return 'bg-blue-100 text-blue-800';
    case 'cancelled': return 'bg-red-100 text-red-800';
    case 'pending':
    default: return 'bg-yellow-100 text-yellow-800';
  }
}
</script>