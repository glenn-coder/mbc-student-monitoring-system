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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'mbc-navy': {
                    DEFAULT: '#0D1164',
                    50: '#eaebf4',
                    100: '#d5d7e8',
                    200: '#abadce',
                    300: '#8184b3',
                    400: '#575b98',
                    500: '#2d317d',
                    600: '#0D1164',
                    700: '#0a0d4b',
                    800: '#070932',
                    900: '#030419',
                }
            }
        },
    },

    plugins: [forms],
};
