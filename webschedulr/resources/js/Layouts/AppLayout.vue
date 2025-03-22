<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue'; // Add this import

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <h1 class="font-bold text-xl">WebSchedulr</h1>
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                
                                <NavLink :href="route('clients.index')" :active="route().current('clients.*')">
                                    Clients
                                </NavLink>
                                
                                <NavLink :href="route('appointments.index')" :active="route().current('appointments.*')">
                                    Appointments
                                </NavLink>
                                
                                <NavLink :href="route('settings.index')" :active="route().current('settings.*')">
                                    Settings
                                </NavLink>

                                <NavLink :href="route('calendar')" :active="route().current('calendar*')">
                                    Calendar
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <button @click="logout" class="text-gray-500 hover:text-gray-700">
                                Log Out
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="py-10">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <slot></slot>
                </div>
            </main>
        </div>
    </div>
</template>