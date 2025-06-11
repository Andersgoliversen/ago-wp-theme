// IIFE (Immediately Invoked Function Expression) to encapsulate the lightbox logic,
// preventing global scope pollution and providing a private namespace.
(function(){
  // Select all gallery links. If none, exit script.
  const links = Array.from(document.querySelectorAll('.gallery a'));
  if(!links.length) return;

  // Create the lightbox overlay div.
  const overlay = document.createElement('div');
  // Style the overlay for a full-screen, semi-transparent background with centered content.
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);display:flex;align-items:center;justify-content:center;flex-direction:column;z-index:9999;';
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

  // Set ARIA labels for navigation buttons for screen readers.
  prevBtn.setAttribute('aria-label', 'Previous image');
  nextBtn.setAttribute('aria-label', 'Next image');

  // Keep track of the current image index.
  let index = 0;
  // Store the element that was focused before the lightbox was opened.
  let lastFocusedElement;

  // Function to extract a caption for the image.
  // It tries to find a <figcaption> within the link's parent <figure>.
  // Falls back to the link's title attribute or the alt attribute of the <img> inside the link.
  function getCaption(link){
    const fig = link.closest('figure'); // Find the parent <figure> element.
    const figcap = fig ? fig.querySelector('figcaption') : null; // Find <figcaption> within <figure>.
    // Return figcaption text, or link title, or image alt text, or an empty string.
    return figcap ? figcap.textContent : (link.title || link.querySelector('img')?.alt || '');
  }

  // Function to display the lightbox with the image at a given index.
  function show(i){
    // Calculate the correct index, wrapping around if necessary.
    index = (i + links.length) % links.length;
    const link = links[index]; // Get the link for the current index.
    img.src = link.href; // Set the image source.
    caption.textContent = getCaption(link); // Set the caption text.

    // Store the currently focused element before opening the lightbox.
    lastFocusedElement = document.activeElement;
    overlay.hidden = false; // Show the overlay.
    // Focus the overlay (or a specific element within it, e.g., nextBtn) to enable keyboard navigation.
    nextBtn.focus(); // Or overlay.focus();
  }

  // Add click event listeners to each gallery link to open the lightbox.
  links.forEach((link, i) => {
    link.addEventListener('click', e => {
      e.preventDefault(); // Prevent default link navigation.
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
    // Restore focus to the element that was focused before the lightbox was opened.
    if (lastFocusedElement) {
      lastFocusedElement.focus();
    }
  }

  // Event listeners for next, previous, and close actions.
  nextBtn.addEventListener('click', next); // Next button click.
  prevBtn.addEventListener('click', prev); // Previous button click.
  // Close lightbox if the overlay background (not content within it) is clicked.
  overlay.addEventListener('click', e => { if(e.target === overlay) close(); });

  // Define focusable elements within the lightbox for focus trapping.
  // For this simple lightbox, it's just the previous and next buttons.
  const firstFocusableElement = prevBtn;
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
