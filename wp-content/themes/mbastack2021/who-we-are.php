<?php
/*
Template Name: who-we-are

common header/navbar
hero - single fixed image with page title overlay
two column text/image with background image + colour
full width video panel
two column text/image with background image + colour
two column image/text with background image + colour
4 column 1 row case study grid
two column image/text with background image + colour
common footer
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  get_template_part( 'blocks/common/page-banner','',['large' => get_field('large'), 'small' => get_field('small'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile'), 'has-video' => get_field('has_video'), 'format' => get_field('video_format'), 'source' => get_field('video_source'), 'gradient_start' => get_field('gradient_start'), 'gradient_end' => get_field('gradient_end') ]);

?>

<?php
  /*
  single column left aligned 10/12 width
  CMX block:
  centre align image with three hotspots, clickung on the hot spots shows a text panel relating to the hotspot, clicking on the 'CXM' label in the image closes the text panels
  Tools panel
    topclip 'TOOLS'
    intro header
    intro copy
    five blocks in two columns
      1 2
      3 4
      5
    each block
      icon / text
  How / nimble
    topclip 'HOW'
    subheader
    intro block (with expand/contract button)
    copy block (accordion)
    follow link bottom, right align
    Group
      MSQ mao
  */
  get_template_part( 'blocks/common/modules','',[]);
?>  


<?php

  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
