<?php
/*
Modules flexuble content template for layout: full_width_image
blocks/commom/modules/full_width_image.php
/source/scss/common/
*/

/*
has_header ('no','yes')
headet_text
header_colour
header_opacity
desktop_image (image ID)
mobile_image (image ID)
control_size_by ('natural','aspect','height')
aspect_ratio 
fixed_height
has_copy ('no','yes')
copy_header
copy_body
copy_colour ('black','white')
has_link  ('no','yes')
copy_link_text
copy_link_url
copy_link_target ('self','blank')
*/

//echo "<pre>".print_r(get_row(),true)."</pre>";

$moduleIdentifier = $args['module_identifier'];

$desktopURL = '';
$mobileURL = '';

$sizeControl = get_sub_field('control_size_by');
$aspect = ( $sizeControl === 'aspect') ? get_sub_field('aspect_ratio') : false;
$fixedheight = ( $sizeControl === 'height') ? get_sub_field('fixed_height') : false;

$desktop = get_sub_field('desktop_image');
$mobile = get_sub_field('mobile_image');
if (empty($mobile)) :
	$mobile = $desktop;
endif;

$desktopURL = ( !empty($desktop) ) ? wp_get_attachment_image_src($desktop, 'full')[0] : '';
$mobileURL = ( !empty($mobile) ) ? wp_get_attachment_image_src($mobile, 'full')[0] : '';

$hasHeader = (get_sub_field('desktop_image') === 'yes');
$hasCopy = (get_sub_field('has_copy') === 'yes');
$hasCopyLink = (get_sub_field('has_link') === 'yes');
$copyLinkNewWindow = (get_sub_field('copy_link_target') === 'blank');
?>

<style>
<?php
switch ( $sizeControl ) {
	case 'natural' : {
?>
#<?php echo $moduleIdentifier; ?>.noCopy.natural{
}
<?php
	} break; 
	case 'aspect' : {
?>
#<?php echo $moduleIdentifier; ?>.noCopy.aspect{
	padding-top: <?php echo $aspect; ?>;
}
<?php
	} break; 
	case 'height' : {
?>
#<?php echo $moduleIdentifier; ?>.noCopy.height{
	height: <?php echo $fixedheight; ?>;
}
<?php
	} break; 
}
?>
<?php if ( get_sub_field('has_header') === 'yes') : ?>
#<?php echo $moduleIdentifier; ?> h2{
	color: <?php echo get_sub_field('header_colour'); ?>;
	opacity: <?php echo get_sub_field('header_opacity'); ?>;
}
<?php endif; ?>
</style>
<?php if ( !$hasCopy ) : ?>
	<section class="module fullWidthImage <?php echo $sizeControl; ?> noCopy parallax-window" data-parallax="scroll" data-image-src="<?php echo $desktopURL; ?>" id="<?php echo $moduleIdentifier; ?>"></section>
<?php else : ?>
	<section class="module fullWidthImage <?php echo $sizeControl; ?> <?php if ( $hasCopy ) : ?>hasCopy<?php else : ?>noCopy<?php endif; ?>" id="<?php echo $moduleIdentifier; ?>">

		<?php if ( get_sub_field('has_header') === 'yes') : ?>
		<header class="topclip">
			<div class="container">
				<div>
					<h2><?php echo get_sub_field('header_text'); ?></h2>
				</div>
			</div>
		</header>
		<?php endif; ?>

		<?php if ( $hasCopy ) : ?>
		<div class="ol <?php echo get_sub_field('copy_colour'); ?>">
			<div class="container">
				<dv class="row">
					<div class="col-lg-8 offset-lg-4">
						<h3><?php echo get_sub_field('copy_header'); ?></h3>
						<p><?php echo get_sub_field('copy_body'); ?></p>

						<?php if ( $hasCopy ) : ?>
						<a href="<?php echo get_sub_field('copy_link_url'); ?>" class="arrowLink <?php echo get_sub_field('copy_colour'); ?>"  target="_blank"><?php echo get_sub_field('copy_link_text'); ?></a>
						<?php endif; ?>

					</div>
				</dv>
			</div>
		</div>
		<?php endif; ?>

		<div class="position">
			<figure>
				<img alt="" src="<?php echo $desktopURL; ?>" class="desktop">
				<img alt="" src="<?php echo $mobileURL; ?>" class="mobile">
			</figure>
		</div>

	</section>
<?php endif; ?>
