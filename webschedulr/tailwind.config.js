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
                primary: {
                    DEFAULT: '#6950f3',
                    50: '#f5f3fe',
                    100: '#ebe7fd',
                    200: '#d6cefb',
                    300: '#b7a8f8',
                    400: '#9682f4',
                    500: '#6950f3',
                    600: '#5a3feb',
                    700: '#4a2dd6',
                    800: '#3e28ae',
                    900: '#35238d',
                },
                secondary: {
                    DEFAULT: '#00a36d',
                    50: '#e6f7f1',
                    100: '#ceefe3',
                    200: '#9ee0c7',
                    300: '#6ed1ab',
                    400: '#3ec28f',
                    500: '#00a36d',
                    600: '#009463',
                    700: '#007a52',
                    800: '#006141',
                    900: '#004e35',
                },
                neutral: {
                    DEFAULT: '#878c93',
                    50: '#f8f8f8',
                    100: '#e7e8e9',
                    200: '#d1d3d5',
                    300: '#b0b4b9',
                    400: '#878c93',
                    500: '#6d7278',
                    600: '#5a5e63',
                    700: '#47494e',
                    800: '#343538',
                    900: '#222',
                }
            }
        }
    },

    plugins: [forms],
};
