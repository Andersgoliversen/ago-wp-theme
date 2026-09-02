/**
 * Theme JavaScript
 * – global interactions loaded on every page
 */

const runWhenIdle = (callback) => {
  if ('requestIdleCallback' in window) {
    window.requestIdleCallback(callback, { timeout: 2000 });
  } else {
    setTimeout(callback, 1);
  }
};

// Start Diurnalis animations after full page load
window.addEventListener('load', () => {
  document.body.classList.add('diurnalis-ready');
});

// ---------------------------------------------------------------------------
// Theme toggle
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const root = document.documentElement;
  const storageKey = 'theme';
  const darkQuery = window.matchMedia('(prefers-color-scheme: dark)');

  function applyTheme(theme) {
    root.classList.toggle('dark', theme === 'dark');
    root.dataset.theme = theme;
  }

  function currentPreference() {
    const stored = localStorage.getItem(storageKey);
    if (stored === 'dark' || stored === 'light') {
      return stored;
    }
    return darkQuery.matches ? 'dark' : 'light';
  }

  function syncTheme() {
    applyTheme(currentPreference());
  }

  darkQuery.addEventListener('change', syncTheme);
  syncTheme();

  document.querySelectorAll('.theme-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const next = root.classList.contains('dark') ? 'light' : 'dark';
      applyTheme(next);
      localStorage.setItem(storageKey, next);
    });
  });
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

  function htmlToText(value) {
    const documentFragment = new DOMParser().parseFromString(String(value || ''), 'text/html');
    return documentFragment.body.textContent.trim();
  }

  function safeHttpUrl(value) {
    if (typeof value !== 'string' || value.trim() === '') return null;

    try {
      const url = new URL(value, window.location.href);
      return url.protocol === 'http:' || url.protocol === 'https:' ? url : null;
    } catch (error) {
      return null;
    }
  }

  async function loadMore() {
    if (loading) return;
    loading = true;
    try {
      const localizedUrl = window.agThemeL10n && window.agThemeL10n.restPostsUrl;
      const requestUrl = new URL(localizedUrl || '/wp-json/wp/v2/posts', window.location.href);
      requestUrl.searchParams.set('_embed', '1');
      requestUrl.searchParams.set('per_page', String(perPage));
      requestUrl.searchParams.set('page', String(page + 1));

      const res = await fetch(requestUrl.toString());
      if (!res.ok) return;
      const posts = await res.json();
      if (!Array.isArray(posts) || posts.length === 0) return;
      page += 1;
      for (const post of posts) {
        const postUrl = safeHttpUrl(post.link);
        if (!postUrl) continue;

        const featuredMedia = post._embedded && Array.isArray(post._embedded['wp:featuredmedia'])
          ? post._embedded['wp:featuredmedia'][0]
          : null;
        const mediaUrl = featuredMedia ? safeHttpUrl(featuredMedia.source_url) : null;
        const titleText = htmlToText(post.title && post.title.rendered);
        const fallbackAlt = window.agThemeL10n && window.agThemeL10n.blogPostImageAlt
          ? window.agThemeL10n.blogPostImageAlt
          : 'Blog post image';

        const article = document.createElement('article');
        article.className = 'flex-none snap-center flex flex-col items-center text-center w-full sm:w-1/2 lg:w-1/3 px-2';

        const link = document.createElement('a');
        link.className = 'recent-post-link block';
        link.href = postUrl.href;

        if (mediaUrl) {
          const image = document.createElement('img');
          image.src = mediaUrl.href;
          const mediaAlt = typeof featuredMedia.alt_text === 'string' ? featuredMedia.alt_text : '';
          image.alt = (mediaAlt || titleText || fallbackAlt).trim();
          image.className = 'w-full h-48 object-cover rounded shadow';
          image.loading = 'lazy';
          image.decoding = 'async';
          link.appendChild(image);
        }

        const title = document.createElement('h3');
        title.className = 'mt-4 text-lg font-semibold';
        title.textContent = titleText;
        link.appendChild(title);

        const date = new Date(post.date);
        if (!Number.isNaN(date.getTime())) {
          const time = document.createElement('time');
          time.dateTime = post.date;
          time.className = 'text-sm text-neutral-500';
          time.textContent = date.toLocaleDateString();
          link.appendChild(time);
        }

        article.appendChild(link);
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
  runWhenIdle(() => {
    const input = document.getElementById('search-field');
    const warning = document.getElementById('search-warning');
    const form = input ? input.form : null;

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

    const hideWarning = () => {
      if (warning) warning.classList.add('hidden');
      if (input) input.removeAttribute('aria-invalid');
    };

    if (form) {
      form.addEventListener('submit', (event) => {
        if (input.value.trim() !== '') return;

        event.preventDefault();
        if (warning) warning.classList.remove('hidden');
        input.setAttribute('aria-invalid', 'true');
        input.focus();
      });
    }

    if (input) input.addEventListener('input', hideWarning);
    document.addEventListener('click', (event) => {
      if (!form || !form.contains(event.target)) hideWarning();
    });
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
});

// ---------------------------------------------------------------------------
// Rock Art Research card image sequence
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  runWhenIdle(() => {
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
});
