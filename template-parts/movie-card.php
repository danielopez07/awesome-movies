<!-- <div class="card">
  <?php
  // awesome_movies_post_thumbnail();
  the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></3>' );
  ?>
</div> -->

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
    </a>

    <div class="content-card">
        <a href="<?php echo esc_url( get_permalink() ) ?> ">
            <h3><?php the_title() ?></h3>
        </a>
        <!-- <div class="categories-card">
            <span class="screen-reader-text"><?php _e( 'Categories', 'twentytwenty' ); ?></span>
            <?php the_category( ', ' ); ?>
        </div>
        <a href="<?php echo esc_url( get_permalink() ) ?> ">
            <div class="excerpt-card">
                <?php the_excerpt(); ?>
            </div>
        </a> -->
    </div>
</article>
