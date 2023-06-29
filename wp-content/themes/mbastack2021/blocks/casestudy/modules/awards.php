<?php
/*
module: casestudy/modules/awards.php
source/scss/casestudy/modules/awards.scss -> assets/css/styles.css
*/

/*
heading
subheading
awards[
	item
	caption
]
*/

$moduleIdentifier = $args['module_identifier'];
$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';
$awards = get_sub_field('awards');
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
<section class="module case-study-awards" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row">
			<div class="col header">
				<h2><?php echo get_sub_field('heading'); ?></h2>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-6 intro">
				<?php echo get_sub_field('subheading'); ?>
			</div>
			<div class="col-md-6 values">
				<div class="row">
					<?php
					if ( count($awards) > 0 ) :
						foreach( $awards AS $index => $award) : ?>
					<div class="col-6 point" data-value-max="<?php echo $award['data']; ?>">
						<h4><?php echo $award['prefix']; ?><span class="value"><?php echo $award['data']; ?></span><?php echo $award['suffix']; ?></h4>
						<p><?php echo $award['caption']; ?></p>
					</div>
					<?php
						endforeach;
					endif;
					?>
				</div>
			</div>
		</div>
	</div>
</section>