<?php
/*
Template Name: generic
*/

/*
page banner

page_title_or_intro_text
page_title
intro_text
page_image_desktop
page_image_mobile
has_video ('no','yes')
video_format ('vimeo','youtube','inline')
video_source
*/
if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  get_template_part( 'blocks/common/page-banner','',['large' => get_field('large'), 'small' => get_field('small'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile'), 'has-video' => get_field('has_video'), 'format' => get_field('video_format'), 'source' => get_field('video_source') ]);

?>
<?php
  get_template_part( 'blocks/common/modules','',[]);
  
  // the_content();


  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
