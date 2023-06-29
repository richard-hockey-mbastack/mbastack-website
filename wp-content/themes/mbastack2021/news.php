<?php
/*
Template Name: news

common header/navbar
hero - single fixed image with page title overlay

combined news / blog posts section

** similar to home page panel 
differences:
	no filter
	1, lead, 3 follow items
	no load more
	
Title: 'NEWS'
	filter (category) : environment,awards,insights,data,cxm,film,industry
	1 lead items and 6 follow itams
	lead items:
	6/12 , 6/12 or 7/12 , 5/12 image / text
	text
		date --- category
		item title
		item synopsis/summary
		link: either to external news erticle or blog post
			link caption / URL / target

		follow items 33.3% / 33.3% / 33.33%
			image
			date --- category
			item title
			item synopsis/summary
			link: either to external news erticle or blog post
				link caption / URL / target

	load more button: loads 3 / 6 (multiple of 3) otems

Assign multiple categories to an item?


common footer

links to post / blog item ?
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */

	  get_template_part( 'blocks/common/page-banner','',['large' => get_field('large'), 'small' => get_field('small'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile'), 'has-video' => get_field('has_video'), 'format' => get_field('video_format'), 'source' => get_field('video_source'), 'gradient_start' => get_field('gradient_start'), 'gradient_end' => get_field('gradient_end') ]);

	get_template_part( 'blocks/common/modules','',[]);

?>  


<?php

  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
