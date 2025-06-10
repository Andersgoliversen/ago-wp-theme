<footer class="max-w-7xl mx-auto py-8 px-4 text-sm text-gray-500 text-center">
    <nav class="mb-4 flex justify-center space-x-4">
        <?php
        $links = [
            [ 'slug' => '/blog/',     'label' => 'Blog' ],
            [ 'slug' => '/gallery/',  'label' => 'Gallery' ],
            [ 'slug' => '/projects/', 'label' => 'Projects' ],
            [ 'slug' => '/shop/',     'label' => 'Shop' ],
            [ 'slug' => '/about/',    'label' => 'About' ],
        ];
        ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600">
            <?php bloginfo( 'name' ); ?>
        </a>
        <?php foreach ( $links as $l ) : ?>
            <a href="<?php echo esc_url( home_url( $l['slug'] ) ); ?>"
               class="no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600">
                <?php echo esc_html( $l['label'] ); ?>
            </a>
        <?php endforeach; ?>
        <a href="https://www.youtube.com/@andersgoliversen" target="_blank" rel="noopener"
           class="no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600"
           aria-label="YouTube channel">
            <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current">
                <path d="M23.5 6.2a2.99 2.99 0 0 0-2.1-2.1C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.6a2.99 2.99 0 0 0-2.1 2.1A31.2 31.2 0 0 0 0 12a31.2 31.2 0 0 0 .5 5.8 2.99 2.99 0 0 0 2.1 2.1c2 .6 9.4.6 9.4.6s7.4 0 9.4-.6a2.99 2.99 0 0 0 2.1-2.1 31.2 31.2 0 0 0 .5-5.8 31.2 31.2 0 0 0-.5-5.8zM9.6 15.5v-7l6.4 3.5-6.4 3.5z"/>
            </svg>
        </a>
    </nav>
    © <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>
</footer>

<?php wp_footer(); ?>
</body>
</html>
