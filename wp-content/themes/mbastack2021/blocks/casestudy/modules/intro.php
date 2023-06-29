<?php
/*
module: casestudy/modules/intro.php
source/scss/casestudy/modules/intro.scss -> assets/css/styles.css
*/

$moduleIdentifier = $args['module_identifier'];
/*
parallax ('start','end','none')
bg1 (color,transparent)
bg2 (color,transparent)
bg_overlap

copy1
copy2
copy3_title
copy3
*/
$moduleIdentifier = $args['module_identifier'];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

?>

<style>
	#<?php echo $moduleIdentifier; ?>{
		z-index: <?php echo $args['z']; ?>;
	}
	#<?php echo $moduleIdentifier; ?> .bg1{
		<?php if ( !empty($bg1) ) : ?>background-color: <?php echo $bg1; ?>;<?php endif; ?>
		top: <?php echo $bg_pullup; ?>;
		height: calc(100% - <?php echo $bg_bottom_margin; ?>);
	}
</style>

<section class="module case-study-intro" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row row1">
			<?php if (!empty(get_sub_field('copy1')) && !empty(get_sub_field('copy2')) ) : ?>
			<div class="col-md-6 copy1">
				<?php echo get_sub_field('copy1'); ?>
			</div>
			<div class="col-md-6 copy2">
				<?php echo get_sub_field('copy1'); ?>
			</div>
			<?php endif; ?>
			<?php if ( !empty(get_sub_field('copy1')) && empty(get_sub_field('copy2')) ) : ?>
			<div class="col-md-9 copy1">
				<?php echo get_sub_field('copy1'); ?>
			</div>
			<?php endif; ?>
		</div>
		<div class="row2">
			<div class="col-md-6 offset-md-6 main">
				<h2><?php echo get_sub_field('copy3_title'); ?></h2>
				<?php echo get_sub_field('copy3'); ?>
			</div>
		</div>
	</div>
</section>