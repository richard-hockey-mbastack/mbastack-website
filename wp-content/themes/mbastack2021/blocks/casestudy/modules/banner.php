<?php
/*
module: casestudy/modules/banner.php
source/scss/casestudy/modules/banner.scss -> assets/css/styles.css
*/

/*
large
small
gradient_end
gradient_start
gradient_angle
gradient_opacity;
page_image_desktop
page_image_mobile

is_video ('no','yes')
video_format
video_source
*/
$moduleIdentifier = $args['module_identifier'];

$hasImage = ( !empty(get_sub_field('page_image_desktop')) || !empty(get_sub_field('page_image_mobile')) );
$hasVideo = ( get_sub_field('is_video') === 'yes' );
$format = get_sub_field('video_format');
$source = get_sub_field('video_source');

// echo "<pre>".print_r(get_fields(),true)."</pre>";
?>

  <style>
    #<?php echo $moduleIdentifier; ?>{}
    #<?php echo $moduleIdentifier; ?> .gradient{
      background: linear-gradient(<?php echo get_sub_field('gradient_angle'); ?>deg, <?php echo get_sub_field('gradient_start'); ?> 0%, <?php echo get_sub_field('gradient_end'); ?> 100%);
      opacity: <?php echo get_sub_field('gradient_opacity'); ?>;
    }
  </style>
<section class="module pageBanner case-study-banner" id="<?php echo $moduleIdentifier; ?>">
  <header>
      <div class="dots"></div>    
      <div class="gradient"></div>
      
      <div class="background">
        <?php if ($hasImage) : 
        $desktop = get_sub_field('page_image_desktop');
        $mobile = get_sub_field('page_image_mobile');
        if (empty($mobile)) {
          $mobile = $desktop;
        }

        $desktopURL = ( !empty($desktop) ) ? wp_get_attachment_image_src($desktop, 'full')[0] : '';
        $mobileURL = ( !empty($mobile) ) ? wp_get_attachment_image_src($mobile, 'full')[0] : '';
        ?>
        <figure>
          <img alt="<?php echo $args['title']; ?>" src="<?php echo $desktopURL; ?>" class="desktop">
          <img alt="<?php echo $args['title']; ?>" src="<?php echo $mobileURL; ?>" class="mobile">
        </figure>
        <?php endif ?>

        <?php if ( $hasVideo ) : ?>
          <?php
            switch ( $format ) {
              case 'vimeo' : {
          ?>
         <div class="videoWrapper vimeoWrapper" data-mode="autoplay" data-format="vimeo"  data-width="1292" data-height="727">
            <div class="pr">
              <iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $source; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0" frameborder="0" allow="autoplay;"></iframe>
            </div>
        </div>

          <?php
              } break;
              case 'youtube' : {
          ?>
          <div class="videoWrapper" data-format="youtube" data-source="<?php echo $source; ?>" >
          </div>
          <?php
              } break;
              case 'inline' : {
          ?>
          <div class="videoWrapper inlineWrapper" data-format="inline" data-mode="autoplay"  data-height="1080" data-width="1920" data-source="<?php echo $source; ?>">
            <div class="pr"></div>
          </div>
          <?php
              } break;
            }
          ?>
        <?php endif; ?>

      </div>

    <div class="h1box">
      <div class="container">
        <div class="row">
          <h1><?php echo get_sub_field('small'); ?></h1>
          <p><?php echo get_sub_field('large'); ?></p>
          <a class="scrolldown" data-container="section.module" data-target="nextmodule"><span>Go to next module</span></a>
        </div>
      </div>
    </div>
  </header>
</section>
