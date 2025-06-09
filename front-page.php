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
      src="<?php echo esc_url( wp_get_attachment_image_url(3512, 'full') ); ?>"
      alt="Satellite view of the Norwegian coastline"
      class="absolute inset-0 h-full w-full object-cover"
      loading="eager" />

    <img id="hero-img-2"
      src="<?php echo esc_url( wp_get_attachment_image_url(3513, 'full') ); ?>"
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
<!-- Main content area with three featured sections -->
<section id="main-areas" aria-label="Main content" class="py-16">
  <div class="max-w-7xl mx-auto grid gap-12 md:grid-cols-3 justify-center px-4">
    <!-- Gallery card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <img src="<?php echo esc_url( wp_get_attachment_image_url( 8538, 'medium' ) ); ?>"
           alt="Artwork of human alien hybrid"
           class="w-full h-48 object-cover rounded shadow" />
      <h2 class="mt-4 text-xl font-semibold">Art</h2>
      <p class="mt-2 text-sm">My illustrations and drawings</p>
      <a href="https://andersgoliversen.com/gallery/"
         class="mt-4 inline-block bg-neutral-800 text-white py-2 px-4 rounded transition-colors hover:bg-neutral-700">
        View Gallery
      </a>
    </article>

    <!-- Rock Art Research card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <img src="<?php echo esc_url( wp_get_attachment_image_url( 8536, 'medium' ) ); ?>"
           alt="A petroglyph photo from Solbergfeltet"
           class="w-full h-48 object-cover rounded shadow" />
      <h2 class="mt-4 text-xl font-semibold">Rock Art Research</h2>
      <p class="mt-2 text-sm">Research on Norwegian rock art and petroglyphs</p>
      <a href="https://andersgoliversen.com/projects/prehistoric-norway/"
         class="mt-4 inline-block bg-neutral-800 text-white py-2 px-4 rounded transition-colors hover:bg-neutral-700">
        Explore Research
      </a>
    </article>

    <!-- Diurnalis card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <img src="<?php echo esc_url( wp_get_attachment_image_url( 8537, 'medium' ) ); ?>"
           alt="Image of Elijah with a map from the Diurnalis episode &quot;Happy Birthday&quot;"
           class="w-full h-48 object-cover rounded shadow" />
      <h2 class="mt-4 text-xl font-semibold">Diurnalis</h2>
      <p class="mt-2 text-sm">Development art and animation for Diurnalis</p>
      <a href="https://andersgoliversen.com/projects/diurnalis/"
         class="mt-4 inline-block bg-neutral-800 text-white py-2 px-4 rounded transition-colors hover:bg-neutral-700">
        View Diurnalis
      </a>
    </article>
  </div>
</section>

<?php get_footer(); ?>