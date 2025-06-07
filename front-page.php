<?php get_header(); ?>

<section id="hero" aria-label="Hero" class="relative min-h-[80vh] w-full overflow-hidden isolate transition-transform duration-1000 ease-in-out hover:scale-105 will-change-transform">
    <div id="hero-bg-wrap" class="absolute inset-0 -z-10 will-change-transform">
        <img class="hero-bg absolute inset-0 h-full w-full object-cover animate-crossfade will-change-opacity" src="<?php echo esc_url( wp_get_attachment_image_url( 3512, 'full' ) ); ?>" alt="Satellite view of the Norwegian coast" loading="eager">
        <img class="hero-bg absolute inset-0 h-full w-full object-cover opacity-0 animate-crossfade will-change-opacity" src="<?php echo esc_url( wp_get_attachment_image_url( 3513, 'full' ) ); ?>" alt="Infrared rendering of the same coastline" loading="lazy">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <div class="relative z-10 flex h-full flex-col items-center justify-center text-center px-4">
        <h1 class="text-3xl md:text-5xl font-semibold tracking-wide animate-fadeUp">Anders Gjesdal Oliversen – Artist and researcher</h1>
        <p class="mt-4 text-lg md:text-2xl tracking-wider animate-fadeUp delay-300">Exploring creativity, technology, art history, and the future of human expression</p>
    </div>
</section>

<main id="content" class="max-w-7xl mx-auto px-4">
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
