<template>
  <AppLayout title="Create Service" :user="$page.props.auth.user">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Create New Service</h1>
            <Link :href="route('services.index')" 
                  class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition">
              Back to Services
            </Link>
          </div>
          
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Service Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Service Name</label>
                <input type="text" id="name" v-model="form.name" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
              </div>
              
              <!-- Duration -->
              <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                <input type="number" id="duration" v-model="form.duration" min="5" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div v-if="form.errors.duration" class="text-red-500 text-xs mt-1">{{ form.errors.duration }}</div>
              </div>
              
              <!-- Price -->
              <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                  </div>
                  <input type="number" id="price" v-model="form.price" step="0.01" min="0" 
                         class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</div>
              </div>
              
              <!-- Color -->
              <div>
                <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                <div class="mt-1 flex items-center">
                  <input type="color" id="color" v-model="form.color" 
                         class="h-10 w-10 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <span class="ml-2 text-gray-700">{{ form.color }}</span>
                </div>
                <div v-if="form.errors.color" class="text-red-500 text-xs mt-1">{{ form.errors.color }}</div>
              </div>
              
              <!-- Active Status -->
              <div class="col-span-1 md:col-span-2">
                <div class="flex items-center">
                  <input type="checkbox" id="is_active" v-model="form.is_active" 
                         class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                  <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">Active</label>
                </div>
                <div v-if="form.errors.is_active" class="text-red-500 text-xs mt-1">{{ form.errors.is_active }}</div>
              </div>
              
              <!-- Description -->
              <div class="col-span-1 md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" v-model="form.description" rows="4" 
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end">
              <button type="submit" 
                      class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition"
                      :disabled="form.processing">
                {{ form.processing ? 'Creating...' : 'Create Service' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
  name: '',
  duration: 60,
  price: null,
  color: '#4f46e5',
  description: '',
  is_active: true,
});

function submit() {
  form.post(route('services.store'));
}
</script>