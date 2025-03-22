<!-- resources/js/Pages/Appointments/Index.vue -->
<template>
  <AppLayout title="Appointments">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Appointments</h1>
            <Link :href="route('appointments.create')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
              New Appointment
            </Link>
          </div>
          
          <!-- Appointments Listing -->
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="appointment in appointments.data" :key="appointment.id">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ appointment.client?.name || 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-500">
                    {{ appointment.service?.name || 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-500">
                    {{ formatDateTime(appointment.start_time) }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span :class="getStatusClass(appointment.status)" class="px-2 py-1 text-xs rounded-full">
                    {{ appointment.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <Link :href="route('appointments.edit', appointment.id)" class="text-indigo-600 hover:text-indigo-900">
                    Edit
                  </Link>
                </td>
              </tr>
              <tr v-if="appointments.data.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No appointments found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  appointments: Object
});

function formatDateTime(dateTime) {
  if (!dateTime) return '';
  return new Date(dateTime).toLocaleString();
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