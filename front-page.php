<?php get_header(); ?>
<main id="content" class="max-w-7xl mx-auto px-4">
    <section class="text-center py-24 space-y-4">
        <h1 class="text-5xl font-bold"><?php bloginfo( 'name' ); ?></h1>
        <p class="text-xl"><?php bloginfo( 'description' ); ?></p>
        <a href="#main" class="mt-10 inline-block bg-black text-white py-2 px-4 rounded">Scroll</a>
    </section>
    <div id="main" class="py-12">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
        endif;
        ?>
    </div>
</main>
<?php get_footer(); ?>
