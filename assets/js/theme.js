/**
 * Theme JavaScript
 * – global interactions loaded on every page
 */

/* Parallax effect for the hero background  ------------------ */
document.addEventListener('DOMContentLoaded', () => {
  // Get the hero background wrap element.
  const bgWrap = document.getElementById('hero-bg-wrap');
  // If the element doesn't exist (e.g., not on the front page), exit.
  if (!bgWrap) return;

  // Define the scroll factor for the parallax effect (e.g., 0.15 means 15% of scroll speed).
  const factor = 0.15;
  // Check if the user prefers reduced motion.
  const motionOK = window.matchMedia('(prefers-reduced-motion: no-preference)');

  // Only apply the parallax effect if reduced motion is not preferred.
  if (motionOK.matches) {
    // Define the function to update the background position on scroll.
    const onScroll = () => {
      // Apply a vertical translation to the background wrap based on the scroll position and factor.
      bgWrap.style.transform = `translateY(${window.scrollY * factor}px)`;
    };
    // Call onScroll once initially to set the correct position on page load.
    onScroll();
    // Add a scroll event listener to update the background position dynamically.
    // { passive: true } improves scrolling performance by indicating the listener won't call preventDefault().
    window.addEventListener('scroll', onScroll, { passive: true });
  }
});
