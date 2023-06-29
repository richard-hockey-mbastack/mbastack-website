<?php
/*
Modules flexuble content template for layout: case_studies_shared_version
blocks/commom/modules/case_studies_shared_version.php
/source/scss/common/sharedCaseStudies.scss
*/

/*
title
title_colour
title_opacity
layout ('grid','carosuel',row')
show_load_more
load_count
case_studies[
  custom post type: 'case_study'
    WP_Post Object
    (
        [ID] => 703
        [post_author] => 1
        [post_date] => 2021-08-13 09:24:23
        [post_date_gmt] => 2021-08-13 09:24:23
        [post_content] => 
        [post_title] => Stellantis LOREM IPSUM
        [post_excerpt] => 
        [post_status] => publish
        [comment_status] => closed
        [ping_status] => closed
        [post_password] => 
        [post_name] => stellantis-lorem-ipsum
        [to_ping] => 
        [pinged] => 
        [post_modified] => 2021-08-13 09:24:24
        [post_modified_gmt] => 2021-08-13 09:24:24
        [post_content_filtered] => 
        [post_parent] => 0
        [guid] => http://local.mbastack/?post_type=casestudy&p=703
        [menu_order] => 0
        [post_type] => casestudy
        [post_mime_type] => 
        [comment_count] => 0
        [filter] => raw
  )
  ACF custom fields:[
    title (text)
    client (text)
    campaign (text)
    synopsis (text)
    desktop_image (image ID)
    mobile_image (image ID)
    has_banner_video ('no','yes')
    banner_video_format ('mp4','youtube','vimeo')
    banner_video_source (video file ID / URL)
    modules[]
  ]
]
preferred_order[
  case_study Post type 'case study' -> post->ID
]
*/

$moduleIdentifier = $args['module_identifier'];
$layoutClass = get_sub_field('layout') . 'Layout';
$caseStudies = get_sub_field('case_studies');

// build list of displayed case study IDs
$shownCaseStudies = [];
if ( gettype($caseStudies) === 'array' && count($caseStudies) > 0) {
  foreach ($caseStudies AS $caseStudy) {
    $shownCaseStudies[] = $caseStudy['case_study'];
  }
}

$showLoadMore = get_sub_field('show_load_more');
$preferredOrder = get_sub_field('preferred_order');
$pageSize = ( $showLoadMore === 'yes' ) ? get_sub_field('load_count') : 0;

$preferedLoadOrder = [];
if ( !empty( $preferredOrder ) ) {
  if ( gettype($preferredOrder) === 'array' && count($preferredOrder) > 0) {
    foreach ($preferredOrder AS $caseStudy) {
      $preferedLoadOrder[] = $caseStudy['case_study'];
    }
  }
} 

// check to see if there are more case studies to display after the initial set
$querySettings = [
  'post_type' => ['casestudy'],
  'post_status' => ['publish'],
  'posts_per_page' => $pageSize,
  'post__not_in' => $shownCaseStudies // skip over case studeis whiuch are already visible
];
$blogposts = new WP_Query($querySettings);
$postsNotShown = $blogposts->found_posts;
$noMorePosts = ( $postsNotShown === 0);

// echo "<pre>".print_r($preferedLoadOrder, true)."</pre>";

?>
<?php if ( !empty(get_sub_field('title') ) ) : ?>
<style>
/*
#<?php echo $moduleIdentifier; ?> .topclip h2{
  <?php if ( !empty(get_sub_field('title_colour') ) ) : ?>
  color: <?php echo get_sub_field('title_colour'); ?>;
  <?php endif; ?>
  <?php if ( !empty(get_sub_field('title_opacity') ) ) : ?>
  opacity: <?php echo get_sub_field('title_opacity'); ?>;
  <?php endif; ?>
}
*/
#<?php echo $moduleIdentifier; ?>.carouselLayout .caseStudyCarousel{width:<?php echo count($caseStudies) * 80; ?>vw;}
</style>
<?php endif; ?>
<section class="module sharedCaseStudies <?php echo $layoutClass; ?>" id="<?php echo $moduleIdentifier; ?>">

  <?php if ( !empty(get_sub_field('title') ) ) : ?>
    <header class="topclip">
      <div class="container">
        <div>
          <h2><?= get_sub_field('title'); ?></h2>
          <div class="controls">
            <div class="controls prevItem"></div>
            <div class="controls nextItem"></div>
          </div>
        </div>
      </div>
    </header>
  <?php endif; ?>

<!--  case study list START -->
<?php if ( get_sub_field('layout') === 'carousel' ) : ?><div class="csfwop"><?php endif; ?>
<div class="csWrapper<?php if ( get_sub_field('layout') === 'carousel' ) : ?> caseStudyCarousel<?php endif; ?> <?php echo $layoutClass; ?>" id="caseStudyList" data-list="<?php echo implode(',',$shownCaseStudies); ?>" data-pagesize="<?php echo $pageSize; ?>" data-clipper="0" data-more="<?php echo $postsNotShown; ?>" data-preferred-order="<?= implode(',',$preferedLoadOrder) ?>">
  <div class="dragTrack"></div>
