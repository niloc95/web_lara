<!-- resources/js/Pages/Clients/Index.vue -->
<template>
  <AppLayout title="Clients" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-6">
          <div class="flex flex-wrap gap-4 justify-between items-center">
            <!-- Search bar -->
            <div class="flex items-center w-full md:w-auto">
              <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input 
                  v-model="search" 
                  @input="debouncedSearch"
                  class="pl-10 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  placeholder="Search clients..." 
                />
              </div>
            </div>
            
            <!-- Status filter -->
            <div class="flex space-x-3 items-center w-full md:w-auto">
              <label class="text-sm text-gray-600">Status:</label>
              <select 
                v-model="status" 
                @change="applyFilters"
                class="mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            
            <!-- View toggle and Add client -->
            <div class="flex space-x-3 w-full md:w-auto justify-between">
              <div class="inline-flex rounded-md shadow-sm" role="group">
                <button 
                  @click="viewMode = 'list'" 
                  :class="viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                  class="py-2 px-4 text-sm font-medium rounded-l-md border border-gray-300 hover:bg-gray-50"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                  </svg>
                </button>
                <button 
                  @click="viewMode = 'grid'" 
                  :class="viewMode === 'grid' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                  class="py-2 px-4 text-sm font-medium rounded-r-md border border-gray-300 hover:bg-gray-50"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                  </svg>
                </button>
              </div>
              
              <Link :href="route('clients.create')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 flex items-center">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Client
              </Link>
            </div>
          </div>
        </div>
        
        <!-- Success Message -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
          {{ $page.props.flash.success }}
        </div>
        
        <!-- List View -->
        <div v-if="viewMode === 'list'" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th @click="sort('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Name
                    <span v-if="sortField === 'name'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Contact
                  </th>
                  <th @click="sort('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Status
                    <span v-if="sortField === 'status'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th @click="sort('appointments_count')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                    Appointments
                    <span v-if="sortField === 'appointments_count'" class="ml-1">
                      {{ sortDirection === 'asc' ? '↑' : '↓' }}
                    </span>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="client in sortedClients" :key="client.id" class="hover:bg-gray-50">
                  <!-- Name Cell -->
                  <td class="px-6 py-4">
                    <Link :href="route('clients.show', client.id)" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                      {{ client.name }}
                    </Link>
                    <div v-if="client.notes" class="text-xs text-gray-500 truncate max-w-xs">{{ client.notes }}</div>
                  </td>
                  
                  <!-- Contact Cell -->
                  <td class="px-6 py-4">
                    <div v-if="client.email" class="text-sm text-gray-900">
                      <a :href="`mailto:${client.email}`" class="hover:text-indigo-600">{{ client.email }}</a>
                    </div>
                    <div v-if="client.phone" class="text-sm text-gray-500">
                      <a :href="`tel:${client.phone}`" class="hover:text-indigo-600">{{ client.phone }}</a>
                    </div>
                  </td>
                  
                  <!-- Status Cell -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="client.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ capitalize(client.status || 'inactive') }}
                    </span>
                  </td>
                  
                  <!-- Appointments Cell -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ client.appointments_count || 0 }}</div>
                    <Link 
                      v-if="client.appointments_count" 
                      :href="route('appointments.index', { search: client.name })"
                      class="text-xs text-indigo-600 hover:text-indigo-900"
                    >
                      View all
                    </Link>
                  </td>
                  
                  <!-- Actions Cell -->
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-3">
                      <Link :href="route('clients.show', client.id)" class="text-indigo-600 hover:text-indigo-900" title="View client details">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </Link>
                      
                      <Link :href="route('clients.edit', client.id)" class="text-indigo-600 hover:text-indigo-900" title="Edit client">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </Link>
                      
                      <Link :href="route('appointments.create', { client_id: client.id })" class="text-green-600 hover:text-green-900" title="Create appointment for this client">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </Link>
                      
                      <button @click="confirmDelete(client)" class="text-red-600 hover:text-red-900" title="Delete client">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="sortedClients.length === 0">
                  <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      <p class="mt-2">No clients found.</p>
                      <p class="text-sm text-gray-400">Try changing your search or filters.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div v-if="clients.links && clients.links.length > 3" class="px-6 py-4 border-t border-gray-200">
            <Pagination :links="clients.links" />
          </div>
        </div>
        
        <!-- Grid View -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="client in sortedClients" :key="client.id" class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
              <span :class="client.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs rounded-full">
                {{ capitalize(client.status || 'inactive') }}
              </span>
              <div class="flex space-x-2">
                <Link :href="route('clients.edit', client.id)" class="text-indigo-600 hover:text-indigo-900">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </Link>
                <button @click="confirmDelete(client)" class="text-red-600 hover:text-red-900">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="p-4">
              <Link :href="route('clients.show', client.id)" class="block mb-2">
                <h3 class="text-lg font-semibold text-gray-900 hover:text-indigo-600">{{ client.name }}</h3>
              </Link>
              
              <div class="mb-3 flex flex-col space-y-1">
                <div v-if="client.email" class="flex items-center text-sm text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <a :href="`mailto:${client.email}`" class="hover:text-indigo-600">{{ client.email }}</a>
                </div>
                <div v-if="client.phone" class="flex items-center text-sm text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <a :href="`tel:${client.phone}`" class="hover:text-indigo-600">{{ client.phone }}</a>
                </div>
              </div>
              
              <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                <div class="text-sm">
                  <span class="text-gray-500">Appointments:</span>
                  <span class="font-semibold ml-1">{{ client.appointments_count || 0 }}</span>
                </div>
                
                <Link :href="route('appointments.create', { client_id: client.id })" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700">
                  <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Appointment
                </Link>
              </div>
            </div>
          </div>
          
          <!-- Empty state for grid view -->
          <div v-if="sortedClients.length === 0" class="col-span-full bg-white p-10 rounded-lg text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No clients found</h3>
            <p class="mt-1 text-sm text-gray-500">Try changing your search or filters.</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <Modal :show="deleteModal" @close="deleteModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">Delete Client</h2>
        <p class="mt-1 text-sm text-gray-600">
          Are you sure you want to delete this client? All associated appointments will also be deleted. This action cannot be undone.
        </p>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="deleteModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
            Cancel
          </button>
          <button @click="deleteClient" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm text-white hover:bg-red-700">
            Delete
          </button>
        </div>
      </div>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  clients: Object,
  filters: Object
});

