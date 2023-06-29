<?php
/*
Modules flexuble content template for layout: news_home_page_version
blocks/common/modules/news_home_page_version
*/

/*
modulefields:
  title
  show_filter
  article_initial_count
  has_load_more
  article_load_count
  show_following_link
  following_link_caption
  following_link_url

POST fields:
  post_image (id) for thumbnails / page header
  video_file [object]
  post_synopsis
  author (custom post type 'people' id)
  link_is_internal ('internal','external')
  external_site_logo
  link_caption
  link_url
*/


// category filters - on page load no filters are applied
// query on each filter see if there are any articles available
$allCategoryFilters = ['Environment','Award','Insight','Data','CXM','Film','Industry','News','Campaign launch'];

$filterList = [];
$leadPost = null;
$thisPosts = [];
$visiblePosts = [];

$moduleIdentifier = $args['module_identifier'];

$initialArticleCount = (get_sub_field('article_initial_count') === 'home' ) ? 4 : 7;
$showLoadMore = (get_sub_field('has_load_more') === 'yes' );
$loadMoreCount = get_sub_field('article_load_count');
$showFilter = (get_sub_field('show_filter') === 'yes' );
$ShowFollowingLink = (get_sub_field('show_following_link') === 'yes' );
// following_link_caption
// following_link_url

// check to see if there are more news stories available
$queryArguments = [
  'post_type' => 'post',
];

$the_query = new WP_Query( $queryArguments );
$foundPosts = $the_query->post_count;

// all posts have been displayed on page load
// do not show 'load more' control
if ($foundPosts < $initialArticleCount) {
  $showLoadMore = false;
}

// -------------------------------------------
// get post categories

$categoryFilters = [];  
$queryArguments = [
  'post_type' => 'post',
  'posts_per_page' => -1
];
$the_query = new WP_Query( $queryArguments );

if ( $the_query->have_posts() ) {
  while ( $the_query->have_posts() ) {
    $the_query->the_post();

    $thisPostCategories = ( !empty($post->ID) ) ? wp_get_post_categories( $post->ID, [] ) : false; // returns category ID
    $cat = ($thisPostCategories) ? get_category( $thisPostCategories[0] ) : '';

    if (!in_array($cat->name, $categoryFilters)) {
      $categoryFilters[] = $cat->name;
      $filterList[] =  strtolower($cat->name);  

    }
  }
}
wp_reset_postdata();

// -------------------------------------------
// check for featured post
// [show_selected] => yes
// [selected_news_post] => WP_Post Object

$queryArguments = [
  'post_type' => 'post',
  'order' => 'DESC',
  'orderby' => 'date',
  'post_status'=> 'publish',
  'posts_per_page' => $initialArticleCount,
  'suppress_filters' => true,
  'ignore_sticky_posts' => true //**
];

$showSelectedPost = get_sub_field('show_selected') === 'yes';

if ( $showSelectedPost ) {
  $selectedPost = get_sub_field('selected_news_post');
  $selectedPostID = $selectedPost->ID;
  $thisPostVideo = ( !empty(get_field( 'video_file', $selectedPostID )) ) ? get_field( 'video_file', $selectedPostID )['url'] : false;
  $thisPostImageURL = ( !empty(get_field( 'post_image', $selectedPostID )) ) ? wp_get_attachment_image_src(get_field( 'post_image', $selectedPostID ), 'full')[0] : '';
  $thisExternalSiteLogo = ( !empty(get_field( 'external_site_logo', $selectedPostID )) ) ? wp_get_attachment_image_src(get_field( 'external_site_logo', $selectedPostID ), 'full')[0] : '';
  $thisPostCategories = ( !empty($selectedPostID) ) ? wp_get_post_categories( $selectedPostID, [] ) : false; // returns category ID
  $cat = ($thisPostCategories) ? get_category( $thisPostCategories[0] ) : '';

  $leadPost = [
      'id' => $selectedPost->ID,
      'title' => $selectedPost->post_title,
      'synopsis' => get_field( 'post_synopsis', $selectedPostID ),
      'has_external_link' => get_field( 'link_is_internal', $selectedPostID ), // 'internal','external'
      'external_site_logo' => $thisExternalSiteLogo,
      'link_url' => get_field( 'link_url', $selectedPostID ),
      'link_caption' => get_field( 'link_caption', $selectedPostID ),
      'image' => $thisPostImageURL,
      'video' => $thisPostVideo,
      'date' => get_the_date("d.m.y", $selectedPostID),
      'category' => $cat->name,
      'permalink'=> '/'.$selectedPost->post_name
    ];

    $visiblePosts[] = $selectedPostID;

    // adjust news posr query to omit selected post
    $queryArguments['post__not_in'] = [ $selectedPostID ];
    $queryArguments['posts_per_page'] = $initialArticleCount;
}
wp_reset_postdata();


