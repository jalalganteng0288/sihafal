/** @type {import('tailwindcss').Config} */
// Catatan: Tailwind CSS v4 menggunakan konfigurasi berbasis CSS di app.css
// File ini disediakan untuk kompatibilitas dengan tools yang membutuhkannya
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            // Kustomisasi tema SiHafal dapat ditambahkan di sini
            colors: {
                primary: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
            },
        },
    },
    plugins: [],
};
