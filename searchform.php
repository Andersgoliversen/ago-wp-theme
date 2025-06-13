<?php
/**
 * Custom search form.
 */
?>
<form role="search" method="get" class="w-full max-w-md mx-auto flex flex-row items-stretch" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" id="search-field" name="s" class="flex-grow border border-neutral-300 rounded-l px-3 py-2" placeholder="<?php esc_attr_e( 'Search …', 'andersgoliversen' ); ?>" value="<?php echo get_search_query(); ?>" />
    <button type="submit" id="search-submit" class="bg-neutral-600 text-white rounded-r px-4 py-2 transition-colors transition-transform duration-150 hover:bg-neutral-400 hover:scale-105 active:bg-neutral-700 active:scale-95 ag-interactive flex items-center justify-center"><!-- Darken and shrink when pressed -->
        <!-- Magnifying glass icon starts here -->
        <svg class="w-5 h-5 stroke-current ag-icon" viewBox="0 0 24 24" fill="none" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <!-- Magnifying glass glass (circle) -->
            <circle cx="10" cy="10" r="8" />
            <!-- Magnifying glass handle -->
            <line x1="22" y1="22" x2="13" y2="13" stroke-linecap="butt" />
        </svg>
        <!-- Magnifying glass icon ends here -->
        <span class="sr-only"><?php esc_html_e( 'Search', 'andersgoliversen' ); ?></span>
    </button>
    <label for="search-field" class="sr-only"><?php esc_html_e( 'Search for:', 'andersgoliversen' ); ?></label>
    <p id="search-warning" class="hidden mt-2 text-neutral-800 bg-white px-2 py-1 rounded shadow">Please fill out this field.</p>
</form>
