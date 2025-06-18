<?php get_header(); ?>
<main id="content" class="max-w-3xl mx-auto px-4 py-12 space-y-12">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            the_title( '<h1 class="text-3xl font-semibold mb-4">', '</h1>' );
            the_content();
            echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '" class="block text-sm italic text-neutral-500">' . esc_html( get_the_date( 'F j, Y' ) ) . '</time>';
        endwhile;
    endif;
    ?>
</main>
<?php get_footer(); ?>
