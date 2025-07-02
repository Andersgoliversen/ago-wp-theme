// IIFE (Immediately Invoked Function Expression) to encapsulate the lightbox logic,
// preventing global scope pollution and providing a private namespace.
(function(){
  // Select all images inside the main content area. If none, exit script.
  const images = Array.from(document.querySelectorAll('#content img'));
  if(!images.length) return;

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
  prevBtn.setAttribute('aria-label', 'Previous image');
  nextBtn.setAttribute('aria-label', 'Next image');
  closeBtn.setAttribute('aria-label', 'Close');

  // Keep track of the current image index.
  let index = 0;
  // Store the element that was focused before the lightbox was opened.
  let lastFocusedElement;

  // Function to extract a caption for the image.
  // It tries to find a <figcaption> within the link's parent <figure>.
  // Falls back to the link's title attribute or the alt attribute of the <img> inside the link.
  function getCaption(imgEl){
    const fig = imgEl.closest('figure'); // Find the parent <figure> element.
    const figcap = fig ? fig.querySelector('figcaption') : null; // Find <figcaption> within <figure>.
    // Return figcaption text or alt text from the image element.
    return figcap ? figcap.textContent : (imgEl.alt || '');
  }

  // Function to display the lightbox with the image at a given index.
  function show(i){
    // Calculate the correct index, wrapping around if necessary.
    index = (i + images.length) % images.length;
    const imgEl = images[index]; // Get the image for the current index.
    const link = imgEl.closest('a');
    // Use the link href if it points to an image, otherwise fall back to the image src.
    const href = link && /\.(jpe?g|png|gif|webp|svg|bmp)(\?.*)?$/i.test(link.href) ? link.href : imgEl.src;
    img.src = href; // Set the image source.
    caption.textContent = getCaption(imgEl); // Set the caption text.

    // Store the currently focused element before opening the lightbox.
    lastFocusedElement = document.activeElement;
    overlay.hidden = false; // Show the overlay.
    overlay.style.display = 'flex';
    // Focus the overlay (or a specific element within it, e.g., nextBtn) to enable keyboard navigation.
    nextBtn.focus(); // Or overlay.focus();
  }

  // Add click event listeners to each image to open the lightbox.
  images.forEach((imgEl, i) => {
    const parentLink = imgEl.closest('a');
    if(parentLink) parentLink.addEventListener('click', e => e.preventDefault());
    imgEl.addEventListener('click', e => {
      e.preventDefault();
      show(i); // Show the lightbox with the clicked image.
    });
  });

  // Function to show the next image.
  function next(){ show(index + 1); }
  // Function to show the previous image.
  function prev(){ show(index - 1); }
  // Function to close the lightbox.
  function close(){
    overlay.hidden = true; // Hide the overlay.
    overlay.style.display = 'none';
    // Restore focus to the element that was focused before the lightbox was opened.
    if (lastFocusedElement) {
      lastFocusedElement.focus();
    }
  }

  // Event listeners for next, previous, and close actions.
  nextBtn.addEventListener('click', next); // Next button click.
  prevBtn.addEventListener('click', prev); // Previous button click.
  closeBtn.addEventListener('click', close); // Close button click.
  // Close lightbox if the overlay background (not content within it) is clicked.
  overlay.addEventListener('click', e => { if(e.target === overlay) close(); });

  // Define focusable elements within the lightbox for focus trapping.
  // The first is the close button and the last is the next button.
  const firstFocusableElement = closeBtn;
  const lastFocusableElement = nextBtn;

  // Keyboard navigation and accessibility event listener.
  document.addEventListener('keydown', e => {
    // Do nothing if the overlay is hidden.
    if(overlay.hidden) return;

    // Handle specific key presses.
    if(e.key === 'ArrowRight') next(); // Next image on Right Arrow.
    else if(e.key === 'ArrowLeft') prev(); // Previous image on Left Arrow.
    else if(e.key === 'Escape') close(); // Close lightbox on Escape.
    else if (e.key === 'Tab') { // Handle Tab key for focus trapping.
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
