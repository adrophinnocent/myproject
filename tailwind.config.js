﻿﻿﻿import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                gold: {
                    50: '#fdfbf0',
                    100: '#faf3cc',
                    200: '#f4e48a',
                    300: '#eccf4a',
                    400: '#e3b81a',
                    500: '#D4AF37',
                    600: '#b8920d',
                    700: '#8f6e0a',
                    800: '#704f0f',
                    900: '#5a3f12'
                },
                safari: {
                    dark: '#0a0703',
                    mid: '#1a1209',
                    light: '#8B6914'
                },
                brand: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#D4AF37',
                    600: '#b8920d',
                    700: '#8f6e0a',
                    800: '#704f0f',
                    900: '#0a0a0a'
                }
            },
            fontFamily: {
                display: ['"Cormorant Garamond"', 'serif'],
                body: ['"Inter"', 'sans-serif'],
                sans: ['"Inter"', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'slide-up': 'slideUp 0.6s ease-out forwards',
                'slide-down': 'slideDown 0.3s ease-out forwards',
                'scale-in': 'scaleIn 0.3s ease-out forwards',
                'float': 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                }
            }
        },
    },

    plugins: [forms],
};
