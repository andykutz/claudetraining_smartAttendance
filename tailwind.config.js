import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#f2f6fb',
                    100: '#e2ebf5',
                    200: '#c5d7ec',
                    300: '#9db8dd',
                    400: '#6f94c9',
                    500: '#4f76b3',
                    600: '#3d5d97',
                    700: '#334b7a',
                    800: '#2c3f64',
                    900: '#1e2b47',
                    950: '#141d30',
                },
                olive: {
                    50: '#f7f8ef',
                    100: '#eef0d8',
                    200: '#dde2b4',
                    300: '#c6cf88',
                    400: '#aeba62',
                    500: '#96a344',
                    600: '#768135',
                    700: '#5b632c',
                    800: '#4a5027',
                    900: '#3e4324',
                    950: '#202410',
                },
            },
        },
    },

    plugins: [forms],
};
