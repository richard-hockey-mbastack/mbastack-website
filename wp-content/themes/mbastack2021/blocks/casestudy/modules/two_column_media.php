<?php
/*
module: casestudy/modules/two_column_media.php
source/scss/casestudy/modules/two_column_media.scss -> assets/css/styles.css
*/

/*
module_css_class

bg1
bg_bottom_margin
bg_pullup

left_column[
	image
	caption
	is_video
	video_format
	video_source
	video_width
	video_height
	video_autoplay ('no','yes')
]
right_column[
	image
	caption
	is_video
	video_format
	video_source
	video_width
	video_height
	video_autoplay ('no','yes')
]
*/
$moduleIdentifier = $args['module_identifier'];
$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

$left_column = get_sub_field('left_column');
$right_column = get_sub_field('right_column');
$leftImage = ( !empty($left_column['image']) ) ? wp_get_attachment_image_src($left_column['image'], 'full')[0] : '';
$rightImage = ( !empty($right_column['image']) ) ? wp_get_attachment_image_src($right_column['image'], 'full')[0] : '';

$leftVideoMode = ($left_column['video_autoplay'] === 'yes') ? 'autoplay' : 'manual';
$rightVideoMode = ($right_column['video_autoplay'] === 'yes') ? 'autoplay' : 'manual';

// module_css_class foldVideo
$isStellantisFoldVideo = (strpos( get_sub_field('module_css_class'), 'foldVideo' ))
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
<section class="module case-study-two_column_media <?php echo get_sub_field('module_css_class'); ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-6 media">

				<?php if ( $left_column['is_video'] === 'no') : ?>
				<figure>
					<img alt="<?php echo $left_column['caption']; ?>" src="<?php echo $leftImage; ?>">
				</figure>
				<?php endif; ?>

				<?php if ( $left_column['is_video'] === 'yes') : ?>
					<div class="background">
						<?php if (!empty($leftImage)) : ?>
							<figure>
								<img alt="<?php echo $left_column['caption']; ?>" src="<?php echo $leftImage; ?>">
							</figure>
						<?php endif; ?>
				<?php
				switch ( $left_column['video_format'] ) :
					case 'vimeo' : {
				?>
				
			         <div class="videoWrapper vimeoWrapper scaleTowidth" data-mode="<?php echo $leftVideoMode; ?>" data-format="vimeo"  data-width="<?php echo $left_column['video_width']; ?>" data-height="<?php echo $left_column['video_height']; ?>">
			            <div class="pr">
			              <iframe src="https://player.vimeo.com/video/<?php echo $left_column['video_source']; ?>?wmode=transparent&amp;autoplay=0&portrait=0&byline=0" frameborder="0" allowfullscreen="true"></iframe>	
			            </div>
			        </div>
				
				<?php


					} break;
					case 'inline' : {
						// if $isStellantisFoldVideo add class 'scaleTowidth' to container 'videoWrapper'
				?>
		          <div class="videoWrapper inlineWrapper scaleTowidth" data-format="inline" data-height="<?php echo $left_column['video_height']; ?>" data-width="<?php echo $left_column['video_width']; ?>" data-format="inline" data-source="<?php echo get_template_directory_uri().$left_column['video_source']; ?>" data-mode="<?php echo $leftVideoMode; ?>">
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
				<?php endif; ?>

				<p class="caption"><?php echo $left_column['caption']; ?></p>
			</div>

			<div class="col-md-6 media">

				<?php if ( $right_column['is_video'] === 'no') : ?>
				<figure>
					<img alt="<?php echo $right_column['caption']; ?>" src="<?php echo $rightImage; ?>">
				</figure>
				<?php endif; ?>

				<?php if ( $right_column['is_video'] === 'yes') : ?>
					<div class="background">
						<?php if (!empty($rightImage)) : ?>
							<figure>
								<img alt="<?php echo $right_column['caption']; ?>" src="<?php echo $rightImage; ?>">
							</figure>
						<?php endif; ?>
				<?php
				switch ( $right_column['video_format'] ) :
					case 'vimeo' : {
				?>
				
			         <div class="videoWrapper vimeoWrapper scaleTowidth" data-mode="<?php echo $rightVideoMode; ?>" data-format="vimeo"  data-width="<?php echo $right_column['video_width']; ?>" data-height="<?php echo $right_column['video_height']; ?>">
			            <div class="pr">
			              <iframe src="https://player.vimeo.com/video/<?php echo $right_column['video_source']; ?>?wmode=transparent&amp;autoplay=0&portrait=0&byline=0" frameborder="0" allowfullscreen="true"></iframe>	
			            </div>
			        </div>
				
				<?php


					} break;
					case 'inline' : {
				?>
		          <div class="videoWrapper inlineWrapper scaleTowidth" data-format="inline" data-height="<?php echo $right_column['video_height']; ?>" data-width="<?php echo $right_column['video_width']; ?>" data-format="inline" data-source="<?php echo get_template_directory_uri().$right_column['video_source']; ?>" data-mode="<?php echo $rightVideoMode; ?>">
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
				<?php endif; ?>

				<p class="caption"><?php echo $right_column['caption']; ?></p>
			</div>
		</div>
	</div>
</section>