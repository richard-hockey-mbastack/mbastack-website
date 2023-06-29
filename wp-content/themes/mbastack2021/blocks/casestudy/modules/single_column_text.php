<?php
/*
module: casestudy/modules/single_column_text.php
source/scss/casestudy/modules/single_column_text.scss -> assets/css/styles.css
*/

$moduleIdentifier = $args['module_identifier'];
$extraCSS = get_sub_field('extra_css');

?>
<style>
	#<?php echo $moduleIdentifier; ?>{
		z-index: <?php echo $args['z']; ?>;
	}
</style>
<section class="module case-study-single_column_text <?php if ( !empty($extraCSS) ) { echo $extraCSS; } ?>" id="<?= $moduleIdentifier; ?>">
	<div class="container">
		<div class="row>">
			<div class="col main">
				<?= get_sub_field('copy'); ?>
			</div>
		</div>
	</div>
</section>