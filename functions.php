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
    echo '<ul class="space-x-6 text-base font-medium">';
    wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
    echo '</ul>';
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
    $upload_dir = wp_get_upload_dir();

    foreach ( array( 'avif', 'webp' ) as $ext ) {
        $available = true;
        foreach ( $sources as $size => $source ) {
            $relative  = str_replace( $upload_dir['baseurl'], '', $source['url'] );
            $candidate = $upload_dir['basedir'] . preg_replace( '/\.(jpg|jpeg|png)$/i', '.' . $ext, $relative );
            if ( ! file_exists( $candidate ) ) {
                $available = false;
                break;
            }
        }
        if ( $available ) {
            foreach ( $sources as $size => $source ) {
                $sources[ $size ]['url'] = preg_replace( '/\.(jpg|jpeg|png)$/i', '.' . $ext, $source['url'] );
            }
            break;
        }
    }

    return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'ag_modern_image_formats', 10, 5 );

/**
 * Enqueue lightbox on gallery pages only.
 */
function ag_maybe_enqueue_lightbox() {
    if ( is_singular() ) {
        $post = get_post();
        if ( has_shortcode( $post->post_content, "gallery" ) || has_block( "core/gallery", $post ) ) {
            wp_enqueue_script( "ag-lightbox", get_template_directory_uri() . "/assets/js/lightbox.js", array(), "1.0", true );
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
add_action( 'wp_footer', 'ag_cleanup_wp' );

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
