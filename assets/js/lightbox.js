// IIFE (Immediately Invoked Function Expression) to encapsulate the lightbox logic,
// preventing global scope pollution and providing a private namespace.
(function(){
  function isDirectImageLink(link) {
    if (!link) return false;

    try {
      const url = new URL(link.href, window.location.href);
      return /\.(?:avif|bmp|gif|jpe?g|png|svg|webp)$/i.test(url.pathname);
    } catch (error) {
      return false;
    }
  }

  // Respect images linked to pages or downloads. Unlinked images and images
  // linked directly to supported image files participate in the lightbox.
  const images = Array.from(document.querySelectorAll('#content img:not(.related-posts__image)'))
    .filter(imgEl => {
      const link = imgEl.closest('a');
      return !link || isDirectImageLink(link);
    });
  if(!images.length) return;
  const labels = window.agThemeL10n || {};

  // Create the lightbox overlay div.
  const overlay = document.createElement('div');
  // Style the overlay for a full-screen, semi-transparent background with centered content.
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);display:none;align-items:center;justify-content:center;flex-direction:column;z-index:9999;';
  // Initially hidden.
  overlay.hidden = true;
  // Set ARIA attributes for accessibility:
  // aria-modal="true" indicates that the content within the dialog is separate from the rest of the page.
  // role="dialog" defines the element as a dialog.
  overlay.setAttribute('aria-modal', 'true');
  overlay.setAttribute('role', 'dialog');
  overlay.setAttribute('aria-label', labels.lightboxLabel || 'Image viewer');
  overlay.className = 'lightbox';
  // Make the overlay focusable programmatically.
  overlay.setAttribute('tabindex', '-1');

  // Populate overlay with image, caption, and navigation buttons.
  overlay.innerHTML = `
    <button class="lb-close" style="position:absolute;top:1rem;right:1rem;font-size:2rem;color:white;background:none;border:none;cursor:pointer">&times;</button>
    <button class="lb-prev" style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:2rem;color:white;background:none;border:none;cursor:pointer">\u276E</button>
    <img style="max-height:80vh;max-width:90vw;margin-bottom:.5rem" />
    <div class="lb-caption" style="color:white;text-align:center"></div>
    <button class="lb-next" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);font-size:2rem;color:white;background:none;border:none;cursor:pointer">\u276F</button>
  `;
  // Append the overlay to the body.
  document.body.appendChild(overlay);

  // Get references to the image, caption, and buttons within the overlay.
  const img = overlay.querySelector('img');
  const caption = overlay.querySelector('.lb-caption');
  const prevBtn = overlay.querySelector('.lb-prev');
  const nextBtn = overlay.querySelector('.lb-next');
  const closeBtn = overlay.querySelector('.lb-close');

  // Set ARIA labels for navigation buttons for screen readers.
  prevBtn.setAttribute('aria-label', labels.previousImage || 'Previous image');
  nextBtn.setAttribute('aria-label', labels.nextImage || 'Next image');
  closeBtn.setAttribute('aria-label', labels.closeLightbox || 'Close image viewer');
  overlay.querySelectorAll('button').forEach(button => { button.type = 'button'; });
  prevBtn.hidden = nextBtn.hidden = images.length < 2;

  // Keep track of the current image index.
  let index = 0;
  // Store the element that was focused before the lightbox was opened.
  let lastFocusedElement;
  let backgroundElements = [];
  let previousOverflow;

  // Copy the existing caption nodes so caption links remain usable without
  // parsing another HTML string. Fall back to the image's alternative text.
  function setCaption(imgEl){
    const fig = imgEl.closest('figure'); // Find the parent <figure> element.
    const figcap = fig ? fig.querySelector('figcaption') : null; // Find <figcaption> within <figure>.
    caption.replaceChildren();

    if (figcap) {
      Array.from(figcap.childNodes).forEach(node => caption.appendChild(node.cloneNode(true)));
    } else {
      caption.textContent = imgEl.alt || '';
    }
  }

  // Function to display the lightbox with the image at a given index.
  function show(i){
    // Calculate the correct index, wrapping around if necessary.
    index = (i + images.length) % images.length;
    const imgEl = images[index]; // Get the image for the current index.
    const link = imgEl.closest('a');
    // Respect an explicit image link. For unlinked attachment images, use the
    // full-size URL supplied by WordPress attachment metadata.
    const href = isDirectImageLink(link)
      ? link.href
      : (imgEl.dataset.fullSrc || imgEl.currentSrc || imgEl.src);
    img.src = href; // Set the image source.
    img.alt = imgEl.alt || '';
    setCaption(imgEl);

    // Store focus only when opening. Next/previous navigation must not replace
    // the page element that should regain focus after the dialog closes.
    if (overlay.hidden) {
      lastFocusedElement = document.activeElement;
      previousOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      backgroundElements = Array.from(document.body.children)
        .filter(element => element !== overlay && element instanceof HTMLElement)
        .map(element => ({ element, inert: element.inert }));
      backgroundElements.forEach(({ element }) => { element.inert = true; });
      overlay.hidden = false;
      overlay.style.display = 'flex';
      closeBtn.focus();
    }
    // Changing an image can replace a caption link that held focus.
    if (!overlay.contains(document.activeElement)) closeBtn.focus();
  }

  // Add activation listeners only to images that participate in the lightbox.
  images.forEach((imgEl, i) => {
    const parentLink = imgEl.closest('a');
    const trigger = parentLink || imgEl;

    trigger.addEventListener('click', e => {
      if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
      e.preventDefault();
      show(i); // Show the lightbox with the clicked image.
    });

    if (!parentLink) {
      imgEl.setAttribute('role', 'button');
      imgEl.setAttribute('tabindex', '0');
      imgEl.addEventListener('keydown', e => {
        if (e.key !== 'Enter' && e.key !== ' ') return;

        e.preventDefault();
        show(i);
      });
    }
  });

  // Function to show the next image.
  function next(){ show(index + 1); }
  // Function to show the previous image.
  function prev(){ show(index - 1); }
  // Function to close the lightbox.
  function close(){
    overlay.hidden = true; // Hide the overlay.
    overlay.style.display = 'none';
    backgroundElements.forEach(({ element, inert }) => { element.inert = inert; });
    backgroundElements = [];
    document.body.style.overflow = previousOverflow;
    // Restore focus to the element that was focused before the lightbox was opened.
    if (lastFocusedElement instanceof HTMLElement) {
      lastFocusedElement.focus();
    }
  }

  // Event listeners for next, previous, and close actions.
  nextBtn.addEventListener('click', next); // Next button click.
  prevBtn.addEventListener('click', prev); // Previous button click.
  closeBtn.addEventListener('click', close); // Close button click.
  // Close lightbox if the overlay background (not content within it) is clicked.
  overlay.addEventListener('click', e => { if(e.target === overlay) close(); });

  // Keyboard navigation and accessibility event listener.
  document.addEventListener('keydown', e => {
    // Do nothing if the overlay is hidden.
    if(overlay.hidden) return;

    // Handle specific key presses.
    if(e.key === 'ArrowRight') { e.preventDefault(); next(); }
    else if(e.key === 'ArrowLeft') { e.preventDefault(); prev(); }
    else if(e.key === 'Escape') { e.preventDefault(); close(); }
    else if (e.key === 'Tab') { // Handle Tab key for focus trapping.
      const focusable = Array.from(overlay.querySelectorAll('button, a[href], [tabindex]'))
        .filter(element => !element.disabled && element.tabIndex >= 0 && element.getClientRects().length);
      const firstFocusableElement = focusable[0];
      const lastFocusableElement = focusable[focusable.length - 1];
      if (e.shiftKey) { // If Shift + Tab is pressed (navigate backwards).
        if (document.activeElement === firstFocusableElement) {
          // If focus is on the first focusable element, move focus to the last.
          lastFocusableElement.focus();
          e.preventDefault(); // Prevent default Tab behavior.
        }
      } else { // If Tab is pressed (navigate forwards).
        if (document.activeElement === lastFocusableElement) {
          // If focus is on the last focusable element, move focus to the first.
          firstFocusableElement.focus();
          e.preventDefault(); // Prevent default Tab behavior.
        }
      }
    }
  });
})();
