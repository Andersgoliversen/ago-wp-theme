<?php

/**
 * Front-page template
 * Hero section with cross-fading images and centred text.
 */
get_header(); ?>

<section id="hero"
  aria-label="Hero"
  class="relative isolate overflow-hidden w-full aspect-[1914/548]">

  <!-- Parallax wrapper for background images -->
  <div id="hero-bg-wrap"
    class="absolute inset-0 -z-10 will-change-transform">

    <?php echo wp_get_attachment_image(
        8697,
        'full',
        false,
        array(
            'id'            => 'hero-img-1',
            'class'         => 'absolute inset-0 h-full w-full object-cover',
            'alt'           => 'Satellite view of forest fire in Siberia',
            'loading'       => 'eager',
            // fetchpriority hints the browser this image is crucial for LCP.
            'fetchpriority' => 'high',
        )
    ); ?>

    <?php echo wp_get_attachment_image(
        8698,
        'full',
        false,
        array(
            'id'            => 'hero-img-2',
            'class'         => 'absolute inset-0 h-full w-full object-cover',
            'alt'           => 'Infra-red rendering of the same fire',
            'loading'       => 'lazy',
        )
    ); ?>

    <!-- Dark overlay to boost legibility -->
    <div class="absolute inset-0 bg-black/30 md:bg-black/30 mix-blend-multiply"></div>
  </div>

  <!-- Centred heading + subtitle -->
  <div class="relative z-10 flex h-full flex-col items-center justify-center
              text-center px-4 pointer-events-none">

    <h1 class="text-white text-2xl sm:text-4xl md:text-6xl font-semibold tracking-wide
               drop-shadow-lg animate-fadeUp">
      Artist and researcher
    </h1>

    <p class="mt-4 text-sm sm:text-lg md:text-2xl tracking-widest text-white
              drop-shadow-lg animate-fadeUp delay-[300ms]">
      Exploring creativity, technology, art history, and the future of human expression
    </p>
  </div>
</section>
<!-- Main content area with three featured sections -->
<section id="main-areas" aria-label="Main content" class="py-16 pb-12">
  <div class="max-w-7xl mx-auto grid gap-12 md:grid-cols-3 justify-center px-4">
    <!-- Gallery card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <!-- Two tall images panning vertically inside the card -->
      <div class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 3133, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'gallery-img-1',
          'class' => 'absolute inset-0 w-full h-full object-cover',
          'alt'   => 'Artwork of human alien hybrid',
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 3072, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'gallery-img-2',
          'class' => 'absolute inset-0 w-full h-full object-cover',
          'alt'   => 'Artwork of a mermonkey with wings',
          'loading' => 'lazy',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold">Art</h2>
      <p class="mt-2 text-sm">My illustrations and drawings</p>
      <a href="https://andersgoliversen.com/gallery/"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        View Gallery
      </a>
    </article>

    <!-- Rock Art Research card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <!-- Three-image sequence with JS-controlled transitions -->
      <div id="rock-art-card" class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 8783, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'rock-art-img-1',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'photograph of a petroglyph at Moelv',
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 8782, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'rock-art-img-2',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'pencil drawing of the same Moelv petroglyph as a moose calf',
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 8781, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'rock-art-img-3',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'pencil drawing of the same Moelv petroglyph as a goat',
          'loading' => 'lazy',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold">Rock Art Research</h2>
      <p class="mt-2 text-sm">Research on Norwegian rock art and petroglyphs</p>
      <a href="https://andersgoliversen.com/projects/prehistoric-norway/"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        Explore Research
      </a>
    </article>

    <!-- Diurnalis card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <!-- Four images zooming and cross-fading -->
      <div class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 2950, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-1',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'title card from the Diurnalis episode Happy Birthday',
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2955, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-2',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'still frame from the Diurnalis episode Happy Birthday',
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2957, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-3',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'still frame from the Diurnalis episode Mystery',
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2949, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-4',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => 'title card from the Diurnalis episode Mystery',
          'loading' => 'lazy',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold">Diurnalis</h2>
      <p class="mt-2 text-sm">Development art and animation for Diurnalis</p>
      <a href="https://andersgoliversen.com/projects/diurnalis/"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        View Diurnalis
      </a>
    </article>
  </div>
</section>

<!-- Latest Posts Section -->
<section id="recent-posts" aria-label="Latest from the Blog" class="py-16 pt-12">
  <div class="max-w-5xl mx-auto px-4 text-center">
    <h2 class="text-2xl font-semibold mb-8">Latest from the Blog</h2>
    <div class="relative">
      <button id="recent-posts-prev" class="absolute -left-4 sm:-left-6 md:-left-8 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100">&lt;</button><!-- Shrink and darken when pressed -->
      <div id="recent-posts-slider" class="flex overflow-x-auto snap-x snap-mandatory gap-2 scroll-smooth px-8 no-scrollbar">
      <?php
      $recent = new WP_Query( array(
        'posts_per_page' => 3,
      ) );
      if ( $recent->have_posts() ) :
        while ( $recent->have_posts() ) :
          $recent->the_post();
      ?>
          <article class="flex-none snap-center flex flex-col items-center text-center w-full sm:w-1/2 lg:w-1/3 px-2">
            <a href="<?php the_permalink(); ?>" class="block">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover rounded shadow', 'alt' => esc_attr(get_the_title()) ) ); ?>
              <?php endif; ?>
              <h3 class="mt-4 text-lg font-semibold"><?php the_title(); ?></h3>
              <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="text-sm text-neutral-500">
                <?php echo esc_html( get_the_date() ); ?>
              </time>
            </a>
          </article>
      <?php
        endwhile;
        wp_reset_postdata();
      endif;
      ?>
      </div>
      <button id="recent-posts-next" class="absolute -right-4 sm:-right-6 md:-right-8 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100">&gt;</button><!-- Shrink and darken when pressed -->
    </div>
    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
       class="inline-block mt-8 font-bold text-white py-2 px-6 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
      View All Posts
    </a>
  </div>
</section>

<?php get_footer(); ?>