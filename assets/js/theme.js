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

// ---------------------------------------------------------------------------
// Blog post slider on the front page
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const slider = document.getElementById('recent-posts-slider');
  const prevBtn = document.getElementById('recent-posts-prev');
  const nextBtn = document.getElementById('recent-posts-next');

  if (!slider || !prevBtn || !nextBtn) return;

  let page = 1;
  const perPage = 3;
  let loading = false;

  async function loadMore() {
    if (loading) return;
    loading = true;
    try {
      const res = await fetch(`/wp-json/wp/v2/posts?_embed&per_page=${perPage}&page=${page + 1}`);
      if (!res.ok) return;
      const posts = await res.json();
      if (!Array.isArray(posts) || posts.length === 0) return;
      page += 1;
      for (const post of posts) {
        const article = document.createElement('article');
        article.className = 'flex-none w-80 snap-center flex flex-col items-center text-center';
        const media = post._embedded && post._embedded['wp:featuredmedia'] ? post._embedded['wp:featuredmedia'][0].source_url : '';
        article.innerHTML = `
          <a href="${post.link}" class="block">
            ${media ? `<img src="${media}" alt="" class="w-full h-40 object-cover rounded shadow">` : ''}
            <h3 class="mt-4 text-lg font-semibold">${post.title.rendered}</h3>
            <time datetime="${post.date}" class="text-sm text-neutral-500">${new Date(post.date).toLocaleDateString()}</time>
          </a>`;
        slider.appendChild(article);
      }
    } catch (e) {
      console.error(e);
    } finally {
      loading = false;
    }
  }

  nextBtn.addEventListener('click', () => {
    slider.scrollBy({ left: slider.clientWidth, behavior: 'smooth' });
    if (slider.scrollWidth - slider.scrollLeft - slider.clientWidth < 50) {
      loadMore();
    }
  });

  prevBtn.addEventListener('click', () => {
    slider.scrollBy({ left: -slider.clientWidth, behavior: 'smooth' });
  });

  let startX = 0;
  slider.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
  }, { passive: true });

  slider.addEventListener('touchend', e => {
    const diff = e.changedTouches[0].clientX - startX;
    if (diff > 50) prevBtn.click();
    if (diff < -50) nextBtn.click();
  });
});
