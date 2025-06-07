/**
 * Theme JavaScript
 * – global interactions loaded on every page
 */

/* Parallax effect for the hero background  ------------------ */
document.addEventListener('DOMContentLoaded', () => {
  const bgWrap = document.getElementById('hero-bg-wrap');
  if (!bgWrap) return;                      // not on front page

  const factor = 0.15;                      // 15 % scroll rate
  const motionOK = window.matchMedia('(prefers-reduced-motion: no-preference)');

  if (motionOK.matches) {
    const onScroll = () => {
      bgWrap.style.transform = `translateY(${window.scrollY * factor}px)`;
    };
    onScroll();                             // set initial position
    window.addEventListener('scroll', onScroll, { passive: true });
  }
});
