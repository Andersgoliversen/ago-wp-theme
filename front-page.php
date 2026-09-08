<?php

/**
 * Front-page template.
 */
get_header();

$front_page_content = '';
if ( have_posts() ) {
  the_post();
  $front_page_content = trim( (string) get_the_content() );
}
?>

<main id="content" class="home-content" tabindex="-1">
<h1 class="sr-only"><?php bloginfo( 'name' ); ?></h1>
<!-- Main content area with three featured sections -->
<section id="main-areas" aria-label="<?php esc_attr_e( 'Main content', 'andersgoliversen' ); ?>" class="py-16 pb-12">
  <?php if ( '' !== $front_page_content ) : ?>
  <div class="entry-content max-w-5xl mx-auto px-4">
    <?php the_content(); ?>
  </div>
  <?php else : ?>
  <div class="max-w-7xl mx-auto grid gap-12 sm:grid-cols-2 xl:grid-cols-3 justify-items-center px-4">
    <!-- Gallery card -->
    <article class="w-full max-w-[320px] min-w-0 flex flex-col items-center text-center">
      <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="block w-full no-underline text-inherit">
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
            'sizes'   => '(max-width: 639px) calc(100vw - 2rem), 320px',
            'style'   => '--gallery-delay: ' . ( $index * 10 ) . 's;',
          );

          if ( 0 === $index ) {
            $attributes['fetchpriority'] = 'high';
          }

          echo wp_get_attachment_image( $gallery_image['attachment_id'], 'medium_large', false, $attributes );
        }
        ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold"><?php esc_html_e( 'Art', 'andersgoliversen' ); ?></h2>
      <p class="mt-2 text-sm"><?php esc_html_e( 'My illustrations and drawings', 'andersgoliversen' ); ?></p>
      </a>
      <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-500 dark:hover:bg-neutral-600 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        <?php esc_html_e( 'View Gallery', 'andersgoliversen' ); ?>
      </a>
    </article>

    <!-- Rock Art Research card -->
    <article class="w-full max-w-[320px] min-w-0 flex flex-col items-center text-center">
      <a href="<?php echo esc_url( home_url( '/projects/prehistoric-norway/' ) ); ?>" class="block w-full no-underline text-inherit">
      <!-- Three-image sequence with JS-controlled transitions -->
      <div id="rock-art-card" class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 8783, 'medium_large', false, array(
          'id'    => 'rock-art-img-1',
          'class' => 'rock-art-img active absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Photograph of a petroglyph at Moelv', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
        <?php echo wp_get_attachment_image( 8782, 'medium_large', false, array(
          'id'    => 'rock-art-img-2',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Pencil drawing of the same Moelv petroglyph as a moose calf', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
        <?php echo wp_get_attachment_image( 8781, 'medium_large', false, array(
          'id'    => 'rock-art-img-3',
          'class' => 'rock-art-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Pencil drawing of the same Moelv petroglyph as a goat', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold"><?php esc_html_e( 'Rock Art Research', 'andersgoliversen' ); ?></h2>
      <p class="mt-2 text-sm"><?php esc_html_e( 'Research on Norwegian rock art and petroglyphs', 'andersgoliversen' ); ?></p>
      </a>
      <a href="<?php echo esc_url( home_url( '/projects/prehistoric-norway/' ) ); ?>"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-500 dark:hover:bg-neutral-600 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        <?php esc_html_e( 'Explore Research', 'andersgoliversen' ); ?>
      </a>
    </article>

    <!-- Diurnalis card -->
    <article class="w-full max-w-[320px] min-w-0 flex flex-col items-center text-center">
      <a href="<?php echo esc_url( home_url( '/projects/diurnalis/' ) ); ?>" class="block w-full no-underline text-inherit">
      <!-- Four images zooming and cross-fading -->
      <div class="relative w-full h-48 overflow-hidden rounded shadow">
        <?php echo wp_get_attachment_image( 2950, 'medium_large', false, array(
          'id'    => 'diurnalis-img-1',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Title card from the Diurnalis episode Happy Birthday', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2955, 'medium_large', false, array(
          'id'    => 'diurnalis-img-2',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Still frame from the Diurnalis episode Happy Birthday', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2957, 'medium_large', false, array(
          'id'    => 'diurnalis-img-3',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Still frame from the Diurnalis episode Mystery', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
        <?php echo wp_get_attachment_image( 2949, 'medium_large', false, array(
          'id'    => 'diurnalis-img-4',
          'class' => 'diurnalis-img absolute inset-0 w-full h-full object-cover',
          'alt'   => __( 'Title card from the Diurnalis episode Mystery', 'andersgoliversen' ),
          'loading' => 'lazy',
          'sizes' => '(max-width: 639px) calc(100vw - 2rem), 320px',
        ) ); ?>
      </div>
      <h2 class="mt-4 text-xl font-semibold"><?php esc_html_e( 'Diurnalis', 'andersgoliversen' ); ?></h2>
      <p class="mt-2 text-sm"><?php esc_html_e( 'Development art and animation for Diurnalis', 'andersgoliversen' ); ?></p>
      </a>
      <a href="<?php echo esc_url( home_url( '/projects/diurnalis/' ) ); ?>"
         class="mt-4 inline-block font-bold text-white py-2 px-4 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-500 dark:hover:bg-neutral-600 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
        <?php esc_html_e( 'View Diurnalis', 'andersgoliversen' ); ?>
      </a>
    </article>
  </div>
  <div class="text-center mt-6">
    <button id="motion-toggle" type="button" class="hidden motion-toggle" aria-pressed="false" aria-controls="main-areas"><?php esc_html_e( 'Pause animations', 'andersgoliversen' ); ?></button>
  </div>
  <?php endif; ?>
</section>

<!-- Latest Posts Section -->
<section id="recent-posts" aria-label="<?php esc_attr_e( 'Latest from the Blog', 'andersgoliversen' ); ?>" class="py-16 pt-12">
  <div class="max-w-5xl mx-auto px-4 text-center">
    <h2 class="text-2xl font-semibold mb-8"><?php esc_html_e( 'Latest from the Blog', 'andersgoliversen' ); ?></h2>
    <div class="relative px-6 sm:px-8">
      <button id="recent-posts-prev" type="button" aria-controls="recent-posts-slider" class="hidden absolute left-0 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100" aria-label="<?php esc_attr_e( 'Previous posts', 'andersgoliversen' ); ?>">&lt;</button><!-- Shrink and darken when pressed -->
      <div id="recent-posts-slider" tabindex="0" aria-label="<?php esc_attr_e( 'Recent posts', 'andersgoliversen' ); ?>" class="flex overflow-x-auto snap-x snap-mandatory gap-2">
      <?php
      $recent = new WP_Query( array(
        'posts_per_page' => 3,
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
      ) );
      if ( $recent->have_posts() ) :
        while ( $recent->have_posts() ) :
          $recent->the_post();
      ?>
          <article class="recent-post-card flex-none snap-start flex flex-col items-center text-center min-w-0 px-2">
            <a href="<?php the_permalink(); ?>" class="recent-post-link block w-full">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover rounded shadow', 'sizes' => '(min-width: 1024px) 300px, (min-width: 640px) 45vw, 85vw' ) ); ?>
              <?php endif; ?>
              <h3 class="mt-4 text-lg font-semibold"><?php the_title(); ?></h3>
              <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="text-sm meta-text">
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
      <button id="recent-posts-next" type="button" aria-controls="recent-posts-slider" class="hidden absolute right-0 top-1/2 -translate-y-1/2 z-10 px-2 text-2xl no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600 dark:hover:text-neutral-300 active:scale-95 active:text-neutral-900 dark:active:text-neutral-100" aria-label="<?php esc_attr_e( 'Next posts', 'andersgoliversen' ); ?>">&gt;</button><!-- Shrink and darken when pressed -->
    </div>
    <p id="recent-posts-status" class="text-sm meta-text mt-2" role="status" aria-live="polite" aria-atomic="true"></p>
    <a href="<?php echo esc_url( ag_get_blog_crumb()['url'] ); ?>"
       class="inline-block mt-8 font-bold text-white py-2 px-6 rounded transition-colors transition-transform duration-150 bg-neutral-600 dark:bg-neutral-500 hover:bg-neutral-500 dark:hover:bg-neutral-600 hover:scale-105 active:bg-neutral-700 dark:active:bg-neutral-600 active:scale-95 no-underline"><!-- Darken and shrink on click -->
      <?php esc_html_e( 'View All Posts', 'andersgoliversen' ); ?>
    </a>
  </div>
</section>

</main>
<?php get_footer(); ?>
