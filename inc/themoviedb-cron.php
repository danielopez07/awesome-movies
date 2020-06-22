<?php
add_action( 'themoviedb_cron', 'themoviedb_cron_func' );


function themoviedb_cron_func() {
  $myfile = fopen( "response.json", "w" ) or die( "Unable to open file!" );
  fwrite( $myfile, '' );
  fclose( $myfile );
  chmod( 'response.json', 0777 );

  $url = "https://api.themoviedb.org/3/configuration?api_key=dd749361bed88e6bbe29fb3e5396489c";
  $config = fetch_API( $url );

  $url = "https://api.themoviedb.org/3/movie/upcoming?api_key=dd749361bed88e6bbe29fb3e5396489c&language=en-US&page=1&region=US";
  $upcomming = fetch_API( $url );
  if ( empty( $upcomming ) || ( isset($upcomming->status_code) ) ) {
    return false;
  }
  else if ( $upcomming->total_results > 10 ) {
    $upcomming = array_slice( $upcomming->results, 0, 2 );
    // $upcomming = array_slice( $upcomming->results, 0, 10 );
  }
  foreach ( $upcomming as $movie ) {
    $movie_saved = save_movie( $movie->id, $config );
  }

  $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
  fwrite( $myfile, json_encode( $config ) );
  fclose( $myfile );
  chmod( 'response.json', 0777 );
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


function save_movie( $id, $config ) {
  $url =  'https://api.themoviedb.org/3/movie/' . $id . '?api_key=dd749361bed88e6bbe29fb3e5396489c&language=en-US';
  $movie_details = fetch_API( $url );

  $poster_url = $config->images->secure_base_url . $config->images->poster_sizes[3] . $movie_details->poster_path;

  // movie trailer
  $videos_url = "https://api.themoviedb.org/3/movie/" . $id . "/videos?api_key=dd749361bed88e6bbe29fb3e5396489c&language=en-US";
  $videos = fetch_API( $videos_url );

  $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
  fwrite( $myfile, json_encode( $videos ) );
  fclose( $myfile );
  chmod( 'response.json', 0777 );

  if ( isset($videos->results) ) {
    foreach ( $videos->results as $video ) {
      $myfile = fopen( "response.json", "a" ) or die( "Unable to open file!" );
      fwrite( $myfile, json_encode( $video ) );
      fclose( $myfile );
      chmod( 'response.json', 0777 );

      if ( $video->type == "Trailer" && $video->site == "YouTube" ) {
        $trailer = $video;
        break;
      }
    }
  }
  $trailer = !empty($trailer) ? 'https://www.youtube.com/watch?v=' . $trailer->key : '';

  // cast
  // reviews

  $movie_post = [
    'post_title' => $movie_details->original_title,
    'guid' => $movie_details->id,
    'post_status' => 'publish',
    'post_type' => 'movie',
    'meta_input' => [
      'movie_trailer' => $trailer,
      'movie_poster' => $poster_url,
      'movie_genre' => json_encode( $movie_details->genres ),
      'alternative_titles' => $movie_details->title,
      'overview' => $movie_details->overview,
      'production_companies' => json_encode( $movie_details->production_companies ),
      'release_date' => $movie_details->release_date,
      'original_language' => $movie_details->original_language,
      'cast' => '',
      'popularity' => $movie_details->popularity,
      'reviews' => ''
    ]
  ];
  wp_insert_post( $movie_post );

  return true;

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

  /* movie_details
  {
    "adult": false,
    "backdrop_path": "/h6SeTbpFRjx5NIppi6h5iXoFzBg.jpg",
    "belongs_to_collection": null,
    "budget": 0,
    "genres": [
      {
        "id": 10752,
        "name": "War"
      },
      {
        "id": 18,
        "name": "Drama"
      },
      {
        "id": 36,
        "name": "History"
      }
    ],
    "homepage": "",
    "id": 531876,
    "imdb_id": "tt3833480",
    "original_language": "en",
    "original_title": "The Outpost",
    "overview": "Based on true events, in this military thriller, a small unit of U.S. soldiers, alone at the remote Combat Outpost Keating, located deep in the valley of three mountains in Afghanistan, battles to defend against an overwhelming force of Taliban fighters in a coordinated attack. The Battle of Kamdesh, as it was known, was the bloodiest American engagement of the Afghan War in 2009 and Bravo Troop 3-61 CAV became one of the most decorated units of the 19-year conflict.",
    "popularity": 31.936,
    "poster_path": "/goEW6QqoFxNI2pfbpVqmXj2WXwd.jpg",
    "production_companies": [],
    "production_countries": [
      {
        "iso_3166_1": "BG",
        "name": "Bulgaria"
      },
      {
        "iso_3166_1": "US",
        "name": "United States of America"
      }
    ],
    "release_date": "2020-07-02",
    "revenue": 0,
    "runtime": 108,
    "spoken_languages": [
      {
        "iso_639_1": "en",
        "name": "English"
      }
    ],
    "status": "In Production",
    "tagline": "",
    "title": "The Outpost",
    "video": false,
    "vote_average": 0,
    "vote_count": 0
  }
  */
}
