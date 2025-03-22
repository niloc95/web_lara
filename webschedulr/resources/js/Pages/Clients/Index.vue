<!-- resources/js/Pages/Clients/Index.vue -->
<template>
  <AppLayout title="Clients">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-semibold mb-4 md:mb-0">Clients</h1>
            
            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
              <div class="relative">
                <input
                  v-model="search"
                  @input="debouncedSearch"
                  type="text"
                  placeholder="Search clients..."
                  class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full md:w-64"
                />
              </div>
              
              <Link :href="route('clients.create')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 text-center">
                Add Client
              </Link>
            </div>
          </div>
          
          <!-- Success Message -->
          <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
            {{ $page.props.flash.success }}
          </div>
          
          <!-- Basic Client Listing -->
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="client in clients.data" :key="client.id">
                <td class="px-6 py-4">{{ client.name }}</td>
                <td class="px-6 py-4">
                  <div v-if="client.email" class="text-sm text-gray-900">{{ client.email }}</div>
                  <div v-if="client.phone" class="text-sm text-gray-500">{{ client.phone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-3">
                    <Link :href="route('clients.show', client.id) + '/' + encodeURIComponent(client.name.toLowerCase().replace(/\s+/g, '-'))" class="text-indigo-600 hover:text-indigo-900">
                      View
                    </Link>
                    <Link :href="route('clients.edit', client.id)" class="text-indigo-600 hover:text-indigo-900">
                      Edit
                    </Link>
                  </div>
                </td>
              </tr>
              <tr v-if="clients.data.length === 0">
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No clients found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  clients: Object,
  filters: Object
});

const search = ref(props.filters?.search || '');

let timeout;
function debouncedSearch() {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    router.get(route('clients.index'), { search: search.value }, {
      preserveState: true,
      replace: true
    });
  }, 300);
}
</script>