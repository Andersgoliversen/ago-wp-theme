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

    <img id="hero-img-1"
      src="<?php echo esc_url( wp_get_attachment_image_url(8697, 'full') ); ?>"
      alt="Satellite view of forest fire in Siberia"
      class="absolute inset-0 h-full w-full object-cover"
      loading="eager" />

    <img id="hero-img-2"
      src="<?php echo esc_url( wp_get_attachment_image_url(8698, 'full') ); ?>"
      alt="Infra-red rendering of the same fire"
      class="absolute inset-0 h-full w-full object-cover"
      loading="lazy" />

    <!-- Dark overlay to boost legibility -->
    <div class="absolute inset-0 bg-black/30 md:bg-black/30 mix-blend-multiply"></div>
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
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 hover:bg-neutral-400 hover:scale-105 no-underline">
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
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 hover:bg-neutral-400 hover:scale-105 no-underline">
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
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 hover:bg-neutral-400 hover:scale-105 no-underline">
        View Diurnalis
      </a>
    </article>
  </div>
</section>

<!-- Latest Posts Section -->
<section id="recent-posts" aria-label="Latest from the Blog" class="py-16">
  <div class="max-w-5xl mx-auto px-4 text-center">
    <h2 class="text-2xl font-semibold mb-8">Latest from the Blog</h2>
    <div class="relative">
      <button id="recent-posts-prev" class="absolute -left-6 sm:-left-8 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600">&lt;</button>
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
                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover rounded shadow' ) ); ?>
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
      <button id="recent-posts-next" class="absolute -right-6 sm:-right-8 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600">&gt;</button>
    </div>
    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
       class="inline-block mt-8 font-bold text-white py-2 px-6 rounded transition-colors transition-transform duration-150 bg-neutral-600 hover:bg-neutral-400 hover:scale-105 no-underline">
      View All Posts
    </a>
  </div>
</section>

<?php get_footer(); ?>