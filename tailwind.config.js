import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Modules/**/Views/**/*.blade.php', // Ajouter les vues des modules
    ],
    
    safelist: [
        // Classes de couleur pour les sections dynamiques
        {
            pattern: /bg-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(50|100|500|600|700|800|900)/,
        },
        {
            pattern: /text-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(800|900)/,
        },
        {
            pattern: /border-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(200|500)/,
        },
        {
            pattern: /from-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(50|500)/,
        },
        {
            pattern: /to-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(100|600)/,
        },
        {
            pattern: /hover:bg-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(700)/,
        },
        {
            pattern: /hover:text-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(900)/,
        },
        {
            pattern: /hover:border-(blue|green|purple|red|yellow|indigo|pink|emerald|teal|orange)-(200)/,
        },
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#1a4ca1',
                    dark: '#0d3580',
                    light: '#2e6ad9'
                },
                secondary: {
                    DEFAULT: '#2271ff',
                    light: '#4d8eff',
                    dark: '#0056e0'
                },
                accent: {
                    DEFAULT: '#ffffff',
                    blue: '#e0ecff'
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                display: ['Montserrat', 'sans-serif']
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'morph': 'morph 15s linear infinite alternate',
                'morph-reverse': 'morph 15s linear infinite alternate-reverse',
                'morph-slow': 'morph 20s linear infinite alternate',
                'morph-fast': 'morph 10s linear infinite alternate-reverse',
                'fadeUp': 'fadeUp 0.8s forwards',
                'fadeIn': 'fadeIn 1s forwards',
                'fadeRight': 'fadeRight 0.8s forwards',
                'bounce-custom': 'bounce-custom 2s infinite',
                'shimmer': 'shimmer 2s infinite',
                'pulse-ring': 'pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite'
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) rotate(-5deg)' },
                    '50%': { transform: 'translateY(-10px) rotate(-3deg)' }
                },
                morph: {
                    '0%': { borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%' },
                    '50%': { borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%' },
                    '100%': { borderRadius: '40% 60% 30% 70% / 40% 40% 60% 50%' }
                },
                fadeUp: {
                    'from': { opacity: '0', transform: 'translateY(30px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' }
                },
                fadeRight: {
                    'from': { opacity: '0', transform: 'translateX(-30px)' },
                    'to': { opacity: '1', transform: 'translateX(0)' }
                },
                fadeIn: {
                    'from': { opacity: '0' },
                    'to': { opacity: '1' }
                },
                'bounce-custom': {
                    '0%, 20%, 50%, 80%, 100%': { transform: 'translateY(0) translateX(-50%)' },
                    '40%': { transform: 'translateY(-20px) translateX(-50%)' },
                    '60%': { transform: 'translateY(-10px) translateX(-50%)' }
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' }
                },
                'pulse-ring': {
                    '0%': { transform: 'scale(0.33)', opacity: '1' },
                    '80%, 100%': { opacity: '0' }
                }
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'mesh-gradient': 'radial-gradient(circle at 20% 30%, rgba(34, 113, 255, 0.2), transparent 40%), radial-gradient(circle at 80% 70%, rgba(26, 76, 161, 0.2), transparent 40%), radial-gradient(circle at 50% 50%, rgba(77, 142, 255, 0.1), transparent 60%)',
                'wave-pattern': "url(\"data:image/svg+xml,%3Csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='pattern' width='80' height='40' patternUnits='userSpaceOnUse' patternTransform='rotate(0)'%3E%3Cpath d='M0,20 Q20,0 40,20 Q60,40 80,20' stroke='%232271ff' fill='none' stroke-width='1' stroke-opacity='0.05'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='%23ffffff'/%3E%3Crect width='100%25' height='100%25' fill='url(%23pattern)'/%3E%3C/svg%3E\")"
            }
        }
    },

    plugins: [forms, typography],
};
