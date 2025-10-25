
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        'primary': {
          '700': '#B91C1C',
          '800': '#991B1B',
          '900': '#7F1D1D',
        },
        'status': {
          'success': '#059669',
          'error': '#DC2626',
          'edit': '#2563EB',
        }
      }
    },
  },
  plugins: [],
}