// -------------------------------------------

// get_sub_field['select_following_news_items'] => 'no',;yes
// get_sub_field['following_news_posts'] => []
$selectFollowingPosts = get_sub_field('select_following_news_items') === 'yes';
// echo "<pre>".print_r( get_fields(), true )."</pre>";

// -------------------------------------------

// get 4/7 latest news articles to display on page load
$the_query = new WP_Query( $queryArguments );

if ( $the_query->have_posts() ) {
  $index = 1;

  // echo "<pre>".print_r($the_query,true)."</pre>";
  while ( $the_query->have_posts() ) {
    $the_query->the_post();
    $thisPostVideo = ( !empty(get_field( 'video_file', $post->ID )) ) ? get_field( 'video_file', $post->ID )['url'] : false;
    $thisPostImageURL = ( !empty(get_field( 'post_image', $post->ID )) ) ? wp_get_attachment_image_src(get_field( 'post_image', $post->ID ), 'full')[0] : '';
    $thisExternalSiteLogo = ( !empty(get_field( 'external_site_logo', $post->ID )) ) ? wp_get_attachment_image_src(get_field( 'external_site_logo', $post->ID ), 'full')[0] : '';
    $thisPostCategories = ( !empty($post->ID) ) ? wp_get_post_categories( $post->ID, [] ) : false; // returns category ID
    $cat = ($thisPostCategories) ? get_category( $thisPostCategories[0] ) : '';

    $thisPost = [
      'id' => $post->ID,
      'title' => $post->post_title,
      'synopsis' => get_field( 'post_synopsis', $post->ID ),
      'has_external_link' => get_field( 'link_is_internal', $post->ID ), // 'internal','external'
      'external_site_logo' => $thisExternalSiteLogo,
      'link_url' => get_field( 'link_url', $post->ID ),
      'link_caption' => get_field( 'link_caption', $post->ID ),
      'image' => $thisPostImageURL,
      'video' => $thisPostVideo,
      'date' => get_the_date("d.m.y", $post->ID),
      'category' => $cat->name,
      'permalink'=> '/'.$post->post_name
    ];

    $visiblePosts[] = $post->ID;

    // treat latest post differently - mat be necessary to allow any post/item to be selected abritrarily
    // make ltest post lead item, inless a specific post has been selected
    if( !$showSelectedPost && $index === 1){
      $leadPost = $thisPost;
    } else {
      $thisPosts[] =  $thisPost;
    }

    $index++;
  }
}
wp_reset_postdata();
?>

<style>
#<?php echo $moduleIdentifier; ?>{}
</style>
<script>
  const postImageList = [<?php 
  echo "'".$leadPost['image']."'";
  if ( count($thisPosts) > 0) {
    echo ",";
  }
  $fpi = '';
  foreach($thisPosts AS $fp) {
    if( $fpi !== '')
      $fpi = $fpi . ",";
    $fpi = $fpi . '\''.$fp['image'].'\'';
  }
  echo $fpi;
  ?>];
