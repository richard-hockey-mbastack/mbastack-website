<?php
/*
module: casestudy/modules/fullwidth_image.php
source/scss/casestudy/modules/fullwidth_image.scss -> assets/css/styles.css
*/

/*
image (ID)
caption
*/
$moduleIdentifier = $args['module_identifier'];
$imageData = ( !empty(get_sub_field('image')) ) ? wp_get_attachment_image_src(get_sub_field('image'), 'full') : [];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

if( count($imageData) > 0 ) {
	$imageSrc = $imageData[0];
	$imageWidth = $imageData[1];
	$imageHeight = $imageData[2];
}

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


<section class="module case-study-fullwidth_image" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row">
		<div class="col">
			<figure>
				<img alt="<?php echo get_sub_field('caption'); ?>" src="<?php echo $imageSrc; ?>" width="<?php echo $imageWidth; ?>" height="<?php echo $imageHeight; ?>">
			</figure>
			<p class="caption"><?php echo get_sub_field('caption'); ?></p>
		</div>
		</div>
	</div>
</section>