<?php 
if ( gettype($caseStudies) === 'array' && count($caseStudies) > 0) {
foreach ($caseStudies AS $index => $caseStudy) {

  // get post $caseStudy['case_study']
  $thisCaseStudyID = $caseStudy['case_study'];
  $thisCaseStudy = get_post($thisCaseStudyID); // get post data using post id

  $caseStudyTitle = get_field( 'title', $thisCaseStudyID );

  $desktop = get_field( 'desktop_image', $thisCaseStudyID );
  $mobile = get_field( 'mobile_image', $thisCaseStudyID );

  $noMobile = (empty($mobile));

  $hasVideo = get_field( 'has_banner_video', $thisCaseStudyID );
  $format = get_field( 'banner_video_format', $thisCaseStudyID );
  $source = get_field( 'banner_video_source', $thisCaseStudyID );

  $metadata = [
    'id' => $thisCaseStudyID,
    'title' => $thisCaseStudy->post_title,
    'url' => $thisCaseStudy->post_name,
    'date' => $thisCaseStudy->post_date,
    'client' => get_field( 'client', $thisCaseStudyID ),
    'campaign' => get_field( 'campaign', $thisCaseStudyID ),
    'synopsis' => get_field( 'synopsis', $thisCaseStudyID )
  ];

  $desktopURL = ( !empty($desktop) ) ? wp_get_attachment_image_src($desktop, 'full')[0] : '';
  $mobileURL = ( !empty($mobile) ) ? wp_get_attachment_image_src($mobile, 'full')[0] : '';
?>
  <div class="caseStudy" id="casestudy-<?php echo $metadata['id']; ?>" data-hasvideo="<?php echo $hasVideo; ?>" data-format="<?php echo $format; ?>" data-source="<?php echo $source; ?>">

    <div class="posabs">
      <div class="container">
        <div class="row">
          <div class="col">
            <h2><?php echo $metadata['client']; ?></h2>
            <div class="textOverlay">
              <h3><?php echo $metadata['synopsis']; ?></h3>
              <p class="falseArrow white">read the case study</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="background <?php if ( $hasVideo === 'no' ) : ?>noVideo<?php endif ?>" data-format="<?php echo $format; ?>">
      <figure>
        <?php if ( !empty($desktopURL) ) : ?><img alt="" src="<?php echo $desktopURL; ?>" <?php if ( !$noMobile ) : ?>class="desktop"<?php endif; ?>><?php endif; ?>
        <?php if ( !$noMobile ) : ?><img alt="" src="<?php echo $mobileURL; ?>" class="mobile"><?php endif; ?>
      </figure>
      <?php if ( $hasVideo === 'yes' ) : ?>
      <?php if ( $format === 'vimeo' ) : ?>
      <div class="videoWrapper vimeoWrapper" data-mode="hover" data-width="1292" data-height="727" data-format="vimeo" data-source="<?php echo $source; ?>">
        <div class="pr">
          <iframe class="vimeo" src="https://player.vimeo.com/video/<?php echo $source; ?>?autoplay=1&loop=1&autopause=0&controls=0&background=1&color=000000&portrait=1&portrait=0&byline=0" frameborder="0" allow="autoplay;" title="<?php echo $metadata['client']. ' - '.$metadata['campaign']; ?>"></iframe>
        </div>
      </div>
      <?php endif; ?>

      <?php if ( $format === 'youtube' ) : ?>
      <div class="videoWrapper youtubeWrapper" data-mode="hover" data-format="youtube" data-height="1080" data-width="1920" data-source="<?php echo $source; ?>">
        <div class="pr"></div>
      </div>
    <?php endif; ?>

      <?php if ( $format === 'inline' ) : ?>
      <div class="videoWrapper inlineWrapper" data-mode="hover" data-format="inline" data-height="1080" data-width="1920" data-source="<?php echo $source; ?>">
        <div class="pr"></div>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </div>

    <a href="<?php echo $metadata['url']; ?>" class="csLink"><span><?php echo $metadata['title']; ?></span></a>

  </div>
<?php   
}
}
?>

<!-- insert new div here -->
<!-- <div class="clipper" id="clipper-0"></div> -->
  
</div>
<?php if ( get_sub_field('layout') === 'carousel' ) : ?>
  </div>
  <div class="container text-end g-0">
    <a href="/work/" class="arrowLink followLink white">view our latest case studies</a>
  </div>
<?php endif; ?>
<!--  case study list END -->

<?php if (!$noMorePosts && $showLoadMore === 'yes' ) : ?>
<div id="itemTemplate">
  <div class="posabs">
    <div class="container">
      <div class="row">
        <div class="col">
          <h2>CLIENT</h2>
          <div class="textOverlay">
            <h3>SYNOPSIS</h3>
            <p class="falseArrow white">read the case study</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="background"></div>  
  <a href="URL" class="csLink"><span>TITLE</span></a>
</div>
  
  <div class="controlClipper loadMoreControl" id="moreCaseStudies">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="loadMore">Load more case studies</p>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
</section>
