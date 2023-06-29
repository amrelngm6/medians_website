/** @type {import('tailwindcss').Config} */
module.exports = {
    purge: [
      "../../app/views/front/*.html.twig",
      "../../app/views/front/includes/*.html.twig",
    ],
    future: {},
    theme: {
    extend: {},
    },
    variants: {},
    plugins: [
      require('tailwindcss'),
      require('autoprefixer'),
    ]
}
