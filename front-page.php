<?php

/**
 * Front-page template.
 */
get_header(); ?>

<!-- Main content area with three featured sections -->
<section id="main-areas" aria-label="<?php esc_attr_e( 'Main content', 'andersgoliversen' ); ?>" class="py-16 pb-12">
  <div class="max-w-7xl mx-auto grid gap-12 md:grid-cols-3 justify-center px-4">
    <!-- Gallery card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <a href="https://andersgoliversen.com/gallery/" class="block no-underline text-inherit">
      <!-- Artwork images panning vertically inside the card -->
      <div class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php
        $gallery_images = array(
          array(
            'attachment_id' => 3133,
            'alt'           => __( 'Artwork of human alien hybrid', 'andersgoliversen' ),
          ),
          array(
            'attachment_id' => 3072,
            'alt'           => __( 'Artwork of a mermonkey with wings', 'andersgoliversen' ),
          ),
          array(
            'attachment_id' => 3525,
            'alt'           => __( 'Artwork of a big tree', 'andersgoliversen' ),
          ),
          array(
            'attachment_id' => 3567,
            'alt'           => __( 'Illustration titled Why is it so dark?', 'andersgoliversen' ),
          ),
          array(
            'attachment_id' => 3155,
            'alt'           => __( 'Artwork of Ullandhaugtarnet', 'andersgoliversen' ),
          ),
        );

        foreach ( $gallery_images as $index => $gallery_image ) {
          $image_number = $index + 1;
          $attributes   = array(
            'id'      => 'gallery-img-' . $image_number,
            'class'   => 'gallery-img absolute inset-0 w-full h-full object-cover',
            'alt'     => $gallery_image['alt'],
            'loading' => 0 === $index ? 'eager' : 'lazy',
            'style'   => '--gallery-delay: ' . ( $index * 10 ) . 's;',
          );

          if ( 0 === $index ) {
            $attributes['fetchpriority'] = 'high';
          }

          echo wp_get_attachment_image( $gallery_image['attachment_id'], 'medium', false, $attributes );
        }
        ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold"><?php esc_html_e( 'Art', 'andersgoliversen' ); ?></h2>
      <p class="mt-2 text-sm"><?php esc_html_e( 'My illustrations and drawings', 'andersgoliversen' ); ?></p>
      </a>
      <a href="https://andersgoliversen.com/gallery/"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        <?php esc_html_e( 'View Gallery', 'andersgoliversen' ); ?>
      </a>
    </article>

    <!-- Rock Art Research card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <a href="https://andersgoliversen.com/projects/prehistoric-norway/" class="block no-underline text-inherit">
      <!-- Three-image sequence with JS-controlled transitions -->
      <div id="rock-art-card" class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 8783, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'rock-art-img-1',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Photograph of a petroglyph at Moelv', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 8782, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'rock-art-img-2',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Pencil drawing of the same Moelv petroglyph as a moose calf', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 8781, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'rock-art-img-3',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Pencil drawing of the same Moelv petroglyph as a goat', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold"><?php esc_html_e( 'Rock Art Research', 'andersgoliversen' ); ?></h2>
      <p class="mt-2 text-sm"><?php esc_html_e( 'Research on Norwegian rock art and petroglyphs', 'andersgoliversen' ); ?></p>
      </a>
      <a href="https://andersgoliversen.com/projects/prehistoric-norway/"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        <?php esc_html_e( 'Explore Research', 'andersgoliversen' ); ?>
      </a>
    </article>

    <!-- Diurnalis card -->
    <article class="w-[320px] flex flex-col items-center text-center">
      <a href="https://andersgoliversen.com/projects/diurnalis/" class="block no-underline text-inherit">
      <!-- Four images zooming and cross-fading -->
      <div class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 2950, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-1',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Title card from the Diurnalis episode Happy Birthday', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2955, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-2',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Still frame from the Diurnalis episode Happy Birthday', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2957, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-3',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Still frame from the Diurnalis episode Mystery', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2949, 'medium', false, array( // Changed 'full' to 'medium'
          'id'    => 'diurnalis-img-4',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Title card from the Diurnalis episode Mystery', 'andersgoliversen' ),
          'loading' => 'lazy',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold"><?php esc_html_e( 'Diurnalis', 'andersgoliversen' ); ?></h2>
      <p class="mt-2 text-sm"><?php esc_html_e( 'Development art and animation for Diurnalis', 'andersgoliversen' ); ?></p>
      </a>
      <a href="https://andersgoliversen.com/projects/diurnalis/"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        <?php esc_html_e( 'View Diurnalis', 'andersgoliversen' ); ?>
      </a>
    </article>
  </div>
</section>

<!-- Latest Posts Section -->
<section id="recent-posts" aria-label="<?php esc_attr_e( 'Latest from the Blog', 'andersgoliversen' ); ?>" class="py-16 pt-12">
  <div class="max-w-5xl mx-auto px-4 text-center">
    <h2 class="text-2xl font-semibold mb-8"><?php esc_html_e( 'Latest from the Blog', 'andersgoliversen' ); ?></h2>
    <div class="relative">
      <button id="recent-posts-prev" class="absolute -left-4 sm:-left-6 md:-left-8 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100" aria-label="<?php esc_attr_e( 'Previous posts', 'andersgoliversen' ); ?>">&lt;</button><!-- Shrink and darken when pressed -->
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
            <a href="<?php the_permalink(); ?>" class="recent-post-link block">
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
      <button id="recent-posts-next" class="absolute -right-4 sm:-right-6 md:-right-8 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100" aria-label="<?php esc_attr_e( 'Next posts', 'andersgoliversen' ); ?>">&gt;</button><!-- Shrink and darken when pressed -->
    </div>
    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
       class="inline-block mt-8 font-bold text-white py-2 px-6 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-400 dark:hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
      <?php esc_html_e( 'View All Posts', 'andersgoliversen' ); ?>
    </a>
  </div>
</section>

<?php get_footer(); ?>
