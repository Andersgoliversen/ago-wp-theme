// Parallax effect for hero background.
document.addEventListener('DOMContentLoaded', () => {
  const bgWrap = document.getElementById('hero-bg-wrap');
  if (bgWrap && window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
    const onScroll = () => {
      bgWrap.style.transform = `translateY(${window.scrollY * 0.3}px)`;
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }
});
