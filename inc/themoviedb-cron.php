<?php
add_action( 'themoviedb_cron', 'themoviedb_cron_func' );


define( 'API_URL', 'https://api.themoviedb.org/3/' );
define( 'API_KEY', 'dd749361bed88e6bbe29fb3e5396489c' );


function themoviedb_cron_func() {
  $url = API_URL . "configuration?api_key=" . API_KEY;
  $config = fetch_API( $url );

  $url = API_URL . "movie/upcoming?api_key=" . API_KEY . "&language=en-US&page=1&region=US";
  $upcoming = fetch_API( $url );
  if ( empty( $upcoming ) || ( isset($upcoming->status_code) ) ) {
    return false;
  }
  else if ( $upcoming->total_results > 10 ) {
    $upcoming = array_slice( $upcoming->results, 0, 10 );
  }
  
  foreach ( $upcoming as $movie ) {
    $movie_saved = save_movie( $movie->id, $config, true );
  }
}


function fetch_API( $url ) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
  ));

  $response = curl_exec($curl);

  curl_close($curl);

  return json_decode( $response );
}


function save_movie( $id, $config, $upcoming  ) {
  $url =  API_URL . "movie/" . $id . "?api_key=" . API_KEY . "&language=en-US";
  $movie_details = fetch_API( $url );

  $trailer = get_trailer( $id );
  $poster_url = $config->images->secure_base_url . $config->images->poster_sizes[3] . $movie_details->poster_path;
  $alternative_titles = get_alternative_titles( $id );
  $cast = get_cast( $id, $config );
  $reviews = get_reviews( $id );

  $movie_post = [
    'post_title' => $movie_details->original_title,
    'post_status' => 'publish',
    'post_type' => 'movie',
    'meta_input' => [
      'id' => $id,
      'movie_trailer' => $trailer,
      'movie_poster' => $poster_url,
      'movie_genre' => json_encode( $movie_details->genres ),
      'alternative_titles' => $alternative_titles,
      'overview' => $movie_details->overview,
      'production_companies' => json_encode( $movie_details->production_companies ),
      'release_date' => $movie_details->release_date,
      'original_language' => $movie_details->original_language,
      'cast' => $cast,
      'popularity' => $movie_details->popularity,
      'reviews' => $reviews,
      'upcoming' => $upcoming
    ]
  ];
  $post_id = wp_insert_post( $movie_post );
  if ( !is_wp_error( $post_id ) ) {
    return true;
  } else {
    //there was an error in the post insertion
    return $post_id->get_error_message();
  }

  /*
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
  */
}


function get_trailer($id) {
  $videos_url = API_URL . "movie/" . $id . "/videos?api_key=" . API_KEY . "&language=en-US";
  $videos = fetch_API( $videos_url );
  if ( isset($videos->results) ) {
    foreach ( $videos->results as $video ) {
      if ( $video->type == "Trailer" && $video->site == "YouTube" ) {
        $trailer = $video;
        break;
      }
    }
  }
  $trailer = !empty( $trailer ) ? 'https://www.youtube.com/watch?v=' . $trailer->key : '';
  return $trailer;
}


function get_alternative_titles( $id ) {
  $alternative_titles_url = API_URL . "movie/" . $id . "/alternative_titles?api_key=" . API_KEY;
  $alternative_titles = fetch_API( $alternative_titles_url );
  $alternative_titles = !empty( $alternative_titles ) && isset( $alternative_titles->titles ) ? $alternative_titles->titles : [];
  if( count( $alternative_titles ) ) {
    foreach ( $alternative_titles as $alternative_title ) {
      if ( $alternative_title->iso_3166_1 == 'US' ) {
        $title = $alternative_title->title;
        break;
      }
    }
  }
  $title = !empty( $title ) ? $title : '';
  return $title;
}


function get_cast( $id, $config ) {
  $cast_url = API_URL . "movie/" . $id . "/credits?api_key=" . API_KEY;
  $cast = fetch_API( $cast_url );
  $cast = !empty( $cast ) && isset( $cast->cast ) ? $cast->cast : [];
  if( count( $cast ) ) {
    foreach ( $cast as &$actor ) {
      $actor->profile_path = $config->images->secure_base_url . $config->images->profile_sizes[1] . $actor->profile_path;
    }
  }
  return json_encode( $cast );
}


function get_reviews( $id ) {
  $reviews_url = API_URL . "movie/" . $id . "/reviews?api_key=" . API_KEY . "&language=en-US&page=1";
  $reviews = fetch_API( $reviews_url );
  $reviews = !empty( $reviews ) && isset( $reviews->results ) ? $reviews->results : [];
  return json_encode( $reviews );
}
