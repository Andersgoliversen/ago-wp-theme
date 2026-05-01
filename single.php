<?php get_header(); ?>
<main id="content" class="max-w-3xl mx-auto px-4 py-12 space-y-12">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ag_render_post_breadcrumbs();
            the_title( '<h1 class="text-3xl font-semibold mb-4">', '</h1>' );
            the_content();
            $tags = get_the_tags();
            if ( $tags ) :
                ?>
                <footer class="post-tags mt-8 pt-6 border-t text-sm meta-text" aria-label="<?php esc_attr_e( 'Post tags', 'andersgoliversen' ); ?>">
                    <span class="font-semibold"><?php esc_html_e( 'Tags:', 'andersgoliversen' ); ?></span>
                    <span class="post-tags__links inline-flex flex-wrap gap-x-3 gap-y-2">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a class="post-tags__link underline text-inherit" href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" rel="tag"><?php echo esc_html( $tag->name ); ?></a>
                        <?php endforeach; ?>
                    </span>
                </footer>
                <?php
            endif;
            get_template_part( 'template-parts/related-posts' );
        endwhile;
    endif;
    ?>
</main>
<?php get_footer(); ?>
