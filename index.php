<?php get_header(); ?>

<main class="max-w-3xl mx-auto px-4 py-12 space-y-12">
<?php
if ( have_posts() ) :
        while ( have_posts() ) :
                the_post();
                ?>
                <article class="space-y-4">
                    <?php
                    the_title( '<h2 class="text-2xl font-semibold mb-4"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
                    if ( has_post_thumbnail() ) {
                        echo '<a href="' . esc_url( get_permalink() ) . '">';
                        the_post_thumbnail( 'large', array( 'class' => 'w-full max-h-[48rem] object-cover object-center' ) );
                        echo '</a>';
                    }

                    the_excerpt();

                    echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '" class="block text-sm italic text-neutral-500">' . esc_html( get_the_date( 'F j, Y' ) ) . '</time>';
                    ?>
                </article>
                <?php
        endwhile;
        the_posts_pagination(
            array(
                'mid_size'           => 2,
                'end_size'           => 1,
                'prev_text'          => __( '&larr; Previous Page', 'andersgoliversen' ),
                'next_text'          => __( 'Next Page &rarr;', 'andersgoliversen' ),
                'screen_reader_text' => '',
            )
        );
else :
        echo '<p>' . esc_html__( 'No posts found.', 'andersgoliversen' ) . '</p>';
endif;
?>
</main>

<?php get_footer(); ?>
