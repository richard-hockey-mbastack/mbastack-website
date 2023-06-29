<?php
/*
module: casestudy/modules/text-and-image.php
source/scss/casestudy/modules/fullwidth_video.scss -> assets/css/styles.css
*/

/*
module_css_class
layout ('image','text') image on levt or text on left
bg1
bg_bottom_margin
bg_pullup
text_colour ('black','white')
copy1 (above row)
copy2 (in row)
image (image ID)
is_video ('no','yes')
format ('vimeo','inline','youtube')
source
width INT
height INT
caption
autoplay
loop
*/

$moduleIdentifier = $args['module_identifier'];

$bg1 = get_sub_field('bg1');
$bg_bottom_margin = ( !empty(get_sub_field('bg_bottom_margin')) ) ? get_sub_field('bg_bottom_margin') : '0px';
$bg_pullup = ( !empty(get_sub_field('bg_pullup')) ) ? get_sub_field('bg_pullup') : '0px';

$extraCSS = get_sub_field('module_css_class');

$layout = get_sub_field('layout');
$ColumnWidths = get_sub_field('column_widths');
switch( $ColumnWidths ) {
	case 'image1' : {
		$imageColumn = 'col-md-1';
		$textColumn = 'col-md-11';
	} break;
	case 'image2' : {
		$imageColumn = 'col-md-2';
		$textColumn = 'col-md-10';
	} break;
	case 'image3' : {
		$imageColumn = 'col-md-3';
		$textColumn = 'col-md-9';
	} break;
	case 'image4' : {
		$imageColumn = 'col-md-4';
		$textColumn = 'col-md-8';
	} break;
	case 'image5' : {
		$imageColumn = 'col-md-5';
		$textColumn = 'col-md-7';
	} break;
	case 'image6' : {
		$imageColumn = 'col-md-6';
		$textColumn = 'col-md-6';
	} break;
	case 'image7' : {
		$imageColumn = 'col-md-7';
		$textColumn = 'col-md-5';
	} break;
	case 'image8' : {
		// default
		$imageColumn = 'col-md-8';
		$textColumn = 'col-md-4';
	} break;
	case 'image9' : {
		$imageColumn = 'col-md-9';
		$textColumn = 'col-md-3';
	} break;
	case 'image10' : {
		$imageColumn = 'col-md-10';
		$textColumn = 'col-md-2';
	} break;
	case 'image11' : {
		$imageColumn = 'col-md-11';
		$textColumn = 'col-md-1';
	} break;
}
$mediaType = get_sub_field('media_type');

$copy1 = get_sub_field('copy1');
$copy2 = get_sub_field('copy2');

switch( $mediaType ) {
	case 'image' : {

	} break;
	case 'video' : {

	} break;
	case 'audio' : {

	} break;
}
// image
$imageID = get_sub_field('image');
$imageURL = ( !empty($imageID) ) ? wp_get_attachment_image_src($imageID, 'full')[0] : '';

// video  
$isVideo = get_sub_field('is_video') === 'yes';
$caption = get_sub_field('caption');
$format = get_sub_field('format');
$source = get_sub_field('source');
$width = get_sub_field('width');
$height = get_sub_field('height');
$autoplay = get_sub_field('autoplay');



$vimeoQS = ( $autoplay === 'autoplay' ) ? '?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0' : '?wmode=transparent&amp;autoplay=0&amp;autoplay=0&portrait=0&byline=0';
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
<section class="module case-study-text-and-image <?php echo get_sub_field('text_colour'); ?> <?php if ( !empty($extraCSS) ) { echo $extraCSS; } ?> <?php echo $layout; ?>" id="<?php echo $moduleIdentifier; ?>">
	<div class="bg1"></div>
	<div class="container">
		<?php if ( !empty($copy1)) : ?>
		<div class="row">
			<div class="<?= $textColumn ?> text">
				<div><?php echo $copy1; ?></div>
			</div>
			<div class="<?= $imageColumn ?> media"></div>
		</div>
		<?php endif; ?>
		<div class="row">

			<div class="<?= $textColumn ?> text">
				<?php if ( !empty($copy2) ) : ?><div><?php echo $copy2; ?></div><?php endif; ?>
			</div>

			<div class="<?= $imageColumn ?> media <?= $mediaType ?>">

<?php
switch( $mediaType ) {
	case 'image' : {
?>				
					<figure>
						<img alt="<?php echo $caption; ?>" src="<?php echo $imageURL; ?>">
					</figure>
<?php
	} break;
	case 'multi-image' : {
		$multiImages = ( !empty(get_sub_field('multi_images')) ) ? get_sub_field('multi_images') : []; 
		$imageCount = count( $multiImages );
		$rowclass = "col";

		switch( $imageCount ) {
			case 1 : { $rowclass = "one"; } break;
			case 2 : { $rowclass = "half"; } break;
			case 3 : { $rowclass = "third"; } break;
			case 4 : { $rowclass = "quarter"; } break;
		}
		if ( $multiImages > 0 ) : 
			?>
					
			<?php
			foreach( $multiImages AS $image ) :
				// image -> imageID
				// caption
				$caption = $image['caption'];
				$imageID = $image['image'];
				$imageURL = ( !empty($imageID) ) ? wp_get_attachment_image_src($imageID, 'full')[0] : '';
?>
			<div class="<?= $rowclass ?>">				
				<figure>
					<img alt="<?php echo $caption; ?>" src="<?php echo $imageURL; ?>">
				</figure>
			</div>

<?php
		endforeach; ?>
<?php
		endif;
	} break;
	case 'video' : {
?>				
					<div class="background">
						<figure>
							<img alt="<?php echo $caption; ?>" src="<?php echo $imageURL; ?>">
						</figure>
				<?php
				switch ( $format ) :
					case 'vimeo' : {
				?>
				
			         <div class="videoWrapper vimeoWrapper" data-mode="<?php echo $autoplay; ?>" data-format="vimeo"  data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>">
			            <div class="pr">
			              <iframe src="https://player.vimeo.com/video/<?php echo $source.$vimeoQS; ?>" frameborder="0" allowfullscreen="true"></iframe>	
			            </div>
			        </div>
				
				<?php


					} break;
					case 'inline' : {
				?>
		          <div class="videoWrapper inlineWrapper" data-format="inline" data-height="<?php echo $height; ?>" data-width="<?php echo $width; ?>" data-format="inline" data-source="<?php echo $source; ?>" data-mode="manual">
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
	} break;
	case 'audio' : {
// audio
$title = get_sub_field('audio_title');
$duration = get_sub_field('audio_duration');
$audio = get_sub_field('audio_file');
$imageID = get_sub_field('audio_image');
$imagePath = ( !empty($imageID) ) ? wp_get_attachment_image_src($imageID, 'full')[0] : '';

?>				
<div class="container audioPlayer">
	<div class="row">
		<div class="headphones">
			<figure>
				<img src="<?= $imagePath ?>" alt="<?= "$title duration: $duration" ?>"></figure>
			</figure>
		</div>
		<div class="widget">
			<?php if ( false && !empty( $duration ) ) : ?><p>Duration: <?= $duration ?></p><?php endif; ?>
		    <audio controls src="<?= get_template_directory_uri().$audio ?>">Your browser does not support the <code>audio</code> element.</audio>				
		</div>
	</div>
</div>

<?php
	} break;
}
?>				
				
			</div>
		</div>
	</div>
</section>

