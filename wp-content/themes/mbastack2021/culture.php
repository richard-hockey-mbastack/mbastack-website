<?php
/*
Template Name: culture
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  get_template_part( 'blocks/common/page-banner','',['type' => get_field('page_title_or_intro_text'), 'title' => get_field('page_title'), 'title' => get_field('intro_text'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile') ]);

  /*
  single column header background-color: #0d0127, white text
  two column text/image fillColumn textLeft background-color: #0d0127, white text
  two column image/text fillColumn imageLeft  background-color: #0d0127, white text
  two column text/image fillColumn textLeft  background-color: #0d0127, white text
  two column text/image white background, #0d0127 text
  new version of People block with two column header section
  full width image with header, Linked? 'diversity'
  full width image with header, Linked? 'neutral'
  */
  get_template_part( 'blocks/common/modules','',[]);

  // the_content();

  /* PAGE STRUCTURE END */

  get_footer( null, [] );
}
?>
