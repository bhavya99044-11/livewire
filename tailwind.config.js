/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'false',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
],
theme: {
  extend: {
    colors: {
      adminThemeStart: '#26c6da',
      adminThemeEnd:'#00bcd4', 
      sideBarColor:'oklch(35.9% 0.144 278.697)',
      sideBarBorder:'oklch(39.8% 0.195 277.366)'
    },
  },
  container: {
    center: true,
    screens: {
      xl: '1440px',
    },
  },
},
  plugins: [],
}
