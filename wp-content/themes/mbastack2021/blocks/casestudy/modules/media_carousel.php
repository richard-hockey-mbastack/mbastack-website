<?php
/*
module: casestudy/modules/media_carousel.php
source/scss/casestudy/modules/media_carousel.scss -> assets/css/styles.css
*/

/*
module_css_class
bg1
bg_bottom_margin
bg_pullup
items[
	is_video ('no','yes')
	image (image ID)
	format ('vimeo','inline'.'youtube')
	source
	width
	height
	caption
]
*/
$moduleIdentifier = $args['module_identifier'];
$extraCSS = get_sub_field('extra_css');

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bb_pullup')) ) ? get_sub_field('bb_pullup') : '0px';

$items = get_sub_field('items');
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
	#<?php echo $moduleIdentifier; ?> .mcfwop{
		position: relative;
		z-index: 1;
	}
</style>
<section class="module case-study-media_carousel <?php if ( !empty($extraCSS) ) { echo $extraCSS; } ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>

	<div class="mcfwop">
		<div class="op">
			<div class="mcSS">
				<?php
				foreach ( $items AS $index => $item) :
					$imageURL = ( !empty($item['image']) ) ? wp_get_attachment_image_src($item['image'], 'full')[0] : '';
				?>
					<div class="item">
						<div class="background">
							<figure>
								<img alt="" src="<?php echo $imageURL; ?>">
							</figure>
							<?php
							if ( $item['is_video'] === 'yes') {
								switch ($item['format']) {
									case "vimeo" : {
										?>
										<div class="videoWrapper vimeoWrapper" data-mode="autoplay" data-width="<?php echo $item['width']; ?>" data-height="<?php echo $item['height']; ?>" data-format="<?php echo $item['format']; ?>" data-source="<?php echo $item['source']; ?>">
											<div class="pr">
												<iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $item['source']; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0" frameborder="0" allow="autoplay;"></iframe>
												<!-- <iframe src="https://player.vimeo.com/video/<?php echo $item['source']; ?>?wmode=transparent&amp;autoplay=0" frameborder="0" allowfullscreen="true"></iframe> -->
											</div>
										</div>
									<?php
									} break;

									case "inline" : {
									?>
							          <div class="videoWrapper inlineWrapper" data-format="inline" data-height="<?php echo $height; ?>" data-width="<?php echo $width; ?>" data-format="inline" data-source="<?php echo $source; ?>" data-mode="manual">
							            <div class="pr"></div> 
							          </div>
									<?php
									} break;

									case "youtube" : {
									?>
									<?php
									} break;
								}
							?>
							<div class="csLink"></div>
							<?php
							}
							?>

						</div>
					</div>
				<?php endforeach; ?>			
			</div>
		</div>
	</div>
</section>