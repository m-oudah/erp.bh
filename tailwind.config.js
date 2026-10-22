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
                // اللون الرئيسي للنظام (Primary): rgb(13 148 136) -> Teal 600
                primary: {
                    DEFAULT: '#0d9488', // rgb(13, 148, 136)
                    light: '#14b8a6',   // teal-500
                    dark: '#0f766e',    // teal-700
                    darker: '#115e59',  // teal-800
                    subtle: '#f0fdfa',  // teal-50
                },
                // اللون الثانوي للأيقونات والعناصر التفاعلية (Secondary): rgb(217 119 6) -> Amber 600
                secondary: {
                    DEFAULT: '#d97706', // rgb(217, 119, 6)
                    light: '#f59e0b',   // amber-500
                    dark: '#b45309',    // amber-700
                    subtle: '#fffbeb',  // amber-50
                },
                // اللون الفرعي الثالث للنصوص الثانوية والحدود (Tertiary): rgb(100 116 139) -> Slate 500
                tertiary: {
                    DEFAULT: '#64748b', // rgb(100, 116, 139)
                    light: '#94a3b8',   // slate-400
                    dark: '#475569',    // slate-600
                    darker: '#334155',  // slate-700
                    subtle: '#f8fafc',  // slate-50
                },
                // توجيه الكلاسات السابقة لتتوافق فوراً وبشكل متكامل مع الهوية الجديدة:
                navy: {
                    DEFAULT: '#0d9488', // يرتبط باللون الرئيسي (Primary)
                    light: '#14b8a6',
                    dark: '#0f766e',
                },
                orange: {
                    DEFAULT: '#d97706', // يرتبط باللون الثانوي للأيقونات (Secondary)
                    light: '#f59e0b',
                    dark: '#b45309',
                }
            }
        },
    },

    plugins: [forms],
};
