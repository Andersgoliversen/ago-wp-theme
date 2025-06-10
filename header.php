<?php
/**
 * Theme header
 * Adds hover / scale to all menu links and site title.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead"
		class="bg-pagebg/60 backdrop-blur supports-[backdrop-filter]:bg-pagebg/30 shadow-sm">
	<div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

		<?php
		/* ----------  Site title ------------------------------------------------ */
                $title_classes  = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600';
		$title_classes .= is_front_page() ? ' text-xl font-semibold' : ' font-medium';

		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
		   class="<?php echo esc_attr( $title_classes ); ?>">
			<?php bloginfo( 'name' ); ?>
		</a>

                <?php
                /* ----------  Primary menu --------------------------------------- */
                $links = [
                        [ 'slug' => '/blog/',     'label' => 'Blog',     'check' => is_home() || is_category() ],
                        [ 'slug' => '/gallery/',  'label' => 'Gallery',  'check' => is_page( 'gallery' ) ],
                        [ 'slug' => '/projects/', 'label' => 'Projects', 'check' => is_page( 'projects' ) ],
                        [ 'slug' => '/shop/',     'label' => 'Shop',     'check' => is_page( 'shop' ) ],
                        [ 'slug' => '/about/',    'label' => 'About',    'check' => is_page( 'about' ) ],
                ];
                foreach ( $links as $l ) {
                        $classes = 'no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600';
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
		<!-- ----------  YouTube icon link ------------------------------------- -->
                <a href="https://www.youtube.com/@andersgoliversen" target="_blank" rel="noopener"
                   class="no-underline text-inherit decoration-transparent transition-transform duration-150 hover:scale-105 hover:text-neutral-600"
                   aria-label="YouTube channel">
			<svg viewBox="0 0 24 24" class="w-6 h-6 fill-current">
				<path d="M23.5 6.2a2.99 2.99 0 0 0-2.1-2.1C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.6a2.99 2.99 0 0 0-2.1 2.1A31.2 31.2 0 0 0 0 12a31.2 31.2 0 0 0 .5 5.8 2.99 2.99 0 0 0 2.1 2.1c2 .6 9.4.6 9.4.6s7.4 0 9.4-.6a2.99 2.99 0 0 0 2.1-2.1 31.2 31.2 0 0 0 .5-5.8 31.2 31.2 0 0 0-.5-5.8zM9.6 15.5v-7l6.4 3.5-6.4 3.5z"/>
			</svg>
		</a>

        </div>

        <div class="max-w-7xl mx-auto px-6 pb-4 text-center">
                <?php get_search_form(); ?>
        </div>
</header>