</script>
  <!-- news.php -->
	<section class="module latest-news" id="<?php echo $moduleIdentifier; ?>" data-visible="<?php echo implode(',',$visiblePosts); ?>" data-current-filter="all" data-filters="<?php echo implode(',',$filterList); ?>" data-pagesize="<?php echo $loadMoreCount; ?>" data-initial="<?php echo $initialArticleCount; ?>" data-filter-clipper="filterClipper-<?php echo $moduleIdentifier; ?>" data-lead-item="leadItem-<?php echo $moduleIdentifier; ?>" data-follow-item-target="follow-item-target-<?php echo $moduleIdentifier; ?>" data-follow-item-clipper="follow-item-clipper-<?php echo $moduleIdentifier; ?>"
    data-load-more-control="load-more-<?php echo $moduleIdentifier; ?>" data-filter-control="filter-control-<?php echo $moduleIdentifier; ?>" data-lead-item-template="leadTemplate-<?php echo $moduleIdentifier; ?>" data-following-item-template="followTemplate-leadTemplate-<?php echo $moduleIdentifier; ?>">

    <?php if( !empty(get_sub_field('title')) ) : ?>
    <header class="topclip">
      <div class="container">
        <div>
          <h2><?php echo get_sub_field('title'); ?></h2>
        </div>
      </div>
    </header>
  <?php endif; ?>

    <?php if ( $showFilter) : ?> 
    <div class="container z2">
      <div class="row">
        <div class="col">
          <div class="filter" id="filter-control-<?php echo $moduleIdentifier; ?>">
            <p class="control"><span class="f1">FILTER: </span><span class="category">CATEGORY</span><span class="icon"></span></p>
            <div class="clipper">
              <ul>
                <li class="active" ><button data-action="all" data-parent="<?php echo $moduleIdentifier; ?>">All</button></li>
                <?php foreach ($categoryFilters AS $filter) : ?>
                <li><button data-action="<?php echo strtolower($filter); ?>" data-parent="<?php echo $moduleIdentifier; ?>"><?php echo $filter; ?></button></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

		<div class="container dataClipper" id="filterClipper-<?php echo $moduleIdentifier; ?>">
      <div class="row leadItem" id="leadItem-<?php echo $moduleIdentifier; ?>">

        <div class="col-md-12 newsitem <?php if (empty($leadPost['image'])) : ?>imageNotPresent<?php endif; ?>" data-post-id="<?php echo $leadPost['id']; ?>" data-xl="<?php echo $leadPost['has_external_link']; ?>">

          <div class="container g-0">
            <div class="row">
              <div class="col-md-6">
                
                <?php if ( $leadPost['video'] ) : ?>
                <div class="videoFrame" data-type="mp4" data-url="<?= $leadPost['video'] ?>">
                  
                    <?php if ( $leadPost['has_external_link'] === 'external' ) : ?>
                    <a href="<?php echo $leadPost['link_url']; ?>"target="_blank" title="<?php echo $leadPost['title']; ?>">
                  <?php else : ?>
                    <a href="<?php echo $leadPost['permalink']; ?>" title="<?php echo $leadPost['title']; ?>">
                    <?php endif; ?>

                  <?php // only set up for inline MP4 video files, maybe add support for youtube/vimeo embeds in future ?>
                  <video autoplay="" muted="" playsinline="" loop="" width="100%" height="100%" preload="none" poster="<?php echo $leadPost['image']; ?>"><source src="<?= $leadPost['video'] ?>" type="video/mp4"></video>

                  </a>
                    <?php if( !empty( $leadPost['external_site_logo'] ) ) : ?><img alt="" src="<?php echo $leadPost['external_site_logo']; ?>" class="externalLogo"><?php endif; ?>

                </div>

                <?php else : ?>
                  <figure>
                    <?php if ( $leadPost['has_external_link'] === 'external' ) : ?>
                    <a href="<?php echo $leadPost['link_url']; ?>"target="_blank" title="<?php echo $leadPost['title']; ?>">
                  <?php else : ?>
                    <a href="<?php echo $leadPost['permalink']; ?>" title="<?php echo $leadPost['title']; ?>">
                    <?php endif; ?>
                    <img alt="<?php echo $leadPost['title']; ?>" src="<?php echo $leadPost['image']; ?>" class="post">
                    </a>
                    
                    <?php if( !empty( $leadPost['external_site_logo'] ) ) : ?><img alt="" src="<?php echo $leadPost['external_site_logo']; ?>" class="externalLogo"><?php endif; ?>
                  </figure>
                <?php endif ?>


              </div>
              <div class="col-md-6 leadText">
                <div class="extraPadding">
                  <p class="strikethrough"><span class="date"><?php echo $leadPost['date']; ?></span><span class="category"><?php echo $leadPost['category']; ?></span></p>
                  <h3><?php echo $leadPost['title']; ?></h3>
                  <p class="synopsis"><?php echo $leadPost['synopsis']; ?></p>

                  <?php if ( $leadPost['has_external_link'] === 'external' ) : ?>
                  <a href="<?php echo $leadPost['link_url']; ?>" class="arrowLink" target="_blank"><span><?php echo $leadPost['link_caption']; ?></span></a>
                <?php else : ?>
                  <a href="<?php echo $leadPost['permalink']; ?>" class="arrowLink"><span>read more</span></a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row followOnItems" id="follow-item-target-<?php echo $moduleIdentifier; ?>">
        <?php foreach($thisPosts AS $thisPost) : ?>

        <div class="col-md-4 newsitem <?php if (empty($thisPost['image'])) : ?>imageNotPresent<?php endif; ?>"  data-post-id="<?php echo $thisPost['id']; ?>" data-xl="<?php echo $thisPost['has_external_link']; ?>">

          <div class="outer">
