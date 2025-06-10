<?php
/**
 * Custom search form.
 */
?>
<form role="search" method="get" class="w-full max-w-md mx-auto flex" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="s" class="sr-only"><?php esc_html_e( 'Search for:', 'andersgoliversen' ); ?></label>
    <input type="search" id="s" name="s" class="flex-grow border border-neutral-300 rounded-l px-3 py-2" placeholder="<?php esc_attr_e( 'Search …', 'andersgoliversen' ); ?>" value="<?php echo get_search_query(); ?>" />
    <button type="submit" class="bg-neutral-200 text-neutral-800 rounded-r px-4 py-2">
        <?php esc_html_e( 'Search', 'andersgoliversen' ); ?>
    </button>
</form>
