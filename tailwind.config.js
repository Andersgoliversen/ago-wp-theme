/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',                    // root templates
    './**/*.php',                 // template-parts, blocks, etc.
    './assets/js/**/*.js',        // scripts that hold class strings
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
  corePlugins: {
    transform: true,
    transformOrigin: true,
    transitionProperty: true,
    transitionDuration: true,
    scale: true,
  },
  plugins: [],
};