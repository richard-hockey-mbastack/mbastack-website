<?php
/*
Modules flexuble content template for layout: twoColumnParallax
blocks/common/modules/twoColumnParallax

// google maps directions
//https://gearside.com/easily-link-to-locations-and-directions-using-the-new-google-maps/

Modules2
  layout: 'two_column_parallax'

  module_css_class
  has_parallax_background
  background_image
  first_row_layout ('text', 'image')
  rows[
    text
    bg2
    text_colour
    has_google_map ('image', 'map)'
    google_map_latlong 'LAT,LONG'
    directions
    image
  ]
*/

$moduleIdentifier = $args['module_identifier'];

$hasParallaxBackground = (get_sub_field('has_parallax_background') === 'yes');
$backgroundImage = get_sub_field('background_image');
$backgroundImageURL = ( !empty($backgroundImage) ) ? wp_get_attachment_image_src($backgroundImage, 'full')[0] : '';
$firstRowLayout = ( get_sub_field('first_row_layout') === 'image' ) ? 'first-row-image-text' : '';
$rows = get_sub_field('rows');
?>

<style>
  #<?php echo $moduleIdentifier; ?>{}
</style>
<?php if ( $hasParallaxBackground ) : ?>
<section class="module twoColumnParallax <?php echo $firstRowLayout; ?> parallax-window<?php if (!empty(get_sub_field('module_css_class'))) : echo get_sub_field('module_css_class'); endif; ?>" data-parallax="scroll" data-image-src="<?php echo $backgroundImageURL; ?>" id="<?php echo $moduleIdentifier; ?>">
<?php else : ?>
<section class="module twoColumnParallax <?php echo $firstRowLayout; ?> <?php if (!empty(get_sub_field('module_css_class'))) : echo get_sub_field('module_css_class'); endif; ?>" id="<?php echo $moduleIdentifier; ?>">
<?php endif; ?>

    <?php foreach( $rows AS $index => $row ) :
      $isGoogleWidget = ( $row['has_google_map'] === 'map' );
      if ( $isGoogleWidget ) {
        $coordinates = explode(',',$row['google_map_latlong']);  
      }
      
      // bg2
      // text_colour
      $anchor = $row['anchor'];
      $bg2 = $row['bg2'];
      $hasBackroundColour = ( !empty( $bg2 ) );
      $textColour = $row['text_colour'];
      $imageID = ( !$isGoogleWidget ) ? $row['image'] : 0;
      $imageURL = ( !$isGoogleWidget ) ? ( !empty($imageID)) ? wp_get_attachment_image_src($imageID, 'full')[0]: '' : '';
      $testColumnWidth = ( $isGoogleWidget ) ? 'col-lg-4' : 'col-lg-6';
      $directions = $row['directions'];


    ?>
    <div class="pair <?php echo $textColour; ?>" <?php if ( $hasBackroundColour ) : ?>style="background-color:<?php echo $bg2; ?>;"<?php endif; ?> <?php if ( !empty($anchor) ) :?>id="<?= $anchor ?>"<?php endif; ?>>
      <?php if ( $isGoogleWidget ) : ?>
        <div class="image"><div class="contactMap" id="contactMap<?php echo $index; ?>" data-directions="<?php echo $directions; ?>" data-lat="<?php echo $coordinates[0]; ?>" data-long="<?php echo $coordinates[1]; ?>" data-zoom="17"></div></div>
      <?php else : ?>
        <div class="image"><figure><img src="<?php echo $imageURL; ?>" alt=""></figure></div>
      <?php endif; ?>
      <div class="text">
        <div class="tbox">
          <?php echo $row['text']; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

</section>
