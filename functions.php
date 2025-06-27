<?php
/**
 * Theme setup and utilities.
 *
 * @package andersgoliversen
 */

/**
 * Register navigation and theme supports.
 */
function ag_theme_setup() {
    // Register a primary navigation menu.
    register_nav_menus( array( 'primary' => __( 'Primary', 'andersgoliversen' ) ) );
    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );
    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );
    // Enable support for responsive embedded content (e.g., YouTube videos).
    add_theme_support( 'responsive-embeds' );
    // Enable support for a custom logo.
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'ag_theme_setup' );

/**
 * Enqueue theme assets with file modification time for cache busting.
 */
function ag_enqueue_assets() {
    $theme_uri  = get_template_directory_uri();
    $theme_path = get_template_directory();

    $css = '/assets/css/theme.css';
    $js  = '/assets/js/theme.min.js'; // Changed to .min.js

    if ( file_exists( $theme_path . $css ) ) {
        // Enqueue the main stylesheet. filemtime() is used for cache-busting by appending
        // the file's last modification time as a version string.
        wp_enqueue_style( 'ag-theme', $theme_uri . $css, array(), filemtime( $theme_path . $css ) );
    }

    // Check if the minified JS file exists, otherwise fall back to the original.
    if ( file_exists( $theme_path . $js ) ) {
        wp_enqueue_script( 'ag-theme', $theme_uri . $js, array(), filemtime( $theme_path . $js ), true );
    } elseif ( file_exists( $theme_path . str_replace( '.min.js', '.js', $js ) ) ) {
        // Fallback to non-minified version if .min.js is not found
        $js_fallback = str_replace( '.min.js', '.js', $js );
        wp_enqueue_script( 'ag-theme', $theme_uri . $js_fallback, array(), filemtime( $theme_path . $js_fallback ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'ag_enqueue_assets' );

/**
 * Output custom root variables.
 */
function ag_output_root_vars() {
    // The CSS variable --pagebg is static and therefore safe from XSS.
    // If this variable were to become dynamic (e.g., user-configurable),
    // it would need proper sanitization/escaping, like esc_html() or esc_attr()
    // depending on the context.
    echo '<style>:root{--pagebg:oklch(97% 0.001 106.424);}</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'ag_output_root_vars', 0 );

/**
 * Preload hero images on the front page.
 *
 * Preloading ensures the largest images begin downloading
 * as soon as possible, improving Largest Contentful Paint.
 */
function ag_preload_hero_images() {
    if ( ! is_front_page() ) {
        return;
    }

    $ids = array( 8697, 8698 );
    foreach ( $ids as $id ) {
        $url = wp_get_attachment_image_url( $id, 'full' );
        if ( $url ) {
            printf( "<link rel='preload' as='image' href='%s'>\n", esc_url( $url ) );
        }
    }
}
add_action( 'wp_head', 'ag_preload_hero_images', 1 );

/**
 * Basic navigation fallback when no menu is assigned.
 * Provides a simple list of pages if no custom menu is set in Appearance > Menus.
 * This improves user experience on fresh installs or if a menu is accidentally unassigned.
 */
function ag_fallback_nav() {
    // Using wp_kses to allow specific HTML tags and attributes for the navigation structure.
    // Define allowed HTML for the wrapper.
    $allowed_html_ul = array(
        'ul' => array(
            'class' => true,
        ),
    );
    echo wp_kses( '<ul class="space-x-6 text-base font-medium">', $allowed_html_ul );
    wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) ); // wp_list_pages escapes its output.
    echo wp_kses( '</ul>', $allowed_html_ul );
}

/**
 * Add loading="lazy" to attachment images.
 *
 * @param array $attr Attributes.
 * @return array
 */
function ag_lazy_loading( $attr ) {
    // Adds loading="lazy" to image attributes.
    // This improves initial page load performance by deferring the loading of off-screen images
    // until they are about to enter the viewport.
    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ag_lazy_loading' );

/**
 * Prefer AVIF or WebP sources when available.
 *
 * @param array  $sources       Srcset sources.
 * @param array  $size_array    Image size.
 * @param string $image_src     Image src.
 * @param array  $image_meta    Image meta.
 * @param int    $attachment_id Attachment ID.
 * @return array
 */
function ag_modern_image_formats( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
    // Get the WordPress upload directory information. This is trusted WordPress data.
    // $upload_dir['baseurl'] is the base URL for uploads (e.g., https://example.com/wp-content/uploads).
    // $upload_dir['basedir'] is the base server path for uploads (e.g., /var/www/html/wp-content/uploads).
    $upload_dir = wp_get_upload_dir();

    // Create a copy of the $sources array to iterate over.
    // This is done to avoid potential issues with modifying an array while looping over it.
    $sources_copy = $sources;

    // Loop through an array of modern image format extensions ('avif', then 'webp').
    // The goal is to find the first format for which all image source sizes exist.
    foreach ( array( 'avif', 'webp' ) as $ext ) {
        // Flag to track if all image sizes exist for the current format ($ext).
        // Reset to true for each new format being checked.
        $all_sources_for_current_format_exist = true;

        // Inner loop: Iterate through each image source size (e.g., 'thumbnail', 'medium', 'large') provided in $sources_copy.
        foreach ( $sources_copy as $size => $source ) {
            // Construct the relative path of the original image.
            // This removes the base URL of uploads from the full image URL.
            // e.g., "wp-content/uploads/2023/10/my-image.jpg"
            $relative_path = str_replace( $upload_dir['baseurl'], '', $source['url'] );

            // Construct the absolute server path for the candidate modern image.
            // preg_replace changes the file extension in $relative_path to the current $ext (e.g., '.avif' or '.webp').
            // e.g., "/var/www/html/wp-content/uploads/2023/10/my-image.avif"
            // This path manipulation is considered safe as it's based on trusted WordPress data ($upload_dir, $source['url']).
            $candidate_path = $upload_dir['basedir'] . preg_replace( '/\.[^.]+$/i', '.' . $ext, $relative_path );

            // Check if the candidate modern image file exists on the server.
            if ( ! file_exists( $candidate_path ) ) {
                // If a specific size for the current format (e.g., my-image-thumbnail.avif) doesn't exist,
                // then this format ($ext) is not fully available.
                $all_sources_for_current_format_exist = false;
                // Break from this inner loop (checking sources for the current $ext).
                // No need to check other sizes for this format if one is missing.
                break;
            }
        }

        // After checking all sizes for the current format ($ext):
        // If all sources for the current format exist (e.g., .avif versions for all sizes are present),
        if ( $all_sources_for_current_format_exist ) {
            // Update the original $sources array to use the new image URLs with the modern format extension.
            foreach ( $sources as $size => $source_item ) { // Iterate over original $sources to modify.
                // preg_replace changes the file extension in the source URL to the current $ext.
                // This is considered safe as it's modifying URLs derived from trusted WordPress data.
                $sources[ $size ]['url'] = preg_replace( '/\.[^.]+$/i', '.' . $ext, $source_item['url'] );
            }
            // Once a modern format (e.g., AVIF) is successfully applied for all sizes,
            // break from the outer loop (which checks for 'avif', 'webp').
            // We prefer AVIF over WebP if available, so no need to check for WebP if AVIF worked.
            break;
        }
    }

    // Return the modified $sources array (if a modern format was applied)
    // or the original $sources array if no complete set of modern image formats was found.
    return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'ag_modern_image_formats', 10, 5 );

/**
 * Enqueue lightbox on gallery pages only.
 * Only enqueues the script if the page is singular, has content, and contains a gallery shortcode or block.
 */
function ag_maybe_enqueue_lightbox() {
    // Only proceed if the current view is a singular page (e.g., a post or a page, not an archive).
    if ( is_singular() ) {
        $post = get_post(); // Get the current post object.
        // Ensure the post object is valid and has content to check.
        if ( $post && ! empty( $post->post_content ) ) {
            // Check if the post content contains either a [gallery] shortcode or a core/gallery block.
            // These are common ways WordPress galleries are inserted.
            if ( has_shortcode( $post->post_content, "gallery" ) || has_block( "core/gallery", $post ) ) {
                // If a gallery is found, enqueue the lightbox JavaScript file.
                $lightbox_js_path = '/assets/js/lightbox.min.js'; // Changed to .min.js
                $lightbox_js_full_path = get_template_directory() . $lightbox_js_path;
                $lightbox_js_uri = get_template_directory_uri() . $lightbox_js_path;
                $version = "1.1";

                if ( file_exists( $lightbox_js_full_path ) ) {
                    wp_enqueue_script( "ag-lightbox", $lightbox_js_uri, array(), filemtime( $lightbox_js_full_path ), true );
                } else {
                    // Fallback to non-minified version if .min.js is not found
                    $lightbox_js_fallback_path = str_replace( '.min.js', '.js', $lightbox_js_path );
                    $lightbox_js_fallback_full_path = get_template_directory() . $lightbox_js_fallback_path;
                    $lightbox_js_fallback_uri = get_template_directory_uri() . $lightbox_js_fallback_path;
                    if ( file_exists( $lightbox_js_fallback_full_path ) ) {
                        wp_enqueue_script( "ag-lightbox", $lightbox_js_fallback_uri, array(), filemtime( $lightbox_js_fallback_full_path ), true );
                    }
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'ag_maybe_enqueue_lightbox' );

/**
 * Remove unused WordPress assets to improve performance and reduce clutter.
 */
function ag_cleanup_wp() {
    // Remove WordPress emoji detection script and styles.
    // Useful if emojis are not extensively used or are handled differently, reducing HTTP requests.
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    // Deregister the wp-embed script if oEmbed functionality for embedding other WordPress posts
    // (or this site's posts on other WordPress sites) is not needed. Reduces a script load.
    wp_deregister_script( 'wp-embed' );
}
add_action( 'init', 'ag_cleanup_wp' );

/**
 * Dequeue default block styles to reduce unused CSS.
 *
 * Removing these styles saves several kilobytes of CSS on each request
 * because the theme relies on its own Tailwind styles.
 */
function ag_remove_block_css() {
    // These styles are enqueued by WordPress core for block themes.
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'ag_remove_block_css', 20 );

/**
 * Defer theme script loading.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function ag_defer_scripts( $tag, $handle ) {
    // Check if the current script handle is 'ag-theme' or 'ag-lightbox'.
    if ( 'ag-theme' === $handle || 'ag-lightbox' === $handle ) {
        // Add the 'defer' attribute to the script tag.
        // The defer attribute tells the browser to download the script in parallel with parsing the HTML,
        // and then execute it after the HTML parsing is complete, but before the DOMContentLoaded event.
        // This improves perceived page load time as script loading/execution doesn't block HTML rendering.
        // Deferred scripts are executed in the order they appear in the document.
        return str_replace( ' src', ' defer src', $tag );
    }
    // Return the original tag if the handle doesn't match.
    return $tag;
}
add_filter( 'script_loader_tag', 'ag_defer_scripts', 10, 2 );

/**
 * Adds meta description to the head.
 */
function ag_add_meta_description() {
    $description = '';

    if ( is_singular() ) {
        $post = get_queried_object();
        if ( $post && has_excerpt( $post ) ) {
            $description = get_the_excerpt( $post );
        } elseif ( $post ) {
            $description = wp_trim_words( strip_shortcodes( $post->post_content ), 55, '...' );
        }
    } elseif ( is_front_page() ) {
        $description = get_bloginfo( 'description' );
    } elseif ( is_category() || is_tag() ) {
        $description = term_description();
    } else {
        // Generic fallback
        $description = sprintf(
            // Translators: %1$s: Site name, %2$s: Site tagline
            esc_html__( 'Explore content from %1$s: %2$s', 'andersgoliversen' ),
            get_bloginfo( 'name' ),
            get_bloginfo( 'description' )
        );
    }

    if ( $description ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'ag_add_meta_description' );

/**
 * Custom posts pagination layout.
 * Outputs Previous/Next links at the edges with page numbers aligned
 * depending on current position.
 */
function ag_custom_posts_pagination() {
    global $wp_query;
    $paged = max( 1, get_query_var( 'paged' ) );
    $max   = intval( $wp_query->max_num_pages );

    if ( $max <= 1 ) {
        return;
    }

    $links = paginate_links( [
        'current'   => $paged,
        'total'     => $max,
        'mid_size'  => 2,
        'end_size'  => 1,
        'type'      => 'array',
        'prev_next' => false,
    ] );

    $prev = get_previous_posts_link( __( '&larr; Previous Page', 'andersgoliversen' ) );
    $next = get_next_posts_link( __( 'Next Page &rarr;', 'andersgoliversen' ), $max );

    $justify = 'justify-center';
    if ( 1 === $paged ) {
        $justify = 'justify-start';
    } elseif ( $paged === $max ) {
        $justify = 'justify-end';
    }

    echo '<nav class="my-12" role="navigation">';
    echo '<div class="flex items-center justify-between gap-4">';
    echo '<div class="flex-none">' . ( $prev ? $prev : '' ) . '</div>';
    echo '<div class="flex-1 flex flex-wrap gap-2 ' . esc_attr( $justify ) . '">';
    if ( $links ) {
        foreach ( $links as $link ) {
            echo $link;
        }
    }
    echo '</div>';
    echo '<div class="flex-none text-right">' . ( $next ? $next : '' ) . '</div>';
    echo '</div>';
    echo '</nav>';
}

/**
 * Append the post date to single post content before Jetpack additions.
 *
 * @param string $content The post content.
 * @return string Modified content with publication date appended.
 */
function ag_append_post_date( $content ) {
    if ( is_single() && in_the_loop() && is_main_query() ) {
        $date = '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '" class="block text-sm italic text-neutral-500">' . esc_html( get_the_date( 'F j, Y' ) ) . '</time>';
        $content .= $date;
    }
    return $content;
}
add_filter( 'the_content', 'ag_append_post_date', 18 );

/**
 * Add scaling container classes to gallery figures.
 *
 * Ensures images and captions scale together by attaching
 * Tailwind utility classes to <figure> elements generated
 * by core image and gallery blocks.
 *
 * @param string $block_content The block HTML.
 * @param array  $block         Block metadata.
 * @return string Filtered block HTML.
 */
function ag_group_gallery_figures( $block_content, $block ) {
    if ( isset( $block['blockName'] ) && in_array( $block['blockName'], array( 'core/image', 'core/gallery' ), true ) ) {
        $block_content = preg_replace_callback(
            '/<figure([^>]*)>/i',
            static function ( $matches ) {
                $attrs = $matches[1];
                $classes = 'group transition-transform duration-150 origin-top';
                if ( preg_match( '/class="([^"]*)"/i', $attrs, $m ) ) {
                    $attrs = str_replace( $m[0], 'class="' . $m[1] . ' ' . $classes . '"', $attrs );
                } else {
                    $attrs .= ' class="' . $classes . '"';
                }
                return '<figure' . $attrs . '>';
            },
            $block_content
        );
    }
    return $block_content;
}
add_filter( 'render_block', 'ag_group_gallery_figures', 10, 2 );

