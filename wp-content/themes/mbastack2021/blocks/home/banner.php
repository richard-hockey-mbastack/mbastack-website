<?php 
/*
Home page banner carousel 

gradient_start
gradient_end
gradient_angle
gradient_opacity
landing_slide[
  large
  small
  logo
  desktop_video_format ('vimeo','inline')
  desktop_video_source
  desktop_video_poster (image ID)
  mobile_video_format ('vimeo','inline')
  mobile_video_source
  mobile_video_poster (image ID)
]
has_carousel ('no','yes')
home_slides[
  home_slide [casestudy,post,page]
]
apply_filter ('no','yes') 
*/

// $desktopImage = ( !empty(get_field( 'page_image_desktop', $thisSlidePostID )) ) ? wp_get_attachment_image_src(get_field( 'page_image_desktop', $thisSlidePostID ), 'full')[0] : '';
// get_field( 'intro_text', $thisSlidePostID )
// get_field( 'intro_text' )

$gradientStart = get_field( 'gradient_start');
$gradientEnd = get_field( 'gradient_end');
$gradientAngle = get_field( 'gradient_angle');
$gradientOpacity = get_field( 'gradient_opacity');

// echo "<p class=\"testex\">gradientStart : '$gradientStart'</p>";

$hasCarousel = get_field( 'has_carousel') === 'yes';
$landingSlide = get_field( 'landing_slide');

$small = $landingSlide['small'];
$large = $landingSlide['large'];
$logo = $landingSlide['logo'];
$thisItemMeta = [];

$thisItemMeta = [
  'desktop' => [
    'format' => $landingSlide['desktop_video_format'],
    'source' => $landingSlide['desktop_video_source'],
    'poster' => ( !empty( $landingSlide['desktop_video_poster'] ) ) ? wp_get_attachment_image_src( $landingSlide['desktop_video_poster'], 'full')[0] : ''
  ],
  'mobile' => [
    'format' => $landingSlide[ 'mobile_video_format'],
    'source' => $landingSlide[ 'mobile_video_source'],
    'poster' => ( !empty( $landingSlide[ 'mobile_video_poster'] ) ) ? wp_get_attachment_image_src( $landingSlide[ 'mobile_video_poster'], 'full')[0] : ''
  ]
];

// echo "<pre>".print_r($thisItemMeta,true)."</pre>";
?>

  <style>
    section.pageBanner .gradient{
      background: linear-gradient(<?php echo $gradientAngle; ?>deg, <?php echo $gradientStart; ?> 0%, <?php echo $gradientEnd; ?> 100%);
      opacity: <?php echo $gradientOpacity; ?>;
    }
  </style>

<section class="module bannerTitle pageBanner homepage"  id="homeBanner-01">

<?php if ($hasCarousel) : ?>  
  <p>Has carousel</p>
  <p>display landing slide first, then following slides</p>
<?php else : ?>

  <header>
      <div class="dots"></div>    
      <div class="gradient"></div>
      <div class="background">
        <?php
        $desktop = $thisItemMeta['desktop']['poster'];
        $mobile = $thisItemMeta['mobile']['poster'];

        $noImages = ( empty($desktop) && empty($mobile) );
        $differentImages = ($desktop !== $mobile && !empty($mobile));

        ?>
        <?php if ( !$noImages ) : ?>
        <figure>
          <?php if ( $differentImages ) : ?>
          <img alt="" src="<?php echo $desktop; ?>" class="desktop">
          <img alt="" src="<?php echo $mobile; ?>" class="mobile">
          <?php else : ?>
            <img alt="" src="<?php echo $desktop; ?>">
          <?php endif; ?>
        </figure>
        <?php endif; ?>

          <?php
            switch ( $thisItemMeta['desktop']['format'] ) {
              case 'vimeo' : {
          ?>
          <div class="videoWrapper vimeoWrapper desktop" data-mode="autoplay" data-format="vimeo"  data-width="1292" data-height="727" data-source="<?php echo $thisItemMeta['desktop']['source']; ?>">
            <div class="pr">
            <iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $thisItemMeta['desktop']['source']; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0&dnt=1" frameborder="0" allow="autoplay;" title="MBAstack"></iframe>
            </div>
          </div>

          <div class="videoWrapper vimeoWrapper mobile" data-mode="autoplay" data-format="vimeo"  data-width="404" data-height="540" data-source="<?php echo $thisItemMeta['mobile']['source']; ?>">
            <div class="pr">
            <iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $thisItemMeta['mobile']['source']; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0&dnt=1" frameborder="0" allow="autoplay;" title="MBAstack"></iframe>
            </div>
          </div>

          <?php
              } break;
              case 'youtube' : {
          ?>
          <div class="videoWrapper" data-format="yotube" data-source="<?php echo $thisItemMeta['desktop']['source']; ?>" >
          </div>
          <?php
              } break;
              case 'inline' : {
          ?>
          <div class="videoWrapper inlineWrapper" data-format="inline" data-mode="autoplay"  data-height="1080" data-width="1920" data-source="<?php echo $thisItemMeta['desktop']['source']; ?>">
          <div class="pr"></div>
          </div>
          <?php
              } break;
            }
          ?>

      </div>

    <div class="h1box">
      <div class="container">
        <div class="row">
          <?php if(false): ?><img class="logo" alt="MBAstack - <?php echo $small; ?>" src="<?php echo get_template_directory_uri().$logo; ?>"><?php endif ?>
          <h1>MBAstack</h1>
          <h2><?php echo $large; ?></h2>
        </div>
      </div>
    </div>
  </header>
<?php endif; ?>
</section>

