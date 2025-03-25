import './bootstrap';
import '../css/app.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Set dark mode based on stored preference or system preference
function initDarkMode() {
  // Check if user previously selected a color scheme
  const userTheme = localStorage.getItem('color-theme');
  
  // Check user preference or OS preference
  if (
    userTheme === 'dark' || 
    (!userTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)
  ) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
}

// Run immediately
initDarkMode();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
```
<!-- filepath: /home/nilo/projects/web_lara/webschedulr/resources/js/Layouts/AppLayout.vue -->
<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

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

                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <Link :href="route('dashboard')" :class="{'text-gray-900': route().current('dashboard'), 'text-gray-500': !route().current('dashboard')}">
                                    Dashboard
                                </Link>
                                <Link :href="route('settings.index')" :class="{'text-gray-900': route().current('settings.index'), 'text-gray-500': !route().current('settings.index')}">
                                    Settings
                                </Link>
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
```