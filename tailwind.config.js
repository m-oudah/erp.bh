import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    DEFAULT: '#1e3a8a', // blue-900 (Navy)
                    light: '#1d4ed8',
                    dark: '#1e3a8a',
                },
                orange: {
                    DEFAULT: '#f97316', // orange-500
                    light: '#fb923c',
                    dark: '#ea580c',
                }
            }
        },
    },

    plugins: [forms],
};
