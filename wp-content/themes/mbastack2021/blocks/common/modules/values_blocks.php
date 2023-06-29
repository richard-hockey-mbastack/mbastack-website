<?php
// 
/*
Modules flexuble content template for layout: values_blocks
blocks/commom/modules/values_blocks.php
*/

/*
extra_css
layout 'text-first','image-first'
rows[
	extra_css
	anchor
	bg_colour
	text_colour
	image
	copy
]
*/

$moduleIdentifier = $args['module_identifier'];
$extraCSS = get_sub_field('extra_css');
$layout = get_sub_field('layout');
$rows = get_sub_field('rows');

// echo "<pre>".print_r($rows ,true )."</pre>";
?>
<style>
#<?= $moduleIdentifier ?>{}	
</style>
<section class="module valuesBlocks <?= $layout ?> <?php if ( !empty($extraCSS) ) { echo $extraCSS; } ?>" id="<?= $moduleIdentifier ?>">
	<?php foreach ( $rows AS $index => $row ) :
/*
[row_extra_css] => 
[anchor] => sparks
[bg_colour] => #0e0127
[text_colour] => white
[image] => 1997
[copy] => 		
*/
	$backgroundImageURL = ( !empty($row['image']) ) ? wp_get_attachment_image_src($row['image'], 'full')[0] : false;
	?>
	<div class="pair row<?= $index ?> <?= $row['text_colour'] ?>" <?php if ( !empty( $row['anchor'] ) ) echo "id=\"{$row['anchor']}\""; ?> <?php if ( !empty( $row['bg_colour'] ) ) echo "style=\"background-color:{$row['bg_colour']};\""; ?> >
		<div class="text">
			<div class="tbox">
			<?= $row['copy'] ?>
			</div>
		</div>
		<div class="image" <?php if ( $backgroundImageURL ) : ?>style="background-image:url('<?= $backgroundImageURL ?>')"<?php endif ?>></div>
	</div>
	<?php endforeach; ?>

</section>