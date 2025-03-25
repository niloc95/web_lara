<template>
  <AppLayout :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Services</h1>
            <Link :href="route('services.create')" 
                  class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition">
              Add Service
            </Link>
          </div>
          
          <!-- Flash Messages -->
          <div v-if="$page.props.flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ $page.props.flash.success }}
          </div>
          
          <!-- Services Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
              <thead class="bg-gray-100">
                <tr>
                  <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Name</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Duration</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Price</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Color</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Status</th>
                  <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="service in services" :key="service.id" class="hover:bg-gray-50">
                  <td class="py-3 px-4 text-sm">{{ service.name }}</td>
                  <td class="py-3 px-4 text-sm">{{ service.duration }} min</td>
                  <td class="py-3 px-4 text-sm">{{ service.price ? '$' + service.price : 'Free' }}</td>
                  <td class="py-3 px-4 text-sm">
                    <div class="flex items-center">
                      <div class="w-6 h-6 rounded mr-2" :style="{ backgroundColor: service.color }"></div>
                      {{ service.color }}
                    </div>
                  </td>
                  <td class="py-3 px-4 text-sm">
                    <span :class="service.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                          class="px-2 py-1 rounded-full text-xs">
                      {{ service.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-sm">
                    <div class="flex space-x-2">
                      <Link :href="route('services.edit', service.id)" 
                            class="text-blue-600 hover:text-blue-900">
                        Edit
                      </Link>
                      <button @click="confirmDelete(service)" 
                              class="text-red-600 hover:text-red-900">
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="services.length === 0">
                  <td colspan="6" class="py-6 text-center text-gray-500">
                    No services found. Click "Add Service" to create one.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-6">Are you sure you want to delete the service "{{ serviceToDelete?.name }}"? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button @click="showDeleteModal = false" 
                  class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
            Cancel
          </button>
          <button @click="deleteService" 
                  class="px-4 py-2 bg-red-500 text-white rounded-md">
            Delete
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();

const props = defineProps({
  services: Array,
});

const showDeleteModal = ref(false);
const serviceToDelete = ref(null);

function confirmDelete(service) {
  serviceToDelete.value = service;
  showDeleteModal.value = true;
}

function deleteService() {
  router.delete(route('services.destroy', serviceToDelete.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false;
      serviceToDelete.value = null;
    }
  });
}
</script>