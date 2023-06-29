<?php
/*
Modules flexuble content template for layout: mismatched_images
blocks/common/modules/mismatched_images
/source/scss/common/whoWeAreIntro.scss
*/

/*
small_image
large_image
layout ('smallfirst','largefirst')
*/

$large = ( !empty(get_sub_field('large_image') ) ) ? wp_get_attachment_image_src(get_sub_field('large_image'), 'full')[0] : '';
$small = ( !empty(get_sub_field('small_image') ) ) ? wp_get_attachment_image_src(get_sub_field('small_image'), 'full')[0] : '';
?>
<section class="module mismatched_images <?php if (get_sub_field('layout') === 'largefirst') : ?>largefirst<?php endif; ?>">
	<div class="images">
		<?php if ( !empty($large) )  : ?>
		<figure  class="largeB"><img src="<?php echo $large; ?>"></figure>
		<?php endif; ?>
		<?php if ( !empty($small) )  : ?>
		<figure  class="smallB"><img src="<?php echo $small; ?>"></figure>
		<?php endif; ?>
	</div>
</section>
