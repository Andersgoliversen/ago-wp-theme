// IIFE (Immediately Invoked Function Expression) to encapsulate the lightbox logic and avoid polluting the global scope.
(function(){
    // Select all anchor tags within elements having the class 'gallery'.
    const links = document.querySelectorAll('.gallery a');
    // If no gallery links are found, exit the script.
    if(!links.length) return;

    // Create the lightbox overlay div.
    const overlay = document.createElement('div');
    // Style the overlay for a full-screen, semi-transparent background with centered content.
    overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;z-index:9999;';
    // Set the inner HTML of the overlay to include an image tag, styled to fit within the overlay.
    overlay.innerHTML = '<img style="max-height:100%;max-width:100%" />';
    // Initially hide the overlay.
    overlay.hidden = true;
    // Append the overlay to the document body.
    document.body.appendChild(overlay);

    // Get the image element within the overlay.
    const img = overlay.querySelector('img');

    // Iterate over each gallery link.
    links.forEach(function(link){
        // Add a click event listener to each link.
        link.addEventListener('click', function(event){
            // Prevent the default anchor tag behavior (navigation).
            event.preventDefault();
            // Set the source of the overlay image to the href of the clicked link.
            img.src = link.href;
            // Show the overlay.
            overlay.hidden = false;
        });
    });

    // Add a click event listener to the overlay itself.
    overlay.addEventListener('click', function(){
        // Hide the overlay when it's clicked (this allows closing the lightbox by clicking outside the image).
        overlay.hidden = true;
    });
})();
