<?php
/*
Modules flexuble content template for layout: trinity
blocks/common/modules/trinity

Modules2
  layout: 'trinity'

  sections[
    id
    title
    copy (WSIWYG text)
    icon (image ID)
    vector_icon
  ]
*/

$moduleIdentifier = $args['module_identifier'];
$sections = get_sub_field('sections');
$items = [];
?>

<style>
<?php foreach ( $sections AS $index => $section ) : ?>
#<?php echo $moduleIdentifier; ?> #trinity-<?php echo ($index + 1); ?>{
  z-index: <?php echo ((2 - $index) + 1); ?>
}
<?php endforeach; ?>
</style>

<section class="module trinity" id="<?php echo $moduleIdentifier; ?>">
  <div class="panels">
    <?php foreach ( $sections AS $index => $section ) :
      $items[] = $section['title'];
      $hasImageIcon = ( !empty( $section['icon'] ) );
      $hasVectorIcon = ( !empty( $section['vector_icon'] ) );
      $hasVideo = ( !empty( $section['video'] ) );

      if ( $hasImageIcon ) {
        $iconImageURL = ( !empty($section['icon']) ) ? wp_get_attachment_image_src($section['icon'], 'full')[0] : '';
      }

      if ( $hasVideo ) {
        $hasImageIcon = false;
        $hasVectorIcon = false;
        $videoSource = get_template_directory_uri().$section['video'];
      }
    ?>
    <div class="panel" id="trinity-<?php echo ($index + 1); ?>">
        <div class="nauri">
          <?php if ( $hasImageIcon && !$hasVectorIcon ) : ?>
          <figure>
            <img src="<?php echo $iconImageURL; ?>" alt="<?php echo $section['title']; ?>">
          </figure>
          <?php endif; ?>

          <?php if ( $hasVectorIcon ) : ?>
          <figure>
            <lottie-player src="<?php echo $section['vector_icon']; ?>" background="transparent" speed="1" style="width: 60px; height: 60px;" loop autoplay></lottie-player>
          </figure>
          <?php endif; ?>

          <div class="trinityAnimationClip">
            <div class="background">
              <div class="videoWrapper inlineWrapper" data-format="inline" data-mode="autoplay"  data-height="1080" data-width="1920" data-source="<?php echo $videoSource; ?>">
                <div class="pr"></div>
              </div>
            </div>

            <!--
            <video class="trinityAnimation" autoplay="true" preload="metadata" muted="" nocontrols="" playsinline="" loop="">
              <source type="video/mp4" src="<?php echo $videoSource; ?>">
            </video>
            -->
          </div>
          <?php if ( !$hasVideo ) : ?><h2><?php echo $section['title']; ?></h2><?php endif; ?>
          <?php echo $section['copy']; ?>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

