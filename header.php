<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-pagebg' ); ?>>
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only" href="#content">Skip to content</a>
<header class="max-w-7xl mx-auto flex items-center py-6 px-4">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/logo.png' ); ?>" alt="" class="mr-2">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-xl font-semibold mr-8">
        <?php bloginfo( 'name' ); ?>
    </a>
    <nav class="flex items-center space-x-6">
        <?php
        $links = [
          [ 'slug' => '/blog/',     'label' => 'Blog',     'check' => is_home() || is_category() ],
          [ 'slug' => '/gallery/',  'label' => 'Gallery',  'check' => is_page( 'gallery' ) ],
          [ 'slug' => '/projects/', 'label' => 'Projects', 'check' => is_page( 'projects' ) ],
          [ 'slug' => '/shop/',     'label' => 'Shop',     'check' => is_page( 'shop' ) ],
          [ 'slug' => '/about/',    'label' => 'About',    'check' => is_page( 'about' ) ],
        ];
        foreach ( $links as $l ) {
            $classes = 'transition-transform duration-150 hover:scale-105 hover:text-neutral-600';
            if ( $l['check'] ) {
                $classes .= ' font-semibold';
            }
            printf(
                '<a href="%s" class="%s">%s</a>',
                esc_url( home_url( $l['slug'] ) ),
                esc_attr( $classes ),
                esc_html( $l['label'] )
            );
        }
        ?>
    </nav>
    <a href="https://www.youtube.com/channel/UCovgolET91fDl9s_K8eSvLw" class="ml-auto" aria-label="YouTube">
        <svg viewBox="0 0 24 24" class="h-8 w-8 fill-red-600" aria-hidden="true">
            <path d="M23 12c0-1.7-.2-3.4-.3-5.1-.2-1.6-1.5-2.8-3.1-3C16.9 3.5 12 3.5 12 3.5s-4.9 0-7.6.4c-1.6.2-2.9 1.4-3.1 3C.2 8.6 0 10.3 0 12s.2 3.4.3 5.1c.2 1.6 1.5 2.8 3.1 3C7.1 20.5 12 20.5 12 20.5s4.9 0 7.6-.4c1.6-.2 2.9-1.4 3.1-3 .2-1.7.3-3.4.3-5.1zM9.7 15.5v-7l6.3 3.5-6.3 3.5z"/>
        </svg>
    </a>
</header>