<?php
/*
module: casestudy/modules/mismatched_media.php
source/scss/casestudy/modules/mismatched_media.scss -> assets/css/styles.css
*/

/*
module_css_class
bg1
bg_bottom_margin
bg_pullup
layout ('narrow','wide')
mobile_layout ('coloumn','row')
narrow[
	image
	is_video
	format
	source
	width
	height
	caption
]
wide[
	image
	is_video
	format
	source
	width
	height
	caption
]
*/
$moduleIdentifier = $args['module_identifier'];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';
$mobileLayoutClass = (get_sub_field('mobile_layout') === 'row') ? 'col-6' : '';
$wide = get_sub_field('wide');
$narrow = get_sub_field('narrow');
$wideImageID = $wide['image'];
$wideImageURL = ( !empty($wideImageID) ) ? wp_get_attachment_image_src($wideImageID, 'full')[0] : '';
$narrowImageID = $narrow['image'];
$narrowImageURL = ( !empty($narrowImageID) ) ? wp_get_attachment_image_src($narrowImageID, 'full')[0] : '';
$wideIsVideo = ( $wide['is_video'] === 'yes');
$narrowIsVideo = ( $narrow['is_video'] === 'yes');
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
<section class="module case-study-mismatched_media <?php echo get_sub_field('layout'); ?> mobile-<?php echo get_sub_field('mobile_layout'); ?> <?php if ( !empty(get_sub_field('module_css_class')) ) { echo get_sub_field('module_css_class'); } ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>


	<div class="container">
		<div class="row">
			<div class="<?php echo $mobileLayoutClass; ?> col-md-8 wide">
				<?php
				if ($wideIsVideo) {
				?>
					<div class="background">
						<figure>
							<img alt="<?php echo $wide['caption']; ?>" src="<?php echo $wideImageURL; ?>">
						</figure>
				<?php
				switch ( $wide['format'] ) :
					case 'vimeo' : {
				?>
				
			         <div class="videoWrapper vimeoWrapper" data-mode="manual" data-format="vimeo"  data-width="1292" data-height="727">
			            <div class="pr">
			              <iframe src="https://player.vimeo.com/video/<?php echo $wide['source']; ?>?wmode=transparent&amp;autoplay=0&portrait=0&byline=0" frameborder="0" allowfullscreen="true"></iframe>	
			            </div>
			        </div>
				
				<?php


					} break;
					case 'inline' : {
				?>
		          <div class="videoWrapper inlineWrapper" data-format="inline" data-height="<?php echo $wide['height']; ?>" data-width="<?php echo $wide['width']; ?>" data-format="inline" data-source="<?php echo $wide['source']; ?>" data-mode="manual">
		            <div class="pr"></div> 
		          </div>
				<?php
						
					} break;
					case 'youtube' : {
				?>
				<?php						
					} break;
				endswitch;
				?>
					</div>
				<?php
				} else {
				?>
					<figure>
						<img alt="<?php echo $wide['caption']; ?>" src="<?php echo $wideImageURL; ?>">
					</figure>
				<?php						
				}
				?>
				
			</div>
			<div class="<?php echo $mobileLayoutClass; ?> col-md-4 narrow">
				<?php
				if ($narrowIsVideo) {
				?>
					<div class="background">
						<figure>
							<img alt="<?php echo $narrow['caption']; ?>" src="<?php echo $narrowImageURL; ?>">
						</figure>
				<?php
				switch ( $narrow['format'] ) :
					case 'vimeo' : {
				?>
				
			         <div class="videoWrapper vimeoWrapper" data-mode="manual" data-format="vimeo"  data-width="1292" data-height="727">
			            <div class="pr">
			              <iframe src="https://player.vimeo.com/video/<?php echo $narrow['source']; ?>?wmode=transparent&amp;autoplay=0&portrait=0&byline=0" frameborder="0" allowfullscreen="true"></iframe>	
			            </div>
			        </div>
				
				<?php


					} break;
					case 'inline' : {
				?>
		          <div class="videoWrapper inlineWrapper" data-format="inline" data-height="<?php echo $narrow['height']; ?>" data-width="<?php echo $narrow['width']; ?>" data-format="inline" data-source="<?php echo $narrow['source']; ?>" data-mode="manual">
		            <div class="pr"></div> 
		          </div>
				<?php
						
					} break;
					case 'youtube' : {
				?>
				<?php						
					} break;
				endswitch;
				?>
					</div>
				<?php
				} else {
				?>
					<figure>
						<img alt="<?php echo $narrow['caption']; ?>" src="<?php echo $narrowImageURL; ?>">
					</figure>
				<?php						
				}
				?>
				
			</div>
		</div>
	</div>
</section>