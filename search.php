<?php get_header(); ?>
<main id="content" class="max-w-3xl mx-auto px-4 py-12 space-y-12">
    <h1 class="text-3xl font-semibold mb-4">
        <?php printf( esc_html__( 'Search Results for: %s', 'andersgoliversen' ), esc_html( get_search_query() ) ); ?>
    </h1>
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ?>
            <article class="space-y-4">
                <?php
                the_title(
                    '<h2 class="text-2xl font-semibold mb-4"><a href="' . esc_url( get_permalink() ) . '">',
                    '</a></h2>'
                );

                if ( has_post_thumbnail() ) {
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    the_post_thumbnail( 'large', [ 'class' => 'w-full max-h-[48rem] object-cover object-center' ] );
                    echo '</a>';
                }

                the_excerpt();

                echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '" class="block text-sm italic text-neutral-500">' . esc_html( get_the_date( 'F j, Y' ) ) . '</time>';
                ?>
            </article>
            <?php
        endwhile;
        ag_custom_posts_pagination();
    else :
        echo '<p>' . esc_html__( 'No results found.', 'andersgoliversen' ) . '</p>';
    endif;
    ?>
</main>
<?php get_footer(); ?>
