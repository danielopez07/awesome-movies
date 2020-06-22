<article class="card">
  <a href="<?php echo esc_url( get_permalink() ) ?> ">
    <?php
    $img = get_post_meta( get_the_ID(), 'photo' )[0];
    if ( $img )
    echo '<figure class="card-image-actor"><img src="' . $img . '" /></figure>';
    else {
      echo '<figure class="card-image"><img loading="lazy" src="/wp-content/themes/awesome-movies/imgs/default-img.jpg"></figure>';
    }
    ?>

    <div class="content-card-actor">
      <h3><?php the_title() ?></h3>
    </div>

  </a>
</article>
