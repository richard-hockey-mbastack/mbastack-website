<?php
/*
module: casestudy/modules/two_column_text.php
source/scss/casestudy/modules/single_column_text.scss -> assets/css/styles.css
*/

/*
module_css_class
layout
column_split
bg1
bg_bottom_margin
bg_pullup
text_colour ('black','white')
title
narrow
wide
*/

$moduleIdentifier = $args['module_identifier'];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

$layout = get_sub_field('layout');
$columnSplit = get_sub_field('column_split');

switch( $columnSplit ) {
	case 'inequal' : {
		$a = 'col-md-4';
		$b = 'col-md-8';
	} break;
	case 'equal' : {
		$a = 'col-md-6';
		$b = 'col-md-6';
	} break;
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
<section class="module case-study-two_column_text <?php echo get_sub_field('text_colour'); ?> <?php echo $layout; ?> <?php echo $columnSplit; ?> <?php if ( !empty(get_sub_field('module_css_class')) ) { echo get_sub_field('module_css_class');} ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<?php if ( !empty(get_sub_field('title')) ) : ?>
		<div class="row">
			<div class="<?php echo $a; ?> narrow">
				<h2 class="title"><?php echo get_sub_field('title'); ?></h2>
			</div>
			<div class="<?php echo $b; ?> wide"></div>
		</div>
	<?php endif; ?>
		<div class="row">
			<div class="<?php echo $a; ?> narrow">
				<?php echo get_sub_field('narrow'); ?>
			</div>
			<div class="<?php echo $b; ?> wide">
				<?php echo get_sub_field('wide'); ?>
			</div>
		</div>
	</div>
</section>