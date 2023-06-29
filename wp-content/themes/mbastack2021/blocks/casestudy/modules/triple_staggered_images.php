<?php
/*
module: casestudy/modules/triple_staggered_images.php
source/scss/casestudy/modules/triple_staggered_images.scss -> assets/css/styles.css
*/

/*
module_css_class
bg1
bg_bottom_margin
bg_pullup
layout ('left','right')
mobile_layout ('column','row')
img1
caption1
img2
caption2
img3
caption3
*/
$moduleIdentifier = $args['module_identifier'];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

$layout = get_sub_field('layout');
$image1ID = get_sub_field('img1');
$Image1URL = ( !empty($image1ID) ) ? wp_get_attachment_image_src($image1ID, 'full')[0] : '';
$caption1 = get_sub_field('caption1');
$image2ID = get_sub_field('img2');
$Image2URL = ( !empty($image2ID) ) ? wp_get_attachment_image_src($image2ID, 'full')[0] : '';
$caption2 = get_sub_field('caption2');
$image3ID = get_sub_field('img3');
$Image3URL = ( !empty($image3ID) ) ? wp_get_attachment_image_src($image3ID, 'full')[0] : '';
$caption3 = get_sub_field('caption3');
?>
<style>
	#<?php echo $moduleIdentifier; ?>{
		z-index: <?php echo $args['z']; ?>;
	}
	#<?php echo $moduleIdentifier; ?> .bg1{
		background-color: <?php echo $bg1; ?>;
		top: <?php echo $bg_pullup; ?>;
		height: calc(100% - <?php echo $bg_bottom_margin; ?>);
	}
</style>
<section class="module case-study-triple_staggered_images <?php echo $layout; ?> <?php if ( !empty(get_sub_field('module_css_class')) ) { echo get_sub_field('module_css_class'); } ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row">
			<div class="col-4">
				<figure><img alt="<?php echo $caption1; ?>" src="<?php echo $Image1URL; ?>"></figure>
			</div>
			<div class="col-4">
				<figure><img alt="<?php echo $caption2; ?>" src="<?php echo $Image2URL; ?>"></figure>
			</div>
			<div class="col-4">
				<figure><img alt="<?php echo $caption3; ?>" src="<?php echo $Image3URL; ?>"></figure>
			</div>
		</div>
	</div>
</section>