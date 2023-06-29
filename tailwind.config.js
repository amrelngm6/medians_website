/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/*.{vue,js}",
    "./src/components/*.{vue,js}",
    "./app/views/admin/**/*.{twig}",
  ],
  safelist: [
    {
      pattern: /./,
      variants: ['md', 'lg', 'xl'], // you can add your variants here
    },
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
    require('@tailwindcss/aspect-ratio'),
  ]
}
