<template>
  <AppLayout :title="`Service: ${service.name}`" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Service Details Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                <span 
                  v-if="service.color" 
                  class="inline-block w-4 h-4 rounded-full mr-2"
                  :style="{ backgroundColor: service.color }"
                ></span>
                {{ service.name }}
              </h2>
              
              <div class="flex space-x-2">
                <Link 
                  :href="route('services.edit', service.id)" 
                  class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                >
                  Edit Service
                </Link>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
              <div class="p-4 bg-blue-50 rounded-lg">
                <div class="text-sm text-blue-600 mb-1">Duration</div>
                <div class="text-lg font-semibold">{{ service.duration }} minutes</div>
              </div>
              
              <div class="p-4 bg-green-50 rounded-lg">
                <div class="text-sm text-green-600 mb-1">Price</div>
                <div class="text-lg font-semibold">
                  ${{ service.price ? parseFloat(service.price).toFixed(2) : '0.00' }}
                </div>
              </div>
              
              <div class="p-4 bg-purple-50 rounded-lg">
                <div class="text-sm text-purple-600 mb-1">Total Appointments</div>
                <div class="text-lg font-semibold">{{ stats.total_appointments }}</div>
              </div>
            </div>
            
            <div class="mt-4">
              <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
              <div class="text-gray-700 whitespace-pre-wrap">{{ service.description || 'No description provided.' }}</div>
            </div>
          </div>
        </div>
        
        <!-- Appointments for this service -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Appointments</h3>
            
            <div v-if="appointments.data.length === 0" class="text-center py-6 text-gray-500">
              No appointments found for this service.
            </div>
            
            <div v-else>
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="appointment in appointments.data" :key="appointment.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDateTime(appointment.start_time) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      <Link :href="route('clients.show', appointment.client.id)" class="hover:text-indigo-600 hover:underline">
                        {{ appointment.client.name }}
                      </Link>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                            :class="getStatusClass(appointment.status)">
                        {{ capitalize(appointment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link :href="route('appointments.edit', appointment.id)" class="text-indigo-600 hover:text-indigo-900">
                        Edit
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
              
              <Pagination :links="appointments.links" class="mt-6" />
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
import Pagination from '@/Components/Pagination.vue';
import { format } from 'date-fns';

// Props
const props = defineProps({
  service: Object,
  appointments: Object,
  stats: Object
});

// Methods
function formatDateTime(dateString) {
  return format(new Date(dateString), 'MMM d, yyyy - h:mm a');
}

function getStatusClass(status) {
  switch (status) {
    case 'confirmed': 
      return 'bg-green-100 text-green-800';
    case 'completed': 
      return 'bg-blue-100 text-blue-800';
    case 'cancelled': 
      return 'bg-red-100 text-red-800';
    case 'pending':
    default: 
      return 'bg-yellow-100 text-yellow-800';
  }
}

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>