<?php
/*
Template Name: privacy
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  get_template_part( 'blocks/common/page-banner','',['large' => get_field('large'), 'small' => get_field('small'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile'), 'has-video' => get_field('has_video'), 'format' => get_field('video_format'), 'source' => get_field('video_source'), 'gradient_start' => get_field('gradient_start'), 'gradient_end' => get_field('gradient_end') ] );

// get_template_part( 'blocks/common/modules','',[]);
?>
<section class="module privacy">
	<div class="container">
		<div class="row">
			<div class="col">
				<?php the_content(); ?>			
			</div>
		</div>
	</div>
</section>
<?php
get_footer( null, [] );
}
?>
