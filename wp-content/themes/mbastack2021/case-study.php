<?php
/*
Template Name: case-study

common header/navbar
hero - single fixed image with page title overlay
common footer

*/
if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */

?>  
<h2 style="color:#000;">case-study.php</h2>

<?php

  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
