<?php
add_action( 'themoviedb_cron', 'themoviedb_cron_func' );


define( 'API_URL', 'https://api.themoviedb.org/3/' );
define( 'API_KEY', 'dd749361bed88e6bbe29fb3e5396489c' );
define( 'NUMBER', 10 );


function themoviedb_cron_func() {
  $url = API_URL . "configuration?api_key=" . API_KEY;
  $config = fetch_API( $url );

  $myfile = fopen( "response.json", "w" ) or die( "Unable to open file!" );
  fwrite( $myfile, '-> ' );
  fclose( $myfile );
  chmod( 'response.json', 0777 );

  $url = API_URL . "movie/upcoming?api_key=" . API_KEY . "&language=en-US&page=1&region=US";
  $upcoming = fetch_and_save( $url, 'movie', 'upcoming', $config, 10 );

  $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
  fwrite( $myfile, ' UPCOMING ' );
  fwrite( $myfile, json_encode( $upcoming ) );
  fclose( $myfile );
  chmod( 'response.json', 0777 );

  $url = API_URL . "movie/popular?api_key=" . API_KEY . "&language=en-US&page=1&region=US";
  $movies = fetch_and_save( $url, 'movie', 'popular', $config, 10 );

  $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
  fwrite( $myfile, ' MOVIES ' );
  fwrite( $myfile, json_encode( $movies ) );
  fclose( $myfile );
  chmod( 'response.json', 0777 );

  $url = API_URL . "person/popular?api_key=" . API_KEY . "&language=en-US&page=1&region=US";
  $people = fetch_and_save( $url, 'person', false, $config, 10 );

  $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
  fwrite( $myfile, ' PEOPLE ' );
  fwrite( $myfile, json_encode( $people ) );
  fclose( $myfile );
  chmod( 'response.json', 0777 );

  // save movies' cast
}

function fetch_and_save( $url, $movie_or_person, $movie_type, $config, $number ) {
  $items = fetch_API( $url );
  if ( empty( $items ) || ( isset($items->status_code) ) ) {
    return false;
  }
  else if ( $items->total_results > 10 ) {
    $items = array_slice( $items->results, 0, 10 );
  }
  foreach ( $items as $item ) {
    if ( $movie_or_person == 'movie' )
      $movie_saved = save_movie( $item->id, $config, $movie_type );
    else if ( $movie_or_person == 'person' )
      $person_saved = save_person( $item->id, $config );
  }
  return true;
}


function save_person( $id, $config ) {
  $url =  API_URL . "person/" . $id . "?api_key=" . API_KEY . "&language=en-US";
  $person_details = fetch_API( $url );

  $photo = $config->images->secure_base_url . $config->images->profile_sizes[1] . $movie_details->poster_path;

  $person_post = [
    'post_title' => $person_details->name,
    'post_status' => 'publish',
    'post_type' => 'actor',
    'meta_input' => [
      'id' => $id,
      'photo' => $person_details->profile_path,
      'birthday' => $person_details->birthday,
      'place_of_birth' => $person_details->place_of_birth,
      'deathday' => $person_details->deathday,
      'website' => $person_details->homepage,
      'popularity' => $person_details->popularity,
      'bio' => $person_details->biography,
      'gallery' => [], // max 10 items
      'movies_related' => [] // sorted by date displaying: movie poster, character name, movie title and release date.
    ]
  ];
  $post_id = wp_insert_post( $person_post );
  if ( !is_wp_error( $post_id ) ) {
    return true;
  } else {
    //there was an error in the post insertion
    return $post_id->get_error_message();
  }
}


function save_movie( $id, $config, $movie_type  ) {
  $url =  API_URL . "movie/" . $id . "?api_key=" . API_KEY . "&language=en-US";
  $movie_details = fetch_API( $url );


    $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
    fwrite( $myfile, ' SAVE MOVIE ' );
    fwrite( $myfile, json_encode( $movie_details ) );
    fclose( $myfile );
    chmod( 'response.json', 0777 );

  $trailer = get_trailer( $id );
  $poster_url = $config->images->secure_base_url . $config->images->poster_sizes[3] . $movie_details->poster_path;
  $alternative_titles = get_alternative_titles( $id );
  $cast = get_cast( $id, $config );
  $reviews = get_reviews( $id );
  $similar_movies = get_similar_movies( $id );

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
      'similar_movies' => $similar_movies,
      'movie_type' => $movie_type
    ]
  ];
  $post_id = wp_insert_post( $movie_post );
  if ( !is_wp_error( $post_id ) ) {
    return true;
  } else {
    //there was an error in the post insertion
    return $post_id->get_error_message();
  }
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
  $trailer = !empty( $trailer ) ? $trailer->key : '';
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

function get_similar_movies( $id ) {
  $similar_movies_url = API_URL . "movie/" . $id . "/similar?api_key=" . API_KEY . "&language=en-US&page=1";
  $similar_movies = fetch_API( $similar_movies_url );
  if ( !empty( $similar_movies ) && isset( $similar_movies->results ) && count( $similar_movies->results ) ) {
    return $similar_movies->results[0]->title . " | " . $similar_movies->results[1]->title;
  }
  return "";
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
