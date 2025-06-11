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
    register_nav_menus( array( 'primary' => __( 'Primary', 'andersgoliversen' ) ) );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'responsive-embeds' );
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
    $js  = '/assets/js/theme.js';

    if ( file_exists( $theme_path . $css ) ) {
        wp_enqueue_style( 'ag-theme', $theme_uri . $css, array(), filemtime( $theme_path . $css ) );
    }

    if ( file_exists( $theme_path . $js ) ) {
        wp_enqueue_script( 'ag-theme', $theme_uri . $js, array(), filemtime( $theme_path . $js ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'ag_enqueue_assets' );

/**
 * Output custom root variables.
 */
function ag_output_root_vars() {
    echo '<style>:root{--pagebg:oklch(97% 0.001 106.424);}</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'ag_output_root_vars', 0 );

/**
 * Basic navigation fallback when no menu is assigned.
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
    // Get the WordPress upload directory information.
    $upload_dir = wp_get_upload_dir();

    // Create a copy of the sources array to iterate over, to avoid modifying it directly.
    $sources_copy = $sources;

    // Loop through modern image formats (AVIF and WebP) to check for their availability.
    foreach ( array( 'avif', 'webp' ) as $ext ) {
        $available = true;
        // Check if all sources have a corresponding modern image format version.
        foreach ( $sources_copy as $size => $source ) {
            // Get the relative path of the image.
            $relative_path = str_replace( $upload_dir['baseurl'], '', $source['url'] );
            // Construct the candidate path for the modern image format.
            // This regex replaces the existing file extension with the new one ($ext).
            $candidate_path = $upload_dir['basedir'] . preg_replace( '/\.[^.]+$/i', '.' . $ext, $relative_path );
            // If a candidate file does not exist, this format is not available for all sources.
            if ( ! file_exists( $candidate_path ) ) {
                $available = false;
                break; // Exit the inner loop.
            }
        }

        // If the modern image format is available for all sources, update the original $sources array.
        if ( $available ) {
            foreach ( $sources as $size => $source ) {
                // Replace the original image URL extension with the modern image format extension.
                $sources[ $size ]['url'] = preg_replace( '/\.[^.]+$/i', '.' . $ext, $source['url'] );
            }
            // Once a modern format is successfully applied, no need to check for others.
            break; // Exit the outer loop.
        }
    }

    // Return the modified sources array (or original if no modern formats were found).
    return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'ag_modern_image_formats', 10, 5 );

/**
 * Enqueue lightbox on gallery pages only.
 * Only enqueues the script if the page is singular, has content, and contains a gallery shortcode or block.
 */
function ag_maybe_enqueue_lightbox() {
    // Check if it's a singular page (post, page, or custom post type).
    if ( is_singular() ) {
        $post = get_post();
        // Ensure the post object is valid and has content.
        if ( $post && ! empty( $post->post_content ) ) {
            // Check if the post content contains a gallery shortcode or a core gallery block.
            if ( has_shortcode( $post->post_content, "gallery" ) || has_block( "core/gallery", $post ) ) {
                // Enqueue the lightbox script.
                wp_enqueue_script( "ag-lightbox", get_template_directory_uri() . "/assets/js/lightbox.js", array(), "1.1", true );
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'ag_maybe_enqueue_lightbox' );

/**
 * Remove unused WordPress assets.
 */
function ag_cleanup_wp() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    wp_deregister_script( 'wp-embed' );
}
add_action( 'init', 'ag_cleanup_wp' );

/**
 * Defer theme script loading.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function ag_defer_scripts( $tag, $handle ) {
    if ( 'ag-theme' === $handle || 'ag-lightbox' === $handle ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'ag_defer_scripts', 10, 2 );
