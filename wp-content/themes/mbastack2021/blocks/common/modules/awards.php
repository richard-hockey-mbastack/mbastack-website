<?php
/*
Modules flexuble content template for layout: awards
blocks/common/modules/awards

Modules2
  layout: 'google_map'

	module_css_class
  title
	title colour
	title opacity
  background_colour
  intro
  awards[
	[
		title
		copy
		image
	]
	...
  ]
*/

$moduleIdentifier = $args['module_identifier'];
$hasTtitle = !empty(get_sub_field('title'));
$hasBackgroundColour = !empty(get_sub_field('background_colour'));
$textColour = ( !empty(get_sub_field('text_color')) ) ? get_sub_field('text_color') : 'white';

$awards = ( !empty(get_sub_field('awards')) ) ? get_sub_field('awards') : [];
?>

<style>
#<?php echo $moduleIdentifier; ?>{
	/* <?php echo get_sub_field('background_colour'); ?> */
<?php if ( $hasBackgroundColour ) : ?>
		background-color: <?php echo get_sub_field('background_colour'); ?>;
<?php endif; ?>
}
<?php if ( $hasTtitle ) : ?>
#<?php echo $moduleIdentifier; ?> .topclip h2{
	/*
  <?php if ( !empty(get_sub_field('title_colour')) ) : ?>
    color: <?php echo get_sub_field('title_colour'); ?>;
  <?php endif; ?>
  <?php if ( !empty(get_sub_field('title_opacity')) ) : ?>
    opacity: <?php echo get_sub_field('title_opacity'); ?>;
  <?php endif; ?>
  */
}
<?php endif; ?>
</style>

<section class="module awards <?php if ( !empty(get_sub_field('module_css_class')) ) : echo get_sub_field('module_css_class');  endif; ?> <?= $textColour ?>" id="<?php echo $moduleIdentifier ?>">
  <?php if ( $hasTtitle ) : ?>
    <header class="topclip">
      <div class="container">
        <div>
          <h2><?php echo get_sub_field('title'); ?></h2>
        </div>
      </div>
    </header>
  <?php endif; ?>

	<div class="container">
		<div class="row">
		  <div class="col-md-8 introbox">
		  	<p><?php echo get_sub_field('intro'); ?></p>
		  </div>
		</div>
	</div>

	<div class="container">
		<div class="row awards <?php if ( count( $awards ) === 1 ) { echo "single-award"; } ?>">

			<?php if ( count( $awards ) > 0 ) :
				foreach ( $awards AS $award ) : 
					$imageURL = ( !empty($award['image']) ) ? wp_get_attachment_image_src($award['image'], 'full')[0] : '';
			?>
		  <div class="col-md-6 award">
		    <figure><img alt="<?php echo $award['title']; ?>" src="<?php echo $imageURL; ?>"></figure>
		    <div class="copy">
		      <h5><?php echo $award['title']; ?></h5>
		      <p><?php echo $award['copy']; ?></p>
		    </div>
		  </div>

			<?php
				endforeach;
			endif;
			?>
		</div>
	</div>  
</section>
