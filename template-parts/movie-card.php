<article class="card">
  <a href="<?php echo esc_url( get_permalink() ) ?> ">
    <?php
    $img = get_post_meta( get_the_ID(), 'movie_poster' )[0];
    if ( $img )
    echo '<figure class="card-image"><img src="' . $img . '" /></figure>';
    else {
      echo '<figure class="card-image"><img loading="lazy" src="/wp-content/themes/awesome-movies/imgs/default-img.jpg"></figure>';
    }
    ?>

    <div class="content-card">
      <h3><?php the_title() ?></h3>

      <p class="release-date-card">
        Release date: <?php echo date( 'jS \of F, Y', strtotime( get_post_meta( get_the_ID(), 'release_date' )[0] ) ); ?>
      </p>

      <div class="genres-card">
        <?php
        $movie_genres = json_decode( get_post_meta( get_the_ID(), 'movie_genre' )[0] );
        foreach ( $movie_genres as $genre ) {
          echo '<span class="genre">' . $genre->name . '</span>';
        }
        ?>
      </div>
    </div>

  </a>
</article>
