<?php
/*
Modules flexuble content template for layout: two-column
blocks/commom/modules/two-column.php
*/

/*
module_css_class
title
title_colour
title_opacity
full_width ('no','yes')
arrangement ('text','image')
text_colour
background-colour
background_image
text_header
copy
image_fills_entire_column ('yes','no')
image_size
desktop_image
mobile_image
vector
has_following_links ('yes','no')
links[
  [
    'link' => [
      link_text
      link_url
      link_target ('self','blank')
      link_style ('white','black')
    ]
  ]
]
*/

$moduleIdentifier = $args['module_identifier'];

$hasClass = !empty( get_sub_field('module_css_class') );
$hasTitle = !empty( get_sub_field('title') );
$hasBackgroundImage = !empty( get_sub_field('background_image') );
$hasBackgroundColor = !empty( get_sub_field('background_colour') );
$backgroundImageURL = ($hasBackgroundImage) ? wp_get_attachment_image_src(get_sub_field('background_image'), 'full')[0] : '';
$hasCopyHeader = !empty( get_sub_field('text_header') );
$hasFollowingLinks = get_sub_field('has_following_links') === 'yes';

$imageFillsColumn = get_sub_field('image_fills_entire_column') === 'yes';
$imageHasSize = !empty( get_sub_field('image_size') );

$desktop = get_sub_field('desktop_image');
$mobile = get_sub_field('mobile_image');
if (empty($mobile)) :
  $mobile = $desktop;
endif;

$desktopURL = ( !empty($desktop) ) ? wp_get_attachment_image_src($desktop, 'full')[0] : '';
$mobileURL = ( !empty($mobile) ) ? wp_get_attachment_image_src($mobile, 'full')[0] : '';

// echo "<pre>".print_r(get_row(),true)."</pre>";"

?>
<style>
#<?php echo $moduleIdentifier; ?>{
<?php if ( $hasBackgroundColor ) : ?>
  background-color: <?php echo get_sub_field('background_colour'); ?>;
<?php endif; ?>
<?php
if ( $hasBackgroundImage ) :
?>
  background-image:url('<?php echo $backgroundImageURL; ?>');
<?php endif; ?>
}
<?php if( $hasTitle ) : ?>
  /*
#<?php echo $moduleIdentifier; ?> header h2{
<?php if ( !empty( get_sub_field('title_colour') ) ) : ?>
  color: <?php echo get_sub_field('title_colour'); ?>;
<?php endif; ?>  
<?php if ( !empty( get_sub_field('title_opacity') ) ) : ?>
  opacity: <?php echo get_sub_field('title_opacity'); ?>;
<?php endif; ?>  
*/
}
<?php endif; ?>
<?php if ( $imageHasSize && !$imageFillsColumn) : ?> 
#<?php echo $moduleIdentifier; ?> .imageColumn figure img{width: <?php echo get_sub_field('image_size'); ?>;margin: 0 auto;}
<?php endif; ?>
</style>
<section class="module twoColumnMixed <?php if ( !empty(get_sub_field('module_css_class')) ) : echo get_sub_field('module_css_class');  endif; ?> <?php if (get_sub_field('arrangement') === 'text') : ?>textLeft<?php else : ?>imageLeft <?php endif; ?><?php if (get_sub_field('full_width') !== 'no') : ?> fullWidth<?php endif; ?><?php if (get_sub_field('text_colour') === 'white') : ?> whiteCopy<?php endif; ?> <?php if ( $imageFillsColumn ) : ?>fillsColumn<?php endif; ?>" id="<?php echo $moduleIdentifier ?>">
<?php if ( $hasTitle ) : ?>
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

      <div class="col-md-6 textColumn">
        <div class="inner">
          <!-- add text column header -->
          <?php if (!empty(get_sub_field('text_header'))) : ?>
            <h3><?php echo get_sub_field('text_header'); ?></h3>
          <?php endif; ?>
          <?php echo get_sub_field('copy'); ?>

          <?php if ( get_sub_field('has_following_links') === 'yes' ) : ?>
          <ul class="desktopOnly">
            <?php
            foreach ( get_sub_field('links') AS $index => $item ) :
              $thisLink = $item['link'];
            ?>
            <li>
              <a href="<?php echo $thisLink['link_url']; ?>" class="arrowLink <?php if (get_sub_field('text_colour') === 'white') : ?>white<?php else : ?>black<?php endif ; ?>" <?php if ($thisLink['link_target'] !== 'self') : ?> target="_blank"<?php endif; ?>><?php echo $thisLink['link_text']; ?></a></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-md-6 imageColumn">

        <?php if ( !empty(get_sub_field('vector')) ) : ?>
        <figure data-flow="creativy,data,technology">
          <lottie-player src="<?php echo get_sub_field('vector'); ?>" background="transparent" speed="1" style="width: 100%;max-width:660px;margin:0 auto;" loop autoplay></lottie-player>
        </figure>
        <?php else : ?>
        <figure>
          <img alt="" src="<?php echo $desktopURL; ?>" class="desktop">
          <img alt="" src="<?php echo $mobileURL; ?>" class="mobile">
        </figure>
        <?php endif; ?>
      </div>

    </div>
  </div>

          <?php if ( get_sub_field('has_following_links') === 'yes' ) : ?>
            <div class="container text-end">
              <div class="row">
                <div class="col">
          <ul class="mobileOnly">
            <?php
            foreach ( get_sub_field('links') AS $index => $item ) :
              $thisLink = $item['link'];
            ?>
            <li>
              <a href="<?php echo $thisLink['link_url']; ?>" class="arrowLink <?php if (get_sub_field('text_colour') === 'white') : ?>white<?php else : ?>black<?php endif ; ?>" <?php if ($thisLink['link_target'] !== 'self') : ?> target="_blank"<?php endif; ?>><?php echo $thisLink['link_text']; ?></a></li>
            <?php endforeach; ?>
          </ul>
                </div>
              </div>
            </div>
          <?php endif; ?>

</section>
