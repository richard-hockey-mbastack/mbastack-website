<?php
/*
Template Name: mbastack-post
Template Post Type: post
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  $permalink = site_url().'/'.$post->post_name;
  
  /* PAGE STRUCTURE START */
    $bannerURL = wp_get_attachment_image_src(get_field( 'post_image', $post->ID ), 'full')[0];
    $thisPostCategories = wp_get_post_categories( $post->ID, [] ); // returns category ID
    $category = get_category( $thisPostCategories[0] ); // get FIRST category

    $filter = $category->name;
    $hasAuthor = ( !empty(get_field( 'author', $post->ID )) );
    $authorPost = get_field( 'author', $post->ID ); // return wp_post object, not ID
    $authorPostID = $authorPost->ID;
    /*
    forename
    surname
    role
    portrait_photo
    full_photo
    */
    if ( !empty($authorPost) ) {
      $author = [
        'forename' => get_field( 'forename', $authorPost->ID ),
        'surname' => get_field( 'surname', $authorPost->ID ),
        'role' => get_field( 'role', $authorPost->ID ),
        'portrait' => wp_get_attachment_image_src(get_field( 'portrait_photo', $authorPost->ID ), 'full')[0],
        'full' => wp_get_attachment_image_src(get_field( 'full_photo', $authorPost->ID ), 'full')[0]
      ];
    } else {
      $author = [];
    }

    // get first three other posts in same category
    // how to handle only one post (current) in category?
    // maybe display three latest posts in any category

    $queryArguments = [
      'post_type' => 'post',
      'posts_per_page' => 3,
      'post__not_in' => [$post->ID]
    ];
    if ( $filter !== 'all' ) {
        $queryArguments['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( $filter ),
            )
        );
    }
    $the_query = new WP_Query( $queryArguments );
    $sameCategoryPosts = $the_query->post_count;

    if ( $sameCategoryPosts === 0 ) {
      // no other posts in same category, get from all
      $queryArguments = [
        'post_type' => 'post',
        'posts_per_page' => 3,
        'post__not_in' => [$post->ID]
      ];
      $the_query = new WP_Query( $queryArguments );
    }

    $newIDs = [];
    $items = [];
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();

            $post_id = get_the_ID();

            $thisPostImageURL = wp_get_attachment_image_src(get_field( 'post_image', $post_id ), 'full')[0];
            $thisPostCategories = wp_get_post_categories( $post_id, [] ); // returns category ID
            $cat = get_category( $thisPostCategories[0] ); // get FIRST category

            $thisPost = [
                'id' => $post_id,
                'title' => get_the_title(),
                'image' => $thisPostImageURL,
                'synopsis' => get_field( 'post_synopsis', $post_id ),
                'has_external_link' => get_field( 'link_is_internal', $post_id ),
                'link_url' => get_field( 'link_url', $post_id ),
                'link_caption' => get_field( 'link_caption', $post_id ),
                'date' => get_the_date("d.m.y", $post_id),
                'category' => $cat->slug,
                'permalink'=> get_permalink($post_id)
            ];
            $items[] = $thisPost;
            $newIDs[] = $post_id;
        }
    }
    wp_reset_postdata();
?>
<main class="newspost">

<section class="module pageBanner bannerTitle postTitle" data-post-id="<?php echo $post->ID; ?>">
  <header>
    <figure>
      <div class="overlay"></div>
      <img alt="<?php echo $post->post_title; ?>" src="<?php echo $bannerURL; ?>">
    </figure>

    <div class="h1box">
      <div class="container">
        <div class="row">
          <h1><?php echo $post->post_title; ?></h1>
        </div>
      </div>
    </div>

  </header>
</section>

<section class="module singlePost">
  <div class="container">
    <div class="row">
      <div class="col-lg-2 aside">
        <?php if ( $hasAuthor ) : ?>
        <h3>Author:</h3>
        <figure>
          <img alt="<?php echo $author['forename']." ".$author['surname']; ?>" src="<?php echo $author['portrait']; ?>">
          <figcaption><strong><?php echo $author['forename']." ".$author['surname']; ?></strong><br><?php echo $author['role']; ?></figcaption>
        </figure>
        <?php endif; ?>
        <h3>DATE</h3>
        <p><?php echo get_the_date("d.m.y", $post->ID); ?></p>
        <!-- <p>category: '<?php echo strtolower($category->name); ?>'</p> -->
        <h3>SHARE</h3>
        <ul>
          <li><a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $permalink; ?>" class="linkedin" title="share on linkedin" target="_blank"><span>share on linkedin</span></a></li>
          <li><a href="https://twitter.com/intent/tweet?url=<?php echo $permalink; ?>" class="twitter" title="share on twitter" target="_blank"><span>share on twitter</span></a></li>
        </ul>
      </div>

      <div class="col-lg-9 offset-lg-1 content">
      <?php the_content(); ?>
      </div>

    </div>
  </div>
</section>

<section class="module otherPosts" data-category="<?php echo strtolower($category->name); ?>">
  <div class="container">
    <div class="row">
      <!-- <?php echo strtolower($category->name); ?> / <?php echo $sameCategoryPosts; ?> -->

        <?php foreach($items AS $thisPagePost) : ?>
        <div class="col-md-4 newsitem" data-post-id="<?php echo $thisPagePost['id']; ?>" data-xl="<?php echo $thisPagePost['has_external_link']; ?>">

          <div class="outer">
            <figure>
                  <?php if ( $thisPagePost['has_external_link'] === 'external' ) : ?>
                  <a href="<?php echo $thisPagePost['link_url']; ?>"target="_blank" title="<?php echo $thisPagePost['title']; ?>">
                <?php else : ?>
                  <a href="<?php echo $thisPagePost['permalink']; ?>" title="<?php echo $thisPagePost['title']; ?>">
                  <?php endif; ?>
              <img alt="<?php echo $thisPagePost['title']; ?>" src="<?php echo $thisPagePost['image']; ?>">
            </a>
          </figure>
            <div class="inner">
              <p class="strikethrough"><span class="date"><?php echo $thisPagePost['date']; ?></span><span class="category"><?php echo $thisPagePost['category']; ?></span></p>
              <h3><?php echo $thisPagePost['title']; ?></h3>
              <p class="synopsis"><?php echo $thisPagePost['synopsis']; ?></p>

                  <?php if ( $thisPagePost['has_external_link'] === 'external' ) : ?>
                  <a href="<?php echo $thisPagePost['link_url']; ?>" class="arrowLink" target="_blank"><span><?php echo $thisPagePost['link_caption']; ?></span></a>
                <?php else : ?>
                  <a href="<?php echo $thisPagePost['permalink']; ?>" class="arrowLink"><span>read more</span></a>
                  <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

    </div>
  </div>
</section>
<?php
  /* PAGE STRUCTURE END */

  get_footer( null, [] );
}
?>
