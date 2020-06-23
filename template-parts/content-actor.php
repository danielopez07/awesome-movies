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
			the_title( '<h1 class="entry-title-actor">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title-actor"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	<?php awesome_movies_post_thumbnail(); ?>

	<!--
		Photo
		Name
		Birthday
		Place of birth
		Deathday (if applies)
		Website (if applies)
		Popularity
		Bio
		Gallery of images (max 10 items)
		List of movies related to the actor, sorted by date displaying: movie poster,
		character name, movie title and release date.
	-->

	<div class="entry-content">
    <?php
    $img = get_post_meta( get_the_ID(), 'photo' )[0];
    if ( $img ) {
    	echo '<figure class=""><img src="' . $img . '" /></figure>';
		}

		$birthday = date( 'jS \of F, Y', strtotime( get_post_meta( get_the_ID(), 'birthday' )[0] ) );
		if ( $birthday ) {
			echo "<p><em>Birthday:</em> " . $birthday . "</p>";
		}

		$place_of_birth = get_post_meta( get_the_ID(), 'place_of_birth' )[0];
		if ( $place_of_birth ) {
			echo "<p><em>Place of birth:</em> " . $place_of_birth . "</p>";
		}

		$deathday = get_post_meta( get_the_ID(), 'deathday' )[0];
		if ( $deathday ) {
			echo "<p><em>Deathday:</em> " . $deathday . "</p>";
		}

		$homepage = get_post_meta( get_the_ID(), 'website' )[0];
		if ( $homepage ) {
			echo "<p><em>Homepage:</em> " . $homepage . "</p>";
		}

		$biography = get_post_meta( get_the_ID(), 'bio' )[0];
		if ( $biography ) {
			echo "<p><em>Biography:</em></p> <p>" . $biography . "</p>";
		}

		$gallery = json_decode( get_post_meta( get_the_ID(), 'gallery' )[0] );
		if ( $gallery && count( $gallery ) ) {
			echo "<div class='gallery-actor'>";
			foreach ( $gallery as $image ) {
				echo "<img src='" . $image . "' class='gallery-item-actor' />";
			}
			echo "</div>";
		}

		$movies_related = json_decode( get_post_meta( get_the_ID(), 'movies_related' )[0] );
		// echo 'error ' . json_last_error();
		// there is a probem with the JSON structure here
		if ( $movies_related && count( $movies_related ) ) {
			echo "<ul>";
			foreach ( $movies_related->cast as $movie ) {
				echo "<li>";
				echo "<em>Original title</em>: " . $movie->original_title . "<br>";
				echo "<em>Character</em>: " . $movie->character . "<br>";
				echo "<em>Release date</em>: " . date( 'jS \of F, Y', strtotime( $movie->release_date ) ) . "<br>";
			}
			echo "</ul>";
		}

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
