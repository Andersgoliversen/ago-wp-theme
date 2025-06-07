<?php

/**
 * Front-page template
 * Hero section with cross-fading images and centred text.
 */
get_header(); ?>

<section id="hero"
  aria-label="Hero"
  class="relative isolate overflow-hidden min-h-[90vh] w-full">

  <!-- Parallax wrapper for background images -->
  <div id="hero-bg-wrap"
    class="absolute inset-0 -z-10 will-change-transform">

    <img id="hero-img-1"
      src="<?php echo wp_get_attachment_image_url(3512, 'full'); ?>"
      alt="Satellite view of the Norwegian coastline"
      class="absolute inset-0 h-full w-full object-cover"
      loading="eager" />

    <img id="hero-img-2"
      src="<?php echo wp_get_attachment_image_url(3513, 'full'); ?>"
      alt="Infra-red rendering of the same coastline"
      class="absolute inset-0 h-full w-full object-cover"
      loading="lazy" />

    <!-- Dark overlay to boost legibility -->
    <div class="absolute inset-0 bg-black/60 md:bg-black/70 mix-blend-multiply"></div>
  </div>

  <!-- Centred heading + subtitle -->
  <div class="relative z-10 flex h-full flex-col items-center justify-center
              text-center px-4 pointer-events-none">

    <h1 class="text-white text-4xl md:text-6xl font-semibold tracking-wide
               drop-shadow-lg animate-fadeUp">
      Artist and researcher
    </h1>

    <p class="mt-4 text-lg md:text-2xl tracking-widest text-white
              drop-shadow-lg animate-fadeUp delay-[300ms]">
      Exploring creativity, technology, art history, and the future of human expression
    </p>
  </div>
</section>

<?php get_footer(); ?>