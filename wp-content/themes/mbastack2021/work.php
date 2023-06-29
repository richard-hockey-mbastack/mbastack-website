<?php
/*
Template Name: work

common header/navbar
hero - single fixed image with page title overlay
case studies repeater (4 selected items):
	full width with text overlay (only shown on rollover on section of image,mystery meat navigation anyone?)
	[image,client,campaing,synoposis,link] links to case-study page	
common footer

*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */

  get_template_part( 'blocks/common/page-banner','',['large' => get_field('large'), 'small' => get_field('small'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile'), 'has-video' => get_field('has_video'), 'format' => get_field('video_format'), 'source' => get_field('video_source'), 'gradient_start' => get_field('gradient_start'), 'gradient_end' => get_field('gradient_end') ]);

  get_template_part( 'blocks/common/modules','',[]);

  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