<?php if ( $thisPost['video'] ) : ?>
<div class="videoFrame" data-type="mp4" data-url="<?= $thisPost['video'] ?>">

                    <?php if ( $thisPost['has_external_link'] === 'external' ) : ?>
                    <a href="<?php echo $thisPost['link_url']; ?>"target="_blank" title="<?php echo $thisPost['title']; ?>">
                  <?php else : ?>
                    <a href="<?php echo $thisPost['permalink']; ?>" title="<?php echo $thisPost['title']; ?>">
                    <?php endif; ?>

                  <?php // only set up for inline MP4 video files, maybe add support for youtube/vimeo embeds in future ?>
                  <video autoplay="" muted="" playsinline="" loop="" width="100%" height="100%" preload="none"><source src="<?= $thisPost['video'] ?>" type="video/mp4"></video>

                  </a>
                    <?php if( !empty( $thisPost['external_site_logo'] ) ) : ?><img alt="" src="<?php echo $thisPost['external_site_logo']; ?>" class="externalLogo"><?php endif; ?>

</div>
<?php else : ?>
            <figure>
                  <?php if ( $thisPost['has_external_link'] === 'external' ) : ?>
                  <a href="<?php echo $thisPost['link_url']; ?>"target="_blank" title="<?php echo $thisPost['title']; ?>">
                <?php else : ?>
                  <a href="<?php echo $thisPost['permalink']; ?>" title="<?php echo $thisPost['title']; ?>">
                  <?php endif; ?>
              <img alt="<?php echo $thisPost['title']; ?>" src="<?php echo $thisPost['image']; ?>" class="post">
            </a>
            <?php if( !empty( $thisPost['external_site_logo'] ) ) : ?><img alt="" src="<?php echo $thisPost['external_site_logo']; ?>" class="externalLogo"><?php endif; ?>
          </figure>
<?php endif ?>

            <div class="inner">
              <p class="strikethrough"><span class="date"><?php echo $thisPost['date']; ?></span><span class="category"><?php echo $thisPost['category']; ?></span></p>
              <h3><?php echo $thisPost['title']; ?></h3>
              <p class="synopsis"><?php echo $thisPost['synopsis']; ?></p>

                  <?php if ( $thisPost['has_external_link'] === 'external' ) : ?>
                  <a href="<?php echo $thisPost['link_url']; ?>" class="arrowLink" target="_blank"><span><?php echo $thisPost['link_caption']; ?></span></a>
                <?php else : ?>
                  <a href="<?php echo $thisPost['permalink']; ?>" class="arrowLink"><span>read more</span></a>
                  <?php endif; ?>
            </div>
          </div>
        </div>

        <?php endforeach; ?>

      </div>
      <div class="row followOnItems dataClipper closed" id="follow-item-clipper-<?php echo $moduleIdentifier; ?>">
      </div>

      <?php if ( $showLoadMore ) : ?>
      <div class="controlClipper loadMoreControl" id="load-more-<?php echo $moduleIdentifier; ?>" data-parent="<?php echo $moduleIdentifier; ?>">
        <div class="container">
          <div class="row">
            <div class="col">
              <p class="loadMore">Load more News Items</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 template" data-post-id="ID" id="leadTemplate-<?php echo $moduleIdentifier; ?>">
        <div class="container g-0">
          <div class="row">
            <div class="col-md-6">
              <figure>
                <a href="PERMALINK" title="TITLE" class="post"></a>
                <!-- <img alt="" src="SRC" class="externalLogo"> -->
              </figure>
            </div>
            <div class="col-md-6 leadText">
              <div class="extraPadding">
                <p class="strikethrough"><span class="date">XX.XX.XXXX</span><span class="category">CATEGORY</span></p>
                <h3>TITLE</h3>
                <p class="synopsis">SYNOPSIS</p>
                <a href="URL" class="arrowLink"><span>CAPTION</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 template" id="followTemplate-leadTemplate-<?php echo $moduleIdentifier; ?>" data-post-id="ID" >
        <div class="outer">
          <figure>
            <a href="PERMALINK" title="TITLE"></a>
            <!-- <img alt="" src="SRC" class="externalLogo"> -->
          </figure>
          <div class="inner">
            <p class="strikethrough"><span class="date">XX.XX.XXXX</span><span class="category">CATEGORY</span></p>
            <h3>TITLE</h3>
            <p class="synopsis">SYNOPSIS</p>
            <a href="URL" class="arrowLink"><span>CAPTION</span></a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if ( $ShowFollowingLink ) : ?>
			<div class="container text-center">
					<a href="<?php echo get_sub_field('following_link_url'); ?>" class="followLink"><?php echo get_sub_field('following_link_caption'); ?></a>
			</div>
      <?php endif; ?>

		</div>
	</section>
  <script>
      var newsModule = '<?php echo $moduleIdentifier; ?>';
  </script>