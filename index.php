<?php get_header(); ?>

<main class="max-w-3xl mx-auto px-4 py-12 space-y-12">
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		the_title( '<h2 class="text-2xl font-semibold mb-4">', '</h2>' );
		the_content();
	endwhile;
else :
	echo '<p>No posts found.</p>';
endif;
?>
</main>

<?php get_footer(); ?>
