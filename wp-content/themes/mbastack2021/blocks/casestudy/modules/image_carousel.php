<?php
/*
module: casestudy/modules/image_carousel.php
source/scss/casestudy/modules/image_carousel.scss -> assets/css/styles.css
*/

/*
module_css_class
bg1
bg_bottom_margin
bg_pullup

images[
	image
	caption
]
*/
$moduleIdentifier = $args['module_identifier'];
$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';
$images = get_sub_field('images');
// $leftImage = ( !empty($left_column['image']) ) ? wp_get_attachment_image_src($left_column['image'], 'full')[0] : '';

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
<section class="module case-study-image_carousel <?php if ( !empty(get_sub_field('module_css_class')) ) { echo get_sub_field('module_css_class'); } ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="icfwop">
					<div class="imageCarouselSlickSlider">
						<?php foreach( $images AS $index => $image ) :
							$imageURL = ( !empty($image['image']) ) ? wp_get_attachment_image_src($image['image'], 'full')[0] : '';
						?>
							<div class="item">
								<figure>
									<img alt="<?php echo $image['caption']; ?>" src="<?php echo $imageURL; ?>">
								</figure>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>