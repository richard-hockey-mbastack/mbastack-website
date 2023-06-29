<?php
/*
single post template for custom post type 'casestudy'
/casestudy/[NAME]/

*/
if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  ?>
  <div class="parallax-window caseStudyBG" data-parallax="scroll" data-image-src="<?php echo get_template_directory_uri(); ?>/assets/img/dots-1.png">
  <?php
  get_template_part( 'blocks/casestudy/modules','',['id' => $post->ID]);
  ?>
  </div>
  <?php
  
  /* PAGE STRUCTURE END */

  get_footer( null, [] );
}
?>
