/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/components/tailwind/**/*.blade.php",
        "./resources/**/*.blade.php",
    ],
    theme: {
        extend: {},
        colors: {
            navblue: '#3c8dbc',
            white: '#ffffff',
            gray: {
                50: '#f9f9f9',
                200: '#d1d5db'
            },
            buttonblue: '#307095',
            deletered: '#dd4b39',
            green: '#00a65a',
        }
    },
    plugins: [],
}

