<?php
/*
Modules flexuble content template for layout: fuse
/blocks/commom/modules/fuse.php
/source/scss/common/fuse.scss
*/

$moduleIdentifier = $args['module_identifier'];
$imageID = get_sub_field('image');
$imageURL = ( !empty($imageID) ) ? wp_get_attachment_image_src($imageID, 'full')[0] : '';
?>

<style>
	#<?= $moduleIdentifier ?>{}
</style>

<section class="module fuse" id="<?= $moduleIdentifier ?>">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="bar">
					<div class="box">
						<h2>Are you on a growth mission? <br>Find out how we can help you <span>light the&nbsp;fuse.</span> <div class="videoClipn">
							<div class="background background-x">
							<div class="videoWrapper inlineWrapper" data-format="inline" data-mode="scroll"  data-height="268" data-width="280" data-source="<?php echo get_template_directory_uri(); ?>/assets/video/fuse.mp4">
							<div class="pr"></div>
							</div>
							</div>
						</div></h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>