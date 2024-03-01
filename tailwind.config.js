const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
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
        },
    },
    daisyui: {
        themes: [
            {
                mytheme: {
                    "primary": "#38bdf8",
                    "secondary": "#4ade80",
                    "accent": "#fcd34d",
                    "neutral": "#343a47",
                    "base-100": "#ffffff",
                    "base-200": "#f2f5e9",
                    "base-300": "#1e2433",
                    "info": "#fb923c",
                    "success": "#a78bfa",
                    "warning": "#fb7185",
                    "error": "#dc2626",
                },
            },
        ],
    },
    plugins: [
        // require('@tailwindcss/forms'), // DaisyUIのaccordionが動かない問題
        require('@tailwindcss/typography'),
        require('daisyui')
    ],
};
