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


	<!--
	  Movie title
	  Movie trailer
	  Movie poster
	  Movie genre
	  Alternative titles
	  Overview
	  Production companies
	  Release date
	  Original language
	  Cast (Linked to detail page)
	  Popularity
	  Reviews
	  List of similar movies
	-->

	<div class="entry-content">
		<?php
		$trailer = get_post_meta( get_the_ID(), 'movie_trailer' )[0];
		if ( $trailer )
			echo '<iframe width="1139" height="480" src="https://www.youtube.com/embed/' . $trailer . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

    $img = get_post_meta( get_the_ID(), 'movie_poster' )[0];
    if ( $img )
    echo '<figure class="movie-poster"><img src="' . $img . '" /></figure>';
    else {
      echo '<figure class="movie-poster"><img loading="lazy" src="/wp-content/themes/awesome-movies/imgs/default-img.jpg"></figure>';
    }
    ?>

    <div class="genres-card">
      <?php
      $movie_genres = json_decode( get_post_meta( get_the_ID(), 'movie_genre' )[0] );
      foreach ( $movie_genres as $genre ) {
        echo '<span class="genre">' . $genre->name . '</span>';
      }
      ?>
    </div>

		<?php
		$alternative_title = get_post_meta( get_the_ID(), 'alternative_titles' )[0];
		if ( $alternative_title ) {
			echo "<p><em>Alternative title:</em> " . $alternative_title . "</p>";
		}

		$overview = get_post_meta( get_the_ID(), 'overview' )[0];
		if ( $overview ) {
			echo "<p><em>Overview:</em> " . $overview . "</p>";
		}

		$production_companies = json_decode( get_post_meta( get_the_ID(), 'production_companies' )[0] );
		if ( count( $production_companies ) ) {
			echo "<p><em>Production companies:</em></p>";
			echo "<ul>";
			foreach ($production_companies as $company) {
				echo "<li>" . $company->name . "</li>";
			}
			echo "</ul>";
		}
		?>

    <p class="release-date-card">
      <em>Release date:</em> <?php echo date( 'jS \of F, Y', strtotime( get_post_meta( get_the_ID(), 'release_date' )[0] ) ); ?>
    </p>

		<?php
		$original_language = get_post_meta( get_the_ID(), 'original_language' )[0];
		if ( $original_language ) {
			echo "<p><em>Original language:</em> " . $original_language . "</p>";
		}

		// **** Cast (Linked to detail page) ****
		$cast = json_decode( get_post_meta( get_the_ID(), 'cast' )[0] );
		if ( count( $cast ) ) {
			echo "<p><em>Cast:</em></p>";
			echo "<ul>";
			foreach ( $cast as $actor ) {
				echo "<li><em>Character:</em> " . $actor->character . ". | <em>Name:</em> " . $actor->name . "</li>";
			}
			echo "</ul>";
		}

		$popularity = get_post_meta( get_the_ID(), 'popularity' )[0];
		if ( $popularity ) {
			echo "<p><em>Popularity:</em> " . $popularity . "</p>";
		}

		$reviews = json_decode( get_post_meta( get_the_ID(), 'reviews' )[0] );
		if ( !empty( $reviews ) && count( $reviews ) ) {
			echo "<p><em>Reviews:</em></p>";
			echo "<ul>";
			foreach ( $reviews as $review ) {
				echo "<li>" . $review->content . "</li>";
			}
			echo "</ul>";
		}

	  $similar_movies = get_post_meta( get_the_ID(), 'similar_movies' )[0];
		if ( !empty( $similar_movies ) ) {
			echo "<p><em>Similar movies:</em> " . $similar_movies . "</p>";
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
