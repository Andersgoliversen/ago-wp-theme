<?php get_header(); ?>
<main id="content" class="max-w-3xl mx-auto px-4 py-12 space-y-12">
    <h1 class="text-3xl font-semibold mb-4">
        <?php the_archive_title(); ?>
    </h1>
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            the_title( '<h2 class="text-2xl font-semibold mb-4"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
            the_excerpt();
        endwhile;
        ag_custom_posts_pagination();
    else :
        echo '<p>' . esc_html__( 'No posts found.', 'andersgoliversen' ) . '</p>';
    endif;
    ?>
</main>
<?php get_footer(); ?>
