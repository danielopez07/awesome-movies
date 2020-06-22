<section class="top-actors">
  <h2 class="entry-title">Top actors</h2>

  <?php
  $the_query = new WP_Query([
    'post_type' => 'actor',
    'nopaging' => true
  ]);
  ?>

  <div class="cards">
    <?php
    while ( $the_query->have_posts() ) {
      $the_query->the_post();

      the_title();
      // get_template_part( 'template-parts/movie-card' );
    }
    ?>
  </div>
</section>
