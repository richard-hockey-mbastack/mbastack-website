<?php
/*
module: casestudy/modules/fullwidth_video.php
source/scss/casestudy/modules/fullwidth_video.scss -> assets/css/styles.css
*/

/*
bg1 (color, '') '' = transparent
bg_bottom_margin
bg_pullup

video_format
video_source
poster_image (ID)
caption
width
height
video_scaling ('width', 'height') defaults to width
*/

$moduleIdentifier = $args['module_identifier'];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

$format = get_sub_field('video_format');
$source = get_sub_field('video_source');
$width = get_sub_field('width');
$height = get_sub_field('height');
$scaling = get_sub_field('video_scaling');

$posterID = get_sub_field('poster_image');
$poster = (!empty($posterID)) ? wp_get_attachment_image_src(get_sub_field('poster_image'), 'full')[0] : '';
$caption = get_sub_field('caption');


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

<section class="module case-study-fullwidth_video" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="videoBox">
					<div class="background">
						<figure>
							<img alt="" src="<?= $poster ?>">
						</figure>
				<?php
				switch ( $format ) :
					case 'vimeo' : {
				?>
				
			         <div class="videoWrapper vimeoWrapper noscale <?php if (!empty($scaling)) : echo 'scaleTo'.$scaling; else : echo 'scaleTowidth'; endif; ?>" data-mode="manual" data-format="vimeo"  data-width="1292" data-height="727" data-source="<?php echo $source; ?>">
			            <div class="pr">
			              <iframe src="https://player.vimeo.com/video/<?php echo $source; ?>?wmode=transparent&amp;autoplay=0&portrait=0&byline=0" frameborder="0" allowfullscreen="true"></iframe>	
			            </div>
			        </div>
				
				<?php


					} break;
					case 'inline' : {
				?>
		          <div class="videoWrapper inlineWrapper noscale <?php if (!empty($scaling)) : echo 'scaleTo'.$scaling; else : echo 'scaleTowidth'; endif; ?>" data-format="inline" data-height="<?php echo $height; ?>" data-width="<?php echo $width; ?>" data-format="inline" data-source="<?php echo $source; ?>" data-mode="manual">
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
				
				</div>
				<p class="caption"><?php echo $caption; ?></p>
			</div>
		</div>
	</div>
</section>
