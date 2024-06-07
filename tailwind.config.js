/** @type {import('tailwindcss').Config} */
import preset from './vendor/filament/support/tailwind.config.preset'

module.exports = {
    presets: [preset],
    content: [
        "./resources/views/components/tailwind/**/*.blade.php",
        "./resources/**/*.blade.php",
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {},
        colors: {
            transparent: 'transparent',
            navblue: '#3c8dbc',
            white: '#ffffff',
            gray: {
                50: '#f9f9f9',
                100: '#d1d5db'
            },
            buttonblue: '#307095',
            deletered: '#dd4b39',
            green: '#00a65a',
            black: '#000000'
        }
    },
    plugins: [],
}

