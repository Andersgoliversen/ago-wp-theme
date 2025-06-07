/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',                    // root templates
    './**/*.php',                 // template-parts, blocks, etc.
    './assets/src/js/**/*.js',    // scripts that hold class strings
    '!./node_modules/**/*',       // explicit exclusions
    '!./vendor/**/*'
  ],
  theme: {
    extend: {
      colors: {
        pagebg: 'oklch(97% 0.001 106.424)',
      },
    },
  },
  plugins: [],
};