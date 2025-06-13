<?php
/**
 * Custom search form.
 */
?>
<form role="search" method="get" class="w-full max-w-md mx-auto flex" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="search-field" class="sr-only"><?php esc_html_e( 'Search for:', 'andersgoliversen' ); ?></label>
    <input type="search" id="search-field" name="s" class="flex-grow border border-neutral-300 rounded-l px-3 py-2" placeholder="<?php esc_attr_e( 'Search …', 'andersgoliversen' ); ?>" value="<?php echo get_search_query(); ?>" />
    <button type="submit" id="search-submit" class="bg-neutral-600 text-white rounded-r px-4 py-2 transition-colors transition-transform duration-150 hover:bg-neutral-400 hover:scale-105">
        <!-- Magnifying glass icon starts here -->
        <svg class="w-5 h-5 stroke-current" viewBox="0 0 24 24" fill="none" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <!-- Magnifying glass glass (circle) -->
            <circle cx="10" cy="10" r="8" />
            <!-- Magnifying glass handle -->
            <line x1="21" y1="21" x2="14.5" y2="14.5" stroke-linecap="butt" />
        </svg>
        <!-- Magnifying glass icon ends here -->
        <span class="sr-only"><?php esc_html_e( 'Search', 'andersgoliversen' ); ?></span>
    </button>
</form>
