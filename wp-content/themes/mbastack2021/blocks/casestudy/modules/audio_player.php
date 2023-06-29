<?php
// audio_player

// extra_css_class
// title
// duration
// audio_file -> file array
// - ID
// - filesize
// - url
// - name
// - mimetype
// - type
// - subtype
// image
$moduleIdentifier = $args['module_identifier'];
$extraCSS = get_sub_field('extra_css_class');
$title = get_sub_field('title');
$duration = get_sub_field('duration');
$audio = get_sub_field('audio_file');
$imageID = get_sub_field('image');
$imagePath = ( !empty($imageID) ) ? wp_get_attachment_image_src($imageID, 'full')[0] : '';

// echo "<pre>".print_r($audio, true)."</pre>";
?>
<style>
	#<?= $moduleIdentifier ?>{}
</style>
<section class="module audio_player <?php if ( !empty($extraCSS) ) { echo $extraCSS; } ?>" id="<?= $moduleIdentifier ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 desktopOnly">
				<figure>
					<img src="<?= $imagePath ?>" alt="<?= "$title duration: $duration" ?>"></figure>
				</figure>
			</div>
			<div class="col-lg-6">
				<h2><?= $title ?></h2>
				<?php if ( false && !empty( $duration ) ) : ?><p>Duration: <?= $duration ?></p><?php endif; ?>
			    <audio controls src="<?= get_template_directory_uri().$audio ?>">Your browser does not support the <code>audio</code> element.</audio>				
			</div>
		</div>
	</div>
</section>