// State variables
const viewMode = ref('list');
const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');
const deleteModal = ref(false);
const clientToDelete = ref(null);
const sortField = ref('name');
const sortDirection = ref('asc');

// Computed properties
const sortedClients = computed(() => {
  if (!props.clients || !props.clients.data) return [];
  
  return [...props.clients.data].sort((a, b) => {
    let aValue, bValue;
    
    switch (sortField.value) {
      case 'name':
        aValue = a.name || '';
        bValue = b.name || '';
        break;
      case 'status':
        aValue = a.status || 'inactive';
        bValue = b.status || 'inactive';
        break;
      case 'appointments_count':
        aValue = a.appointments_count || 0;
        bValue = b.appointments_count || 0;
        break;
      default:
        aValue = a[sortField.value] || '';
        bValue = b[sortField.value] || '';
    }
    
    if (sortDirection.value === 'asc') {
      return aValue > bValue ? 1 : -1;
    } else {
      return aValue < bValue ? 1 : -1;
    }
  });
});

// Methods
function sort(field) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
}

function capitalize(string) {
  if (!string) return '';
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function confirmDelete(client) {
  clientToDelete.value = client;
  deleteModal.value = true;
}

function deleteClient() {
  if (!clientToDelete.value) return;
  
  router.delete(route('clients.destroy', clientToDelete.value.id), {
    onSuccess: () => {
      deleteModal.value = false;
      clientToDelete.value = null;
    }
  });
}

// Debounced search
let timeout;
function debouncedSearch() {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    applyFilters();
  }, 300);
}

function applyFilters() {
  router.get(
    route('clients.index'), 
    {
      search: search.value,
      status: status.value
    }, 
    {
      preserveState: true,
      replace: true
    }
  );
}
</script>

<style>
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>