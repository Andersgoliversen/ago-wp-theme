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

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

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
    document.querySelectorAll('.theme-toggle').forEach(btn => {
      btn.setAttribute('aria-pressed', String(theme === 'dark'));
    });
  }

  function currentPreference() {
    let stored;
    try { stored = localStorage.getItem(storageKey); } catch (error) { /* Use the system preference. */ }
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
      try { localStorage.setItem(storageKey, next); } catch (error) { /* The toggle still works without storage. */ }
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
  let hasMore = true;
  const status = document.getElementById('recent-posts-status');
  const labels = window.agThemeL10n || {};
  const seen = new Set(Array.from(slider.querySelectorAll('a'), link => link.href));
  const announce = message => { if (status) status.textContent = message; };
  const atEnd = () => slider.scrollWidth - slider.scrollLeft - slider.clientWidth < 5;

  function updateControls() {
    prevBtn.disabled = loading || slider.scrollLeft < 5;
    nextBtn.disabled = loading || (!hasMore && atEnd());
    slider.setAttribute('aria-busy', String(loading));
  }

  prevBtn.classList.remove('hidden');
  nextBtn.classList.remove('hidden');

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
    if (loading || !hasMore) return false;
    loading = true;
    updateControls();
    announce(labels.loadingPosts || 'Loading posts…');
    try {
      const localizedUrl = window.agThemeL10n && window.agThemeL10n.restPostsUrl;
      const requestUrl = new URL(localizedUrl || '/wp-json/wp/v2/posts', window.location.href);
      requestUrl.searchParams.set('_embed', '1');
      requestUrl.searchParams.set('per_page', String(perPage));
      requestUrl.searchParams.set('page', String(page + 1));
      requestUrl.searchParams.set('orderby', 'date');
      requestUrl.searchParams.set('order', 'desc');

      const res = await fetch(requestUrl.toString(), { signal: AbortSignal.timeout(15000) });
      if (res.status === 400) {
        const error = await res.json();
        if (error.code === 'rest_post_invalid_page_number') {
          hasMore = false;
          announce(labels.postsEnd || 'You have reached the last post.');
          return false;
        }
      }
      if (!res.ok) throw new Error('Posts request failed');
      const posts = await res.json();
      if (!Array.isArray(posts)) throw new Error('Invalid posts response');
      page += 1;
      const totalPages = Number(res.headers.get('X-WP-TotalPages'));
      hasMore = posts.length === perPage && (!totalPages || page < totalPages);
      let added = 0;
      for (const post of posts) {
        const postUrl = safeHttpUrl(post.link);
        if (!postUrl || seen.has(postUrl.href)) continue;

        const featuredMedia = post._embedded && Array.isArray(post._embedded['wp:featuredmedia'])
          ? post._embedded['wp:featuredmedia'][0]
          : null;
        const sizes = featuredMedia && featuredMedia.media_details && featuredMedia.media_details.sizes;
        const thumbnail = sizes && (sizes.medium_large || sizes.medium || sizes.large);
        const mediaUrl = featuredMedia ? safeHttpUrl(thumbnail ? thumbnail.source_url : featuredMedia.source_url) : null;
        const titleText = htmlToText(post.title && post.title.rendered);
        const fallbackAlt = window.agThemeL10n && window.agThemeL10n.blogPostImageAlt
          ? window.agThemeL10n.blogPostImageAlt
          : 'Blog post image';

        const article = document.createElement('article');
        article.className = 'recent-post-card flex-none snap-start flex flex-col items-center text-center min-w-0 px-2';

        const link = document.createElement('a');
        link.className = 'recent-post-link block w-full';
        link.href = postUrl.href;

        if (mediaUrl) {
          const image = document.createElement('img');
          image.src = mediaUrl.href;
          if (thumbnail) {
            image.width = thumbnail.width;
            image.height = thumbnail.height;
            // Use uncropped derivatives with the same aspect ratio.
            const ratio = thumbnail.width / thumbnail.height;
            const candidates = Object.values(sizes).filter(size =>
              size.width > 0 && size.height > 0 &&
              Math.abs(size.width / size.height - ratio) < 0.02 && safeHttpUrl(size.source_url)
            );
            const widths = new Map(candidates.map(size => [size.width, `${safeHttpUrl(size.source_url).href} ${size.width}w`]));
            image.srcset = Array.from(widths.values()).join(', ');
            image.sizes = '(min-width: 1024px) 300px, (min-width: 640px) 45vw, 85vw';
          }
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
          time.className = 'text-sm meta-text';
          time.textContent = date.toLocaleDateString(document.documentElement.lang || undefined, { year: 'numeric', month: 'long', day: 'numeric' });
          link.appendChild(time);
        }

        article.appendChild(link);
        slider.appendChild(article);
        seen.add(postUrl.href);
        added += 1;
      }
      announce(hasMore ? (labels.postsLoaded || 'More posts loaded.') : (labels.postsEnd || 'You have reached the last post.'));
      return added > 0;
    } catch (e) {
      announce(labels.postsError || 'Could not load posts. Try again or use View All Posts.');
      return false;
    } finally {
      loading = false;
      updateControls();
    }
  }

  nextBtn.addEventListener('click', async () => {
    if (loading) return;
    if (atEnd() && !(await loadMore())) return;
    slider.scrollBy({ left: slider.clientWidth, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
  });

  prevBtn.addEventListener('click', () => {
    slider.scrollBy({ left: -slider.clientWidth, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
  });

  // Native touch scrolling avoids a second jump after a swipe.
  slider.addEventListener('scroll', updateControls, { passive: true });
  window.addEventListener('resize', updateControls);
  updateControls();
});

// ---------------------------------------------------------------------------
// Search form enhancements
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  runWhenIdle(() => {
    const input = document.getElementById('search-field');
    const warning = document.getElementById('search-warning');
    const form = input ? input.form : null;

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
        if (reducedMotion.matches) return;
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
      el.addEventListener('touchcancel', stop);
      reducedMotion.addEventListener('change', stop);
    });
  });
});

// ---------------------------------------------------------------------------
// Featured image motion controls
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('motion-toggle');
  if (!toggle) return;
  let paused = false;
  const card = document.getElementById('rock-art-card');
  const imgs = card ? Array.from(card.querySelectorAll('.rock-art-img')) : [];
  const sequence = [0, 1, 0, 2].filter(index => index < imgs.length);
  let position = 0;
  let timer;

  function schedule() {
    if (imgs.length < 2) return;
    timer = setTimeout(() => {
      card.classList.add('rock-wiggle');
      timer = setTimeout(() => {
        card.classList.remove('rock-wiggle');
        card.classList.add('rock-bounce');
        position = (position + 1) % sequence.length;
        imgs.forEach((img, index) => img.classList.toggle('active', index === sequence[position]));
        timer = setTimeout(() => {
          card.classList.remove('rock-bounce');
          schedule();
        }, 600);
      }, 200);
    }, 5000);
  }

  function syncMotion() {
    const stopped = paused || reducedMotion.matches || document.hidden;
    document.body.classList.toggle('animations-paused', stopped);
    toggle.setAttribute('aria-pressed', String(paused));
    clearTimeout(timer);
    if (card) card.classList.remove('rock-wiggle', 'rock-bounce');
    if (!stopped) schedule();
  }

  toggle.classList.remove('hidden');
  toggle.addEventListener('click', () => { paused = !paused; syncMotion(); });
  reducedMotion.addEventListener('change', syncMotion);
  document.addEventListener('visibilitychange', syncMotion);
  syncMotion();
});
