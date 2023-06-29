<?php

/*
date
category
image
title
synopsis
link
*/

/*
DD.MM.YY "d.m.y"
*/
$queryArguments = [
  'post_type' => 'post',
  'order' => 'DESC',
  'orderby' => 'date',
  'posts_per_page' => 4,
  'page' => 1
];
$leadPost = [];
$thisPagePosts = [];
$the_query = new WP_Query( $queryArguments );
?>

<?php 
if ( $the_query->have_posts() ) {
  $index = 1;
  while ( $the_query->have_posts() ) {
    $the_query->the_post();

    $thisPostImageURL = ( !empty(get_field( 'post_image', $post->ID )) ) ? wp_get_attachment_image_src(get_field( 'post_image', $post->ID ), 'full')[0] : '';
    $thisPostCategories = wp_get_post_categories( $post->ID, [] ); // returns category ID
    $cat = get_category( $thisPostCategories[0] ); // gte FIRST category

    $thisPost = [
      'id' => $post->ID,
      'title' => $post->post_title,
      'synopsis' => get_field( 'post_synopsis', $post->ID ),
      'image' => $thisPostImageURL,
      'date' => get_the_date("d.m.y", $post->ID),
      'category' => $cat->name,
      'permalink'=> '/'.$post->post_name
    ];

    if( $index === 1){
      $leadPost = $thisPost;
    } else {
      $thisPagePosts[] =  $thisPost;
    }
    
    $index++;
  }
}
wp_reset_postdata();
?>

<section class="module latestNews">
    <header>
      <h2>NEWS &amp; AWARDS</h2>
    </header>
    <div class="container">

      <div class="row">
        <div class="col-md-12 item leadItem" data-post-id="<?php echo $leadPost['id']; ?>">
          <div class="container g-0">
            <div class="row">
              <div class="col-md-6">
                <figure><a href="<?php echo $leadPost['permalink']; ?>" title="<?php echo $leadPost['title']; ?>"><img alt="<?php echo $leadPost['title']; ?>" src="<?php echo $leadPost['image']; ?>"></a></figure>
              </div>
              <div class="col-md-6 leadText">
                <div class="extraPadding">
                  <p class="strikethrough"><span class="date"><?php echo $leadPost['date']; ?></span><span class="category"><?php echo $leadPost['category']; ?></span></p>
                  <h3><?php echo $leadPost['title']; ?></h3>
                  <p class="synopsis"><?php echo $leadPost['synopsis']; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <?php foreach($thisPagePosts AS $thisPagePost) : ?>
        <div class="col-md-4 item"  data-post-id="<?php echo $thisPagePost['id']; ?>">
          <div class="outer">
            <figure><a href="<?php echo $thisPagePost['permalink']; ?>" title="<?php echo $thisPagePost['title']; ?>"><img alt="<?php echo $thisPagePost['title']; ?>" src="<?php echo $thisPagePost['image']; ?>"></a></figure>
            <div class="inner">
              <p class="strikethrough"><span class="date"><?php echo $thisPagePost['date']; ?></span><span class="category"><?php echo $thisPagePost['category']; ?></span></p>
              <h3><?php echo $thisPagePost['title']; ?> <a href="<?php echo $thisPagePost['permalink']; ?>" class="arrowLink noText"><span>Read case study</span></a></h3>
              <p class="synopsis"><?php echo $thisPagePost['synopsis']; ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

      </div>    

      <div class="container text-end g-0">
        <a href="/news/" class="arrowLink">more articles</a>
      </div>
    </div>
  </section>
