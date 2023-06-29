<?php
/*
Template Name: post
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  ?>
  <h2 style="color:#000;">POST.php</h2>
  <?php
  the_content();


  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
