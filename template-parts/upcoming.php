<section class="upcoming">
  <h2 class="entry-title">Upcoming</h2>

  <?php
  $the_query = new WP_Query([
    'post_type' => 'movie',
    'nopaging' => true,
    'meta_key' => 'movie_type',
    'meta_value' => 'upcoming'
  ]);
  ?>

  <div class="cards">
    <?php
    while ( $the_query->have_posts() ) {
      $the_query->the_post();

      get_template_part( 'template-parts/movie-card' );
    }
    ?>
  </div>
</section>
