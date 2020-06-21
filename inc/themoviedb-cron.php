<?php
add_action( 'wpb_custom_cron', 'wpb_custom_cron_func' );

function wpb_custom_cron_func() {
  $my_post = array(
    'post_title'    => 'My post',
    'post_content'  => 'This is my post.',
    'post_status'   => 'publish',
    'post_author'   => 1
  );

  // Insert the post into the database.
  wp_insert_post( $my_post );

  $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
  $txt = "John Doe\n";
  fwrite($myfile, $txt);
  $txt = "Jane Doe\n";
  fwrite($myfile, $txt);
  fclose($myfile);
}
