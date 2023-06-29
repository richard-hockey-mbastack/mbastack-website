<?php
/*
*/

$desktop = $args['desktop'];
$mobile = $args['mobile'];
if (empty($mobile)) :
$mobile = $desktop;
endif;

$desktopURL = ( !empty($desktop) ) ? wp_get_attachment_image_src($desktop, 'full')[0] : '';
$mobileURL = ( !empty($mobile) ) ? wp_get_attachment_image_src($mobile, 'full')[0] : '';
?>
<section class="module fullWidthImage">
		<figure>
			<img alt="" src="<?php echo $desktopURL; ?>" class="desktop">
			<img alt="" src="<?php echo $desktopURL; ?>" class="mobile">
		</figure>
</section>