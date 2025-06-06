<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Tailwind CDN with custom colour -->
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						pagebg: 'oklch(97% 0.001 106.424)'
					}
				}
			}
		}
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-pagebg' ); ?>>
<?php wp_body_open(); ?>

<header class="max-w-7xl mx-auto flex items-center py-6 px-4">
	<!-- site logo -->
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mr-4 shrink-0">
		<?php
		// 64 × 64 favicon-sized logo recommended
		echo '<img src="' . esc_url( get_stylesheet_directory_uri() . '/logo.png' ) . '" alt="" class="h-10 w-10 rounded-full">';
		?>
	</a>

	<!-- site title -->
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-xl font-semibold mr-8">
		<?php bloginfo( 'name' ); ?>
	</a>

	<!-- primary links -->
	<nav class="space-x-6 text-base font-medium">
		<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
		<a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a>
		<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Projects</a>
		<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop</a>
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
	</nav>

	<!-- YouTube icon far right -->
	<a href="https://www.youtube.com/channel/UCovgolET91fDl9s_K8eSvLw"
	   class="ml-auto" aria-label="YouTube">
		<svg viewBox="0 0 24 24" class="h-8 w-8 fill-red-600">
			<path d="M23 12c0-1.7-.2-3.4-.3-5.1-.2-1.6-1.5-2.8-3.1-3C16.9 3.5 12 3.5 12 3.5s-4.9 0-7.6.4c-1.6.2-2.9 1.4-3.1 3C.2 8.6 0 10.3 0 12s.2 3.4.3 5.1c.2 1.6 1.5 2.8 3.1 3C7.1 20.5 12 20.5 12 20.5s4.9 0 7.6-.4c1.6-.2 2.9-1.4 3.1-3 .2-1.7.3-3.4.3-5.1zM9.7 15.5v-7l6.3 3.5-6.3 3.5z"/>
		</svg>
	</a>
</header>
