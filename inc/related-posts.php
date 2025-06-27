<?php
/**
 * Related posts functionality.
 *
 * @package ago
 */

/**
 * Register custom image size for related post thumbnails.
 */
function ag_register_related_image_size() {
    add_image_size( 'ag-related', 300, 200, true );
}
add_action( 'after_setup_theme', 'ag_register_related_image_size' );

/**
 * Retrieve related post IDs for a given post.
 * Cached via transient for 12 hours.
 *
 * @param int $post_id Post ID.
 * @return int[] Array of post IDs.
 */
function ag_get_related_post_ids( $post_id ) {
    $key  = 'ag_related_' . $post_id;
    $data = get_transient( $key );
    if ( false !== $data ) {
        return $data;
    }

    $ids     = array();
    $exclude = array( $post_id );

    $tags = wp_get_post_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
    if ( $tags ) {
        $tag_query = new WP_Query(
            array(
                'post_type'           => 'post',
                'posts_per_page'      => 3,
                'post__not_in'        => $exclude,
                'ignore_sticky_posts' => true,
                'tag__in'             => $tags,
                'orderby'             => 'date',
            )
        );
        foreach ( $tag_query->posts as $p ) {
            $ids[]    = $p->ID;
            $exclude[] = $p->ID;
            if ( count( $ids ) >= 3 ) {
                break;
            }
        }
    }

    if ( count( $ids ) < 3 ) {
        $cats = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'ids' ) );
        if ( $cats ) {
            $cat_query = new WP_Query(
                array(
                    'post_type'           => 'post',
                    'posts_per_page'      => 3 - count( $ids ),
                    'post__not_in'        => $exclude,
                    'ignore_sticky_posts' => true,
                    'category__in'        => $cats,
                    'orderby'             => 'date',
                )
            );
            foreach ( $cat_query->posts as $p ) {
                $ids[]    = $p->ID;
                $exclude[] = $p->ID;
                if ( count( $ids ) >= 3 ) {
                    break;
                }
            }
        }
    }

    if ( count( $ids ) < 3 ) {
        $recent_query = new WP_Query(
            array(
                'post_type'           => 'post',
                'posts_per_page'      => 3 - count( $ids ),
                'post__not_in'        => $exclude,
                'ignore_sticky_posts' => true,
                'orderby'             => 'date',
                'order'               => 'DESC',
            )
        );
        foreach ( $recent_query->posts as $p ) {
            $ids[] = $p->ID;
            if ( count( $ids ) >= 3 ) {
                break;
            }
        }
    }

    set_transient( $key, $ids, 12 * HOUR_IN_SECONDS );
    return $ids;
}

/**
 * Render the related posts section.
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 */
function ago_render_related_posts( $post_id = 0 ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    if ( ! $post_id ) {
        return;
    }

    $ids = ag_get_related_post_ids( $post_id );
    if ( empty( $ids ) ) {
        return;
    }

    echo '<section aria-labelledby="related-posts-title" class="related-posts">';
    echo '<h2 id="related-posts-title" class="related-posts__heading">' . esc_html__( 'Related Posts', 'ago' ) . '</h2>';
    echo '<ul class="related-posts__list">';

    foreach ( $ids as $index => $id ) {
        $title = get_the_title( $id );
        $url   = get_permalink( $id );
        $thumb = get_post_thumbnail_id( $id );
        $attrs = array( 'class' => 'related-posts__image' );
        if ( 0 === $index ) {
            $attrs['loading']       = 'eager';
            $attrs['fetchpriority'] = 'high';
        } else {
            $attrs['loading'] = 'lazy';
        }
        $image = $thumb ? wp_get_attachment_image( $thumb, 'ag-related', false, $attrs ) : '';

        echo '<li class="related-posts__item">';
        echo '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $title ) . '" class="related-posts__link">';
        echo $image; // Contains width & height.
        echo '<span class="related-posts__title">' . esc_html( $title ) . '</span>';
        echo '</a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</section>';
}
