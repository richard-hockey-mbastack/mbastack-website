<?php
/*
module: common/page-banner/
source/scss/common/page-banner.scss -> assets/css/styles.css
*/

/*
large
small
gradient_end
gradient_start
gradient_angle
gradient_opacity;
link_caption *
second_title *
page_image_desktop
page_image_mobile
has-video
format
source

*/
$hasImage = ( !empty($args['desktop']) || !empty($args['mobile']) );
$hasVideo = ( $args['has-video'] === 'yes' );

/**/
// echo "<pre>".print_r($args,true)."</pre>\n";
// echo "<p class=\"testex\">has video: ".$args['has-video']."</p>\n";
/**/
?>
  <style>
    section.pageBanner .gradient{
      background: linear-gradient(<?php echo get_field('gradient_angle'); ?>deg, <?php echo get_field('gradient_start'); ?> 0%, <?php echo get_field('gradient_end'); ?> 100%);
      opacity: <?php echo get_field('gradient_opacity'); ?>;
    }
  </style>

<section class="module bannerTitle pageBanner">

  <header>
      <div class="dots"></div>    
      <div class="gradient"></div>
      
      <div class="background">
        <?php if ($hasImage) : 
        $desktop = $args['desktop'];
        $mobile = $args['mobile'];
        if (empty($mobile)) :
        $mobile = $desktop;
        endif;

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
            switch ( $args['format'] ) {
              case 'vimeo' : {
          ?>
         <div class="videoWrapper vimeoWrapper" data-mode="autoplay" data-format="vimeo"  data-width="1292" data-height="727" data-source="<?php echo $args['source']; ?>">
            <div class="pr">
              <iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $args['source']; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0" frameborder="0" allow="autoplay;"></iframe>
            </div>
        </div>

          <?php
              } break;
              case 'youtube' : {
          ?>
          <div class="videoWrapper" data-format="<?php echo $args['format']; ?>" data-source="<?php echo $args['source']; ?>" >
          </div>
          <?php
              } break;
              case 'inline' : {
          ?>
          <div class="videoWrapper inlineWrapper" data-format="inline" data-mode="autoplay"  data-height="1080" data-width="1920" data-source="<?php echo $args['source']; ?>">
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
          <h1><?php echo $args['large']; ?></h1>
          <p class="intro"><?php echo $args['small']; ?></p>
          
        </div>
      </div>
    </div>
  </header>
</section>
