<?php
/*
Modules flexuble content template for layout: single-column
blocks/commom/modules/single_column.php

basic layout:

left aligned single column of text from 1/12th to 12/12ths of grid width
optional background image
optional background-colour
black text on light background or white text on dark background
optional clipped module title, optional colour and opacity
optional CSS class for instance/page/global styles
*/

/*
module_css_class
background_colour
has_parallax_background ('no', 'yes')
background_image (image ID)
title
title_colour
title_opacity
style ('black','white')
column_width (1,2,3,4,5,6,7,8,9,10,11,12)
copy (WSYIWYG HTML)
*/
$moduleIdentifier = ( !empty(get_sub_field('anchor')) ) ? get_sub_field('anchor') : $args['module_identifier'];

$hasTtitle = !empty(get_sub_field('title'));

$hasBackgroundColour = !empty(get_sub_field('background_colour'));
$hasParallax = ( get_sub_field('has_parallax_background') === 'yes' );
$hasBackgroundImage = !empty(get_sub_field('background_image'));
if( $hasBackgroundImage ) {
  $backgroundImageURL = wp_get_attachment_image_src(get_sub_field('background_image'), 'full')[0];
}

// background video section.homeValues -> /source/scss/pages/hoes.scss

// hav_video 'no'.'yes' $hasVideo
$hasVideoBG = (get_sub_field('has_video') === 'yes' );
if ( $hasVideoBG ) {
  $format = get_sub_field('format');
  $source = get_sub_field('source');
  $video_width = get_sub_field('video_width');
  $video_height = get_sub_field('video_height');
}

$hasFollowingLink = ( get_sub_field('has_following_link') === 'yes' );
if ( $hasFollowingLink ) {
/*
likk_caption
link_url
link_target ('self','new')
*/
  $linkCaption = get_sub_field('link_caption');  
  $linkURL = get_sub_field('link_url');  
  $linktarget = (get_sub_field('link_target') === 'new' ) ? ' target="_blank"' : '';  
}
?>
<style>
#<?= $moduleIdentifier; ?>{
<?php if ($hasBackgroundColour) : ?>
  background-color: <?= get_sub_field('background_colour'); ?>;
<?php endif; ?>  

<?php if ( $hasBackgroundImage && !$hasParallax ) : ?>
  background-image: url('<?= $backgroundImageURL; ?>');
  <?php endif; ?>

}
<?php if ( $hasTtitle ) : ?>
#<?= $moduleIdentifier; ?> .topclip h2{
  <?php if ( !empty(get_sub_field('title_colour')) ) : ?>
    color: <?= get_sub_field('title_colour'); ?>;
  <?php endif; ?>
  <?php if ( !empty(get_sub_field('title_opacity')) ) : ?>
    opacity: <?= get_sub_field('title_opacity'); ?>;
  <?php endif; ?>
}
<?php endif; ?>
</style>
  
<?php if ( $hasParallax ) : ?>
<section class="module singleColumn <?= get_sub_field('module_css_class'); ?> <?= get_sub_field('style'); ?> <?php if ( !$hasTtitle ) : ?>noTitle<?php endif; ?> parallax-window" id="<?= $moduleIdentifier; ?>" data-parallax="scroll" data-image-src="<?= $backgroundImageURL; ?>">
<?php else : ?>
<section class="module singleColumn <?= get_sub_field('module_css_class'); ?> <?= get_sub_field('style'); ?> <?php if ( !$hasTtitle ) : ?>noTitle<?php endif; ?>" id="<?= $moduleIdentifier; ?>" <?php if ( $hasParallax ) : ?>data-parallax="yes"<?php endif; ?>>
<?php endif; ?>

<?php if ( $hasVideoBG ) : ?>
  <div class="vv" data-format="<?= $format ?>" data-source="<?= $source ?>" data-width="<?= $video_width ?>" data_height="<?= $video_height ?>"></div>
<?php endif ?>

  <?php if ( !empty(get_sub_field('title') ) ) : ?>
    <header class="topclip">
      <div class="container">
        <div>
          <h2><?= get_sub_field('title'); ?></h2>
        </div>
      </div>
    </header>
  <?php endif; ?>

  <div class="copy">
    <div class="container">
      <div class="row">
        <div class="col-md-<?= get_sub_field('column_width'); ?>">
          <div class="ba">
            <?= get_sub_field('copy'); ?>

            <?php if ( $hasFollowingLink ) : ?>
              <a href="<?= $linkURL; ?>" class="arrowLink white"<?= $linktarget; ?>><?= $linkCaption; ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

