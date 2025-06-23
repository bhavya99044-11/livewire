/** @type {import('tailwindcss').Config} */

const plugin = require('tailwindcss/plugin');

export default {
  darkMode: 'false',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
],
theme: {
  extend: {
      fontFamily: {
        lato: ['Lato', 'sans-serif'],
        inter: ['Inter', 'sans-serif'],

      },
    colors: {
      adminThemeStart: '#26c6da',
      adminThemeEnd:'#00bcd4', 
      sideBarColor:'oklch(35.9% 0.144 278.697)',
      sideBarBorder:'oklch(39.8% 0.195 277.366)',
      'brand-primary': '#2563eb',
      'brand-secondary': '#4f46e5',
      'brand-accent': '#f97316',
      'brand-light': '#eff6ff',
      'brand-dark': '#1e3a8a',
    },
  },
  container: {
    center: true,
    screens: {
      xl: '1440px',
    },
  },
},
plugins: [
  plugin(function({ addComponents,addBase }) {
    addComponents({
      '.input-style': {
        '@apply mt-1 relative block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm': {},
      },
      '.btn-primary': {
        '@apply w-fit cursor-pointer md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors': {},
      },
      '.btn-secondary': {
        '@apply bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-colors': {},
      },
      '.btn-delete':{
        '@apply text-red-600 hover:text-red-900 p-1 rounded transition-colors': {},
      },
      '.btn-edit':{
        '@apply text-blue-600 hover:text-blue-900 p-1 rounded transition-colors': {},
      },
      '.btn-view':{
        '@apply text-yellow-600 hover:text-yellow-900 p-1 rounded transition-colors': {},
      },
      '.card': {
        
        boxShadow: '0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06)',
        transition: 'box-shadow 0.3s ease',
      },
      '.card':{
        '@apply rounded-lg':{}
      },
      '.card:hover': {
        boxShadow: '0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05)',
      },
    }),
    addBase({
      'form': {
        marginBottom: '0',
      },
    });
  }),
],
}
