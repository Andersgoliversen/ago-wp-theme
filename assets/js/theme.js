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
    let lastKnownScrollPosition = 0;
    let ticking = false;

    // Define the function to update the background position on scroll.
    const onScroll = () => {
      lastKnownScrollPosition = window.scrollY;
      if (!ticking) {
        window.requestAnimationFrame(() => {
          bgWrap.style.transform = `translateY(${lastKnownScrollPosition * factor}px)`;
          ticking = false;
        });
        ticking = true;
      }
    };
    // Call onScroll once initially to set the correct position on page load.
    onScroll();
    // Add a scroll event listener to update the background position dynamically.
    // { passive: true } improves scrolling performance by indicating the listener won't call preventDefault().
    window.addEventListener('scroll', onScroll, { passive: true });
  }
});

// Start Diurnalis animations after full page load
window.addEventListener('load', () => {
  document.body.classList.add('diurnalis-ready');
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

// ---------------------------------------------------------------------------
// Search form enhancements
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('search-field');
  const button = document.getElementById('search-submit');
  const warning = document.getElementById('search-warning');

  if (input) {
    const text = input.getAttribute('placeholder') || '';

    function animatePlaceholder() {
      input.placeholder = '';
      let i = 0;
      const interval = setInterval(() => {
        input.placeholder = text.slice(0, i + 1);
        i += 1;
        if (i >= text.length) clearInterval(interval);
      }, 80);
    }

    animatePlaceholder();
    setInterval(animatePlaceholder, 30000);
  }

  if (button) {
    button.addEventListener('click', (e) => {
      if (input && input.value.trim() === '') {
        e.preventDefault();
        if (warning) warning.classList.remove('hidden');
      }
    });
  }

  const hideWarning = () => warning && warning.classList.add('hidden');
  document.addEventListener('click', hideWarning);
  document.addEventListener('scroll', hideWarning, { passive: true });

  document.querySelectorAll('.ag-interactive').forEach(el => {
    const icon = el.querySelector('.ag-icon');
    if (!icon) return;

    let hold = false;
    let timer;
    let duration = 1.5;

    const stop = () => {
      hold = false;
      clearTimeout(timer);
      icon.classList.remove('ag-spin', 'ag-spin-once');
      icon.style.removeProperty('--ag-spin-duration');
    };

    const startSpin = () => {
      if (!hold) return;
      icon.classList.remove('ag-spin-once');
      icon.classList.add('ag-spin');
      icon.style.setProperty('--ag-spin-duration', duration + 's');
      const accelerate = () => {
        if (!hold) return;
        duration = Math.max(0.2, duration - 0.3);
        icon.style.setProperty('--ag-spin-duration', duration + 's');
        if (duration > 0.2) timer = setTimeout(accelerate, 200);
      };
      accelerate();
    };

    const handlePress = () => {
      hold = true;
      duration = 1.5;
      icon.classList.add('ag-spin-once');
      timer = setTimeout(startSpin, 1400); // 0.4s spin + 1s pause
    };

    el.addEventListener('mousedown', handlePress);
    el.addEventListener('touchstart', handlePress, { passive: true });
    el.addEventListener('mouseup', stop);
    el.addEventListener('mouseleave', stop);
    el.addEventListener('touchend', stop);
  });
});

// ---------------------------------------------------------------------------
// Rock Art Research card image sequence
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const card = document.getElementById('rock-art-card');
  if (!card) return; // exit if the card is not on the page

  const imgs = Array.from(card.querySelectorAll('.rock-art-img'));
  let current = 0;      // index of the image currently shown
  let altToggle = 0;    // toggles between the two alternative drawings

  const wiggleDuration = 200;  // in milliseconds
  const bounceDuration = 600;  // in milliseconds
  const delay = 5000;          // wait time before each transition

  // Show the first image (photograph) initially
  imgs[current].classList.add('active');

  // Determine the next image index in the 0 → 1 → 0 → 2 loop
  function getNextIndex() {
    if (current === 0) {
      return altToggle === 0 ? 1 : 2;
    }
    altToggle = altToggle === 0 ? 1 : 0; // swap between the two drawings
    return 0;
  }

  // Switch the visible image with a cross-fade
  function switchImage() {
    const next = getNextIndex();
    imgs[current].classList.remove('active');
    imgs[next].classList.add('active');
    current = next;
  }

  // Handles the wiggle, bounce and image swap
  function animate() {
    card.classList.add('rock-wiggle');
    setTimeout(() => {
      card.classList.remove('rock-wiggle');
      card.classList.add('rock-bounce');
      switchImage();
      setTimeout(() => {
        card.classList.remove('rock-bounce');
      }, bounceDuration);
    }, wiggleDuration);
  }

  // Initial delay before the first animation, then repeat
  setTimeout(() => {
    animate();
    setInterval(animate, delay + wiggleDuration + bounceDuration);
  }, delay);
});
