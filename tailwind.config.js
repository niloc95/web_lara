import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Enable class-based dark mode
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // GitHub-inspired colorblind-friendly palette
                primary: {
                    DEFAULT: '#0969da', // GitHub blue
                    50: '#f1f8ff',
                    100: '#dbedff',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#0969da',
                    600: '#0550ae',
                    700: '#033d8a',
                    800: '#02326f',
                    900: '#032654',
                },
                secondary: {
                    DEFAULT: '#238636', // GitHub green
                    50: '#f0fff4',
                    100: '#dcffe4',
                    200: '#bef5cb',
                    300: '#85e0a3',
                    400: '#43c663',
                    500: '#238636',
                    600: '#1a7f31',
                    700: '#116329',
                    800: '#0e4e22',
                    900: '#09381c',
                },
                neutral: {
                    DEFAULT: '#6e7781', // GitHub neutral
                    50: '#f6f8fa',
                    100: '#eaeef2',
                    200: '#d0d7de',
                    300: '#afb8c1',
                    400: '#8c959f',
                    500: '#6e7781',
                    600: '#57606a',
                    700: '#424a53',
                    800: '#32383f',
                    900: '#24292f',
                },
                // GitHub semantic colors
                github: {
                    // Base UI colors
                    bg: '#ffffff',
                    'dark-bg': '#0d1117',
                    text: '#24292f',
                    'dark-text': '#e6edf3',
                    heading: '#24292f',
                    'dark-heading': '#f0f6fc',
                    border: '#d0d7de',
                    'dark-border': '#30363d',
                    input: '#f6f8fa',
                    'dark-input': '#0d1117',
                    label: '#24292f',
                    'dark-label': '#c9d1d9',
                    
                    // Accent colors
                    accent: '#0969da',
                    'dark-accent': '#58a6ff',
                    
                    // Status colors
                    success: '#2da44e',
                    'dark-success': '#3fb950',
                    warning: '#bf8700',
                    'dark-warning': '#d29922',
                    error: '#cf222e',
                    'dark-error': '#f85149',
                    info: '#0969da',
                    'dark-info': '#58a6ff',
                    
                    // Special states
                    open: '#238636',
                    closed: '#cf222e',
                    merged: '#8250df',
                    draft: '#6e7781',
                },
                // Storybook-friendly semantic colors
                status: {
                    pending: '#bf8700',
                    confirmed: '#0969da',
                    completed: '#238636',
                    cancelled: '#cf222e',
                }
            }
        }
    },

    plugins: [
        forms,
        function({ addComponents }) {
            addComponents({
                '.stats-card': {
                    '@apply bg-white rounded-lg shadow-md p-5 flex flex-col': {}
                },
                '.stats-value': {
                    '@apply text-2xl font-bold mt-3': {}
                },
                '.stats-icon': {
                    '@apply text-lg opacity-70': {}
                },
                '.dashboard-card': {
                    '@apply bg-white overflow-hidden shadow-xl rounded-lg': {}
                },
                '.dashboard-card-header': {
                    '@apply p-4 border-b border-gray-200 flex justify-between items-center': {}
                },
                '.dashboard-card-title': {
                    '@apply font-semibold text-lg text-gray-800': {}
                },
                '.activity-item': {
                    '@apply flex py-3 border-b border-gray-200': {}
                },
                '.dropdown-menu': {
                    '@apply fixed z-50 bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none w-56': {}
                }
            })
        }
    ]
};
