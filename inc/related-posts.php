<?php
/**
 * Related posts functionality.
 *
 * @package andersgoliversen
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
 * Cached via transient for 12 hours and revalidated before use.
 *
 * @param int $post_id Post ID.
 * @return int[] Array of post IDs.
 */
function ag_get_related_post_ids( $post_id ) {
    $version = max( 1, absint( get_option( 'ag_related_posts_cache_version', 1 ) ) );
    $key     = 'ag_related_' . $version . '_' . $post_id;
    $data = get_transient( $key );
    if ( false !== $data ) {
        $data          = array_map( 'absint', (array) $data );
        if ( empty( $data ) ) {
            return array();
        }

        $published_ids = get_posts(
            array(
                'post_type'              => 'post',
                'post_status'            => 'publish',
                'post__in'               => $data,
                'orderby'                => 'post__in',
                'posts_per_page'         => count( $data ),
                'fields'                 => 'ids',
                'ignore_sticky_posts'    => true,
                'no_found_rows'           => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            )
        );

        if ( $published_ids === $data ) {
            return $data;
        }

        delete_transient( $key );
    }

    $ids     = array();
    $exclude = array( $post_id );

    $tags = wp_get_post_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
    if ( $tags ) {
        $tag_query = new WP_Query(
            array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => 3,
                'post__not_in'        => $exclude,
                'ignore_sticky_posts' => true,
                'tag__in'             => $tags,
                'orderby'             => 'date',
                'fields'              => 'ids',
                'no_found_rows'       => true,
            )
        );
        foreach ( $tag_query->posts as $related_id ) {
            $ids[]     = $related_id;
            $exclude[] = $related_id;
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
                    'post_status'         => 'publish',
                    'posts_per_page'      => 3 - count( $ids ),
                    'post__not_in'        => $exclude,
                    'ignore_sticky_posts' => true,
                    'category__in'        => $cats,
                    'orderby'             => 'date',
                    'fields'              => 'ids',
                    'no_found_rows'       => true,
                )
            );
            foreach ( $cat_query->posts as $related_id ) {
                $ids[]     = $related_id;
                $exclude[] = $related_id;
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
                'post_status'         => 'publish',
                'posts_per_page'      => 3 - count( $ids ),
                'post__not_in'        => $exclude,
                'ignore_sticky_posts' => true,
                'orderby'             => 'date',
                'order'               => 'DESC',
                'fields'              => 'ids',
                'no_found_rows'       => true,
            )
        );
        foreach ( $recent_query->posts as $related_id ) {
            $ids[] = $related_id;
            if ( count( $ids ) >= 3 ) {
                break;
            }
        }
    }

    set_transient( $key, $ids, 12 * HOUR_IN_SECONDS );
    return $ids;
}

/**
 * Delete the source post's related-post cache after relevant post changes.
 * Cached results that contain another changed post are independently checked
 * for publication status whenever they are read.
 *
 * @param int $post_id Post ID.
 */
function ag_clear_related_post_cache( $post_id ) {
    if ( 'post' === get_post_type( $post_id ) ) {
        $version = max( 1, absint( get_option( 'ag_related_posts_cache_version', 1 ) ) );
        delete_transient( 'ag_related_' . $version . '_' . $post_id );
    }
}
add_action( 'save_post_post', 'ag_clear_related_post_cache' );
add_action( 'before_delete_post', 'ag_clear_related_post_cache' );
add_action( 'trashed_post', 'ag_clear_related_post_cache' );
add_action( 'untrashed_post', 'ag_clear_related_post_cache' );

/**
 * Move all related-post lookups to a fresh cache namespace when publication
 * changes can affect which posts are eligible as recommendations.
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Previous post status.
 * @param WP_Post $post       Post object.
 */
function ag_invalidate_related_post_caches_on_status_change( $new_status, $old_status, $post ) {
    if ( 'post' !== $post->post_type || $new_status === $old_status ) {
        return;
    }

    $version = max( 1, absint( get_option( 'ag_related_posts_cache_version', 1 ) ) );
    update_option( 'ag_related_posts_cache_version', $version + 1, false );
}
add_action( 'transition_post_status', 'ag_invalidate_related_post_caches_on_status_change', 10, 3 );

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
    echo '<h2 id="related-posts-title" class="related-posts__heading">' . esc_html__( 'Related Posts', 'andersgoliversen' ) . '</h2>';
    echo '<ul class="related-posts__list">';

    foreach ( $ids as $id ) {
        $title = get_the_title( $id );
        $url   = get_permalink( $id );
        $thumb = get_post_thumbnail_id( $id );
        $attrs = array( 'class' => 'related-posts__image' );
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
