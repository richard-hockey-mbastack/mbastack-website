<?php
/*
Template Name: default post
*/
// default post template
// currently handles news/blog posts
// post_type === 'post'

// needs to handle People as well
// post_type === 'person'

if ( have_posts() ) {
  the_post();
  // populates variable $post
  // post id = $post->ID

  get_header( null, [] ); 

  // echo "<p class=\"testex\">".$post->post_type."</p>\n";

  switch( $post->post_type) {
    case 'post' : {
  $permalink = site_url().'/'.$post->post_name;

  /* PAGE STRUCTURE START */
    $bannerURL = ( !empty(get_field( 'post_image', $post->ID )) ) ? wp_get_attachment_image_src(get_field( 'post_image', $post->ID ), 'full')[0] : '';
    $thisPostCategories = wp_get_post_categories( $post->ID, [] ); // returns category ID
    $category = get_category( $thisPostCategories[0] ); // get FIRST category

    $filter = $category->name;
    $hasAuthor = ( !empty(get_field( 'author', $post->ID )) );
    
    /*
    forename
    surname
    role
    portrait_photo
    full_photo
    */
    
    if ( $hasAuthor ) {
      $authorPost = ($hasAuthor) ? get_field( 'author', $post->ID ) : false; // return wp_post object, not ID
      $author = [
        'forename' => get_field( 'forename', $authorPost->ID ),
        'surname' => get_field( 'surname', $authorPost->ID ),
        'role' => get_field( 'role', $authorPost->ID ),
        'portrait' => ( !empty(get_field( 'portrait_photo', $authorPost->ID )) ) ? wp_get_attachment_image_src(get_field( 'portrait_photo', $authorPost->ID ), 'full')[0] : '',
        'full' => ( !empty(get_field( 'full_photo', $authorPost->ID )) ) ? wp_get_attachment_image_src(get_field( 'full_photo', $authorPost->ID ), 'full')[0] : ''
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
            $thisExternalSiteLogo = ( !empty(get_field( 'external_site_logo', $post->ID )) ) ? wp_get_attachment_image_src(get_field( 'external_site_logo', $post->ID ), 'full')[0] : '';
            $thisPostCategories = wp_get_post_categories( $post_id, [] ); // returns category ID
            $cat = get_category( $thisPostCategories[0] ); // get FIRST category

            $thisPost = [
                'id' => $post_id,
                'title' => get_the_title(),
                'image' => $thisPostImageURL,
                'synopsis' => get_field( 'post_synopsis', $post_id ),
                'has_external_link' => get_field( 'link_is_internal', $post_id ),
                'external_site_logo' => $thisExternalSiteLogo,
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
    <div class="dots"></div>    
    <div class="gradient"></div>
    <div class="background">
      <?php if ( !empty($bannerURL) ) : ?>
      <figure>
        <img alt="<?php echo $post->post_title; ?>" src="<?php echo $bannerURL; ?>">
      </figure>
      <?php endif; ?>
    </div>
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
        <div class="col-md-4 newsitem <?php if (empty($thisPagePost['image'])) : ?>imageNotPresent<?php endif; ?>" data-post-id="<?php echo $thisPagePost['id']; ?>" data-xl="<?php echo $thisPagePost['has_external_link']; ?>">

          <div class="outer">
            <figure>
                  <?php if ( $thisPagePost['has_external_link'] === 'external' ) : ?>
                  <a href="<?php echo $thisPagePost['link_url']; ?>"target="_blank" title="<?php echo $thisPagePost['title']; ?>">
                <?php else : ?>
                  <a href="<?php echo $thisPagePost['permalink']; ?>" title="<?php echo $thisPagePost['title']; ?>">
                  <?php endif; ?>
              <img alt="<?php echo $thisPagePost['title']; ?>" src="<?php echo $thisPagePost['image']; ?>" class="post">
            </a>
            <?php if( !empty( $thisPagePost['external_site_logo'] ) ) : ?><img alt="" src="<?php echo $thisPagePost['external_site_logo']; ?>" class="externalLogo"><?php endif; ?>
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
    } break;

    case 'person' : {
    // get page ID for people page
    // get banner img + title


    // get person ID
    $thisPersonID = $post->ID;

    $n4 = get_post_field( 'post_name', $thisPersonID);

    $person = [
      'id' => $thisPersonID,
      'slug' => get_post_field( 'post_name', $thisPersonID),
      'forename' => get_field('forename', $thisPersonID),
      'surname' => get_field('surname', $thisPersonID),
      'role' => get_field('role', $thisPersonID),
      'bio' => get_field('bio', $thisPersonID),
      'full' => get_field('full_photo', $thisPersonID),
      'portrait' => get_field('portrait_photo', $thisPersonID),
      'linkedin' => get_field('linkedin', $thisPersonID),
      'twitter' => get_field('twitter', $thisPersonID),
      'instagram' => get_field('instagram', $thisPersonID),
      'facebook' => get_field('facebook', $thisPersonID),
      'email' => get_field('email', $thisPersonID),
      'phone' => get_field('phone', $thisPersonID),
      'slug' => get_post_field( 'post_name', $thisPersonID)
    ];

    $fullname = $person['forename'].' '.$person['surname'];
    $thisPersonPortrait = ( !empty($person['portrait']) ) ? wp_get_attachment_image_src($person['portrait'], 'full')[0] : '';
    $thisPersonFull = ( !empty($person['full']) ) ? wp_get_attachment_image_src($person['full'], 'full')[0] : '';

    $peoplePageID = url_to_postid('/people/');
    // get 

    $moduleIdentifier = 'people-preview';
    $peopleModule = get_field('modules',$peoplePageID)[0];

    // modules
    $textStyle = $peopleModule['text_colour'];
    $hasTitle = !empty( $peopleModule['title']);
    $hasBioOverlayTriggers = ( $peopleModule['show_bio_overlay'] === 'yes' ) ;
    $hasFollowingLink = ( $peopleModule['has_following_link'] === 'yes' ) ;
    $hasBackgroundColour = !empty($peopleModule['background_colour']);
    $backgroundColour = $peopleModule['background_colour'];
    $introHeader = $peopleModule['intro_header'];
    $introCopy = $peopleModule['intro_copy'];
    $hasIntroHeader = !empty($introHeader);
    $hasIntroCopy = !empty($introCopy);
    $isCarousel = ($peopleModule['is_carousel'] === 'yes');

    $peopleCTP = $peopleModule['people_to_show'];
    $noPeople = empty($peopleCTP);

    if ( !$noPeople ) {
      foreach( $peopleCTP AS $peopleX ) {
        $thisPersonID = $peopleX['person'];
        $thisPerson = get_post($thisPersonID);

        $peoplePosts[] = [
          'id' => $thisPersonID,
          'slug' => get_post_field( 'post_name', $thisPersonID),
          'forename' => get_field('forename', $thisPersonID),
          'surname' => get_field('surname', $thisPersonID),
          'role' => get_field('role', $thisPersonID),
          'bio' => get_field('bio', $thisPersonID),
          'full' => get_field('full_photo', $thisPersonID),
          'portrait' => get_field('portrait_photo', $thisPersonID),
          'linkedin' => get_field('linkedin', $thisPersonID),
          'twitter' => get_field('twitter', $thisPersonID),
          'instagram' => get_field('instagram', $thisPersonID),
          'facebook' => get_field('facebook', $thisPersonID),
          'email' => get_field('email', $thisPersonID),
          'phone' => get_field('phone', $thisPersonID),
          'slug' => get_post_field( 'post_name', $thisPersonID)
        ];
      }

    }

    // echo "<p class=\"testex\">TESTEX</p>\n";

    /* PAGE STRUCTURE START */
    get_template_part( 'blocks/common/page-banner','',['type' => get_field('page_title_or_intro_text',$peoplePageID), 'title' => get_field('page_title',$peoplePageID), 'intro' => get_field('intro_text',$peoplePageID), 'desktop' => get_field('page_image_desktop',$peoplePageID), 'mobile' => get_field('page_image_mobile',$peoplePageID), 'has-video' => get_field('has_video',$peoplePageID), 'format' => get_field('video_format',$peoplePageID), 'source' => get_field('video_source',$peoplePageID), 'apply_filter' => get_field('apply_filter',$peoplePageID) ]);

?>
    <div class="bioOverlay open" id="bioOverlay">
      <div class="white"></div>
      <div class="blue">
        <div class="container">
          <div class="row">

            <div class="col-md-6 photo">
              <figure>
                <img id="bioPhoto" alt="<?php echo $fullname.' - '.$person['role']; ?>" src="<?php echo $thisPersonFull; ?>">
              </figure>
            </div>

            <div class="col-md-6 info">
              <h2 id="bioName"><?php echo $fullname; ?></h2>
              <h3 id="bioRole"><?php echo $person['role']; ?></h3>
              <div id="bioCopy">
                <p><?php echo $person['bio']; ?></p>
              </div>

              <div class="socialLinks">
                <h4>Contact <span class="contactForename"><?php echo $person['forename']; ?></span></h4>
                <ul>
                  <?php if (!empty( $person['linkedin'] )) : get_template_part( 'blocks/snippets/linkedin','',['linkedin' => $person['linkedin'] ]); endif; ?>
                  <?php if (!empty( $person['twitter'] )) : get_template_part( 'blocks/snippets/twitter','',['twitter' => $person['twitter'] ]); endif; ?>
                  <?php if (!empty( $person['instagram'] )) : get_template_part( 'blocks/snippets/instagram','',['instagram' => $person['instagram'] ]); endif; ?>
                  <?php if (!empty( $person['facebook'] )) : get_template_part( 'blocks/snippets/facebook','',['facebook' => $person['facebook'] ]); endif; ?>
                  <?php if (!empty( $person['email'] )) : get_template_part( 'blocks/snippets/email','',['email' => $person['email'] ]); endif; ?>
                </ul>
              </div>

              <ul class="paging">
                <li class="previous"><button class="prevPerson"><span>Previous</span></button></li>
                <li class="next"><button class="nextPerson"><span>Next</span></button></li>
              </ul>
            </div>

          </div>
        </div>
    </div>
  </div>

<style>
#<?php echo $moduleIdentifier; ?>{}
<?php if ( $hasBackgroundColour ) : ?>
#<?php echo $moduleIdentifier; ?>{
  background-color: <?php echo $backgroundColour; ?>
}
<?php endif; ?>
<?php if ( $hasTitle ) : ?>
#<?php echo $moduleIdentifier; ?> .topclip h2{
<?php if ( !empty($peopleModule['title_colour'] ) ) : ?>
  color: <?php echo $peopleModule['title_colour']; ?>;
<?php endif; ?>
<?php if ( !empty($peopleModule['title_opacity'] ) ) : ?>
  opacity: <?php echo $peopleModule['title_opacity']; ?>;
<?php endif; ?>
}
<?php endif; ?>
</style>

<section class="module people peopleGrid bioOverlayTrigger <?php echo $textStyle; ?>" id="<?php echo $moduleIdentifier; ?>" data-context="<?php echo $peoplePageID; ?>">
<?php if ( $hasTitle ) : ?>
  <header class="topclip">
    <div class="container">
      <div>
        <h2><?php echo $peopleModule['title']; ?></h2>
      </div>
    </div>
  </header>
<?php endif; ?>

    <?php if ( $hasIntroHeader || $hasIntroCopy) : ?> 
    <div class="container teamIntro">
      <div class="row">

        <?php if ( $hasIntroHeader && $hasIntroCopy) : ?>
        <div class="col-lg-6">
          <h3><?php echo $introHeader; ?></h3>
        </div>
        <div class="col-lg-6">
          <?php echo $introCopy; ?>
        </div>
        <?php endif; ?>

        <?php if ( $hasIntroHeader && !$hasIntroCopy) : ?>
        <div class="col">
          <h3><?php echo $introHeader; ?></h3>
        </div>
        <?php endif; ?>

        <?php if ( $hasIntroCopy && !$hasIntroHeader) : ?>
        <div class="col">
          <?php echo $introCopy; ?>
        </div>
        <?php endif; ?>

      </div>
    </div>    
  <?php endif; ?>

<!-- static -->

<!-- not carousel START -->
    <div class="container">
      <div class="row">

<?php
      if( !$noPeople ) :
      foreach($peoplePosts AS $index => $person) :
        $thisPersonPortrait = ( !empty($person['portrait']) ) ? wp_get_attachment_image_src($person['portrait'], 'full')[0] : '';
        $thisPersonFull = ( !empty($person['full']) ) ? wp_get_attachment_image_src($person['full'], 'full')[0] : '';
      ?>
        <div class="item col-md-4 <?php if ($n4 === $person['slug']) : ?>open<?php endif; ?>" id="person-<?php echo $person['slug']; ?>">
          <div class="outer">

            <div class="frame">
              <figure>
                <img alt="<?php echo $person['forename'].' '.$person['surname']; ?>" src="<?php echo $thisPersonPortrait; ?>">
              </figure>
              <div class="tail">
                <div class="caption">
                  <h3><?php echo $person['forename']; ?> <?php echo $person['surname']; ?></h3>
                  <h4><?php echo $person['role']; ?></h4>
                </div>
                <a href="/people/#<?php echo $person['slug']; ?>" class="arrowLink <?php if ( $textStyle === 'light' ) : ?>white<?php else: ?>black<?php endif; ?> noText"><span><?php echo $person['forename'].' '.$person['surname']; ?></span></a>
              </div>
            </div>

            <div class="biov2">
              <div class="padding">
                <h3><?php echo $person['forename']; ?> <?php echo $person['surname']; ?></h3>
                <h4><?php echo $person['role']; ?></h4>
                <?php echo html_entity_decode($person['bio']); ?>
<?php if (!empty( $person['linkedin'] ) || !empty( $person['twitter'] ) || !empty( $person['instagram'] )) : ?>
                <div class="socialLinks">

                <ul>
                  <?php if (!empty( $person['linkedin'] )) : get_template_part( 'blocks/snippets/linkedin','',['linkedin' => $person['linkedin'] ]); endif; ?>
                  <?php if (!empty( $person['twitter'] )) :  get_template_part( 'blocks/snippets/twitter','',['twitter' => $person['twitter'] ]);  endif; ?>
                  <?php if (!empty( $person['instagram'] )) :  get_template_part( 'blocks/snippets/instagram','',['instagram' => $person['instagram'] ]);  endif; ?>
                  <?php if (!empty( $person['email'] )) : get_template_part( 'blocks/snippets/email','',['email' => $person['email'] ]); endif; ?>
                </ul>
                  
                </div>
                <?php endif; ?>
                <a href="" class="arrowLink black noText"><span>CLOSE</span></a>
              </div>
            </div>

          </div>
        </div>
      <?php
      endforeach;
?>        

      </div>
    </div>
<!-- not carousel END -->
<?php endif; ?> 

</section>
<?php
    } break;

    case 'post' : {

    } break;
  }

  /* PAGE STRUCTURE END */

  get_footer( null, [] );
}
?>
