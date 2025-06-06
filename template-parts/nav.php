<?php
/**
 * Primary navigation.
 *
 * @package andersgoliversen
 */
?>
<nav role="navigation" aria-label="<?php esc_attr_e( 'Primary', 'andersgoliversen' ); ?>" class="ml-auto">
<?php
wp_nav_menu(
    array(
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'space-x-6 text-base font-medium',
        'fallback_cb'    => 'ag_fallback_nav',
    )
);
?>
</nav>
