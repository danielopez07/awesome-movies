<?php
/**
 * Template part for displaying movies
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Awesome_Movies
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	<?php awesome_movies_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		echo "<pre>";
		var_dump( get_post_meta( get_the_ID() ) );
		echo "</pre>";

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'awesome-movies' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php awesome_movies_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
