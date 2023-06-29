<?php
/*
MBAstack-2021 theme
functions 
*/
ini_set('display_errors','On');
ini_set('error_reporting', E_ALL );
ini_set( 'mysql.trace_mode', 0 );

/* --------------------------------------------- */
// attach date modified timestamp to CSS,JS file references

// use rewrite rule to route the modified URL to the actual file
// .htaccess
// RewriteEngine on
// RewriteRule ^(.*)\.[\d]{10}\.(css|js)$ $1.$2 [L] 

// update CSS/JS links in header.php/footer.php
/*
change:
    <link rel="stylesheet" href="/css/base.css" type="text/css" />
to:
    <link rel="stylesheet" href="< ?php echo auto_version('/css/base.css'); ? >" type="text/css" />
*/
// update mbastack_enqueue_scripts (below line 128 of this file)

function auto_version($file)
{
    $filePath = get_template_directory() . $file;
    if(strpos($file, '/') !== 0 || !file_exists($filePath) ) {
        return $file;
    } else {
        $mtime = filemtime($filePath);
        return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
    }
}

function file_timestamp($file)
{
    $filePath = get_template_directory() . $file;
    $mtime = filemtime($filePath);
    if ($mtime) {
        return $mtime; 
    } else {
        return time();
    }
    
}

/* --------------------------------------------- */

// enable SVG upload via media library

// https://wordpress.stackexchange.com/questions/313951/how-to-upload-svg-in-wordpress-4-9-8
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

    if (!$data['type']) {
        $wp_filetype = wp_check_filetype($filename, $mimes);
        $ext = $wp_filetype['ext'];
        $type = $wp_filetype['type'];
        $proper_filename = $filename;
        if ($type && 0 === strpos($type, 'image/') && $ext !== 'svg') {
            $ext = $type = false;
        }
        $data['ext'] = $ext;
        $data['type'] = $type;
        $data['proper_filename'] = $proper_filename;
    }
    return $data;
}, 10, 4);

add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

// this adds a style block to the <head> tag of admin pages 
// used to to style the mdia library image view for SVG images
// this function works but the media library is not handling SVG images correctly, it shows a placeholder PNG image instead when you go to the image detail view
// most likely wordpress usually processes uploaded images into three scaled copies for display in the mdeia library, but this process fails with SVG files
add_action('admin_head', function () {
    echo '<style type="text/css">
         .media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
      width: 100% !important;
      height: auto !important;
    }</style>';
});



/*
// https://css-tricks.com/snippets/wordpress/allow-svg-through-wordpress-media-uploader/
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg_thumb_display() {
  echo '
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
      width: 100% !important; 
      height: auto !important; 
    }
  ';
}
add_action('admin_head', 'fix_svg_thumb_display');
*/

/*
related links
https://wpengine.co.uk/resources/enable-svg-wordpress/
https://devmaverick.com/how-to-add-svg-in-wordpress-media-library/
*/

/*
Plugins
https://wordpress.org/plugins/safe-svg/
https://wordpress.org/plugins/svg-support/
*/

/* --------------------------------------------- */

// add HTML title tag to page header via common header header.php
function theme_slug_setup() {
   add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );

// add non breaking space to last two words in block of text
function word_wrapper($text,$minWords = 3) {
   $return = trim($text);
   $arr = explode(' ',$text);
   if(count($arr) >= $minWords) {
           $arr[count($arr) - 2].= '&nbsp;'.$arr[count($arr) - 1];
           array_pop($arr);
           $return = implode(' ',$arr);
   }
   return $return;
}


/* --------------------------------------------- */

/*
https://developer.wordpress.org/reference/functions/wp_enqueue_script/
wp_enqueue_script(
string $handle,
string $src = '',
string[] $deps = array(),
string|bool|null $ver = false,
bool $in_footer = false
)
*/
// add scripts to pages
add_action( 'wp_enqueue_scripts', 'mbastack_enqueue_scripts' );
function mbastack_enqueue_scripts()
{
    // https://developer.wordpress.org/reference/functions/wp_enqueue_script/#usage
    // wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
    
    /*
    wp_register_script(
        'classes', // Handle
        get_stylesheet_directory_uri() . '/assets/js/classes.min.js' // File url
    );
    wp_register_script(
        'default', // Handle
        get_stylesheet_directory_uri() . '/assets/js/default.min.js' // File url
    );
    */

    wp_enqueue_script( 'classes', get_stylesheet_directory_uri() . '/assets/js/classes.min.js' ,[], file_timestamp( '/assets/js/classes.min.js'), false );
    wp_enqueue_script( 'default', get_stylesheet_directory_uri() . '/assets/js/default.min.js' ,[], file_timestamp('/assets/js/default.min.js'), false );

    wp_localize_script( 
        'default', 
        'localize_vars', 
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ), // add AJAX endpoints
            'url' => get_stylesheet_directory_uri(),
            // 'path' => get_stylesheet_directory()
        ) 
    );

    // find out how to attach script based on template/page
    wp_register_script(
        'home', // Handle
        get_stylesheet_directory_uri() . '/assets/js/pages/home.min.js' // File url
    );
    wp_enqueue_script( 'home' );

}


/* --------------------------------------------- */


// set up header and footer menus in admin
function mbastack_menus() {

   $locations = array(
      'primary'  => __( 'Main navigation', 'MBAstack-2021' ),
      'footer' => __( 'Footer links', 'MBAstack-2021' ),
   );

   register_nav_menus( $locations );
}

add_action( 'init', 'mbastack_menus' );


/* --------------------------------------------- */


// register custom post
function create_posttype() {

    /*
    'slides' Home page carousel slides
    'casetsudy' Case studies
    'person' People
    */ 

    register_post_type( 'slides',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Home Banner Slides' ), // label that appears in dasboard navigation
                'singular_name' => __( 'Home Banner Slide' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'slide'), // site/slide/NNN
            'show_in_rest' => true,
 
        )
    );

    register_post_type( 'casestudy',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Case studies' ), // label that appears in dasboard navigation
                'singular_name' => __( 'Case study' )
            ),
            'public' => true,
            'has_archive' => true,
            // 'rewrite' => array('slug' => 'casestudy'),
            'show_in_rest' => true,
 
        )
    );

    register_post_type( 'clients',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Clients' ), // label that appears in dasboard navigation
                'singular_name' => __( 'Client' )
            ),
            'public' => true,
            'has_archive' => true,
            // 'rewrite' => array('slug' => 'client'),
            'show_in_rest' => true,
 
        )
    );

    register_post_type( 'person',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'People' ), // label that appears in dasboard navigation
                'singular_name' => __( 'Person' )
            ),
            'public' => true,
            'has_archive' => true,
            // 'rewrite' => array('slug' => 'person'),
            'show_in_rest' => true,
 
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );


/* --------------------------------------------- */


// AJAX endpoints for work and news/blog posts

// casestudies

add_action('wp_ajax_nopriv_morework', 'morework_function'); // public facing
add_action('wp_ajax_morework', 'morework_function'); // admin or logged in users

function morework_function(){
    // get more case studies
    // parmeters:
    // ids of case studies already display (omit from query)
    // 'visible'
    // number of case studies to return
    // 'pagesize'
    // preferred order

    $visibleCaseStudies = explode(',',$_GET['visible']);
    $preferredOrder = explode(',',$_GET['preferred']);
    $pageSize = $_GET['pagesize'];

    $items = [];
    $newIDS = [];

    // get all posts
    $querySettings = [
      'post_type' => ['casestudy'],
      'post_status' => ['publish']
    ];
    $Allblogposts = new WP_Query($querySettings);
    $totalCaseStudies = $Allblogposts->found_posts;

    // query post type 'casestudy' omitting items which are already visible
    $querySettings = [
      'post_type' => ['casestudy'],
      'post_status' => ['publish'],
      'posts_per_page' => -1, // get all posts
      // 'posts_per_page' => $pageSize,
      // 'paged' => $paged,
      'post__not_in' => $visibleCaseStudies // skip over case studeis whiuch are already visible
    ];
    $blogposts = new WP_Query($querySettings);
    $maxPages = $blogposts->max_num_pages;

    if( $blogposts->have_posts() ) {
        while ( $blogposts->have_posts() ) :
            $blogposts->the_post();
            $post_id = get_the_ID();

            // get dekstop / mobile image URLs
            $desktop = get_field( 'desktop_image', $post_id );
            $mobile = get_field( 'mobile_image', $post_id );

            $noMobile = (empty($mobile));

            $desktopURL = ( !empty($desktop) ) ? wp_get_attachment_image_src($desktop, 'full')[0] : '';
            $mobileURL = ( !empty($mobile) ) ? wp_get_attachment_image_src($mobile, 'full')[0] : '';

            $hasVideo = get_field( 'has_banner_video', $post_id );
            $format = get_field( 'banner_video_format', $post_id );
            $source = get_field( 'banner_video_source', $post_id );
            $width = get_field( 'video_width', $post_id );
            $height = get_field( 'video_height', $post_id );

            // collect new item IDs
            //$newIDS[] =  strval($post_id);

            // build repsonse data
            $items[] = [
                'id' => (int) $post_id,
                'title' => get_the_title(),
                'url' => get_post_field( 'post_name'),
                'date' => get_the_date(),
                'client' => get_field( 'client', $post_id ),
                'campaign' => get_field( 'campaign', $post_id ),
                'desktop' => $desktopURL,
                'mobile' => ( !$noMobile ) ? $mobileURL : 'NOMOBILE',
                'hasvideo' => $hasVideo,
                'format' => $format,
                'source' => $source,
                'width' => $width,
                'height' => $height,
                'templatedir' => get_template_directory_uri()
            ];
        endwhile;
    }
    wp_reset_postdata();

    // sort items into preferred order
    // $items
    // $newIDS
    $sortedItems = [];
    while( count($preferredOrder) > 0 && count($sortedItems) < $pageSize ) {
      $first = array_shift($preferredOrder);

      $in = 0;
      while( $in < count($items) ){
        $ti = $items[$in];
        // echo "<p>in: $in ti: $ti</p>";
        if ( $ti['id'] == $first) {
          $sortedItems[] = $ti;
          array_splice($items, $in, 1);
        }
        $in++;
      }
    }

    if ( count($sortedItems) < $pageSize ) {
        $shortfall = $pageSize - count($sortedItems);
        $fill = array_splice($items, 0, $shortfall);
        $sortedItems = array_merge($sortedItems, $fill);
    }

    // collect new item IDs (in prefferred order)
    $sortedIDs = [];
    foreach ( $sortedItems AS $item ) {
        $sortedIDs[] = $item['id'];
    }

    // append new ids to end if visible ids and return to front end
    $newIDS = array_merge($visibleCaseStudies, $sortedIDs);

    $response = [
        'action' => 'More Work',
        'total' => $totalCaseStudies,
        'show_loader' => ($totalCaseStudies > count($newIDS) ),
        'items_per_page' => $pageSize,
        'preferred_order' => $preferredOrder,
        'ids' => $newIDS,
        'new_item_count' => count($sortedItems),
        'items' => $sortedItems
    ];

    echo json_encode( $response );

    exit();
}

// news/blog posts

function getAllPostsForCategory($category, $idList = null) {
    // check to see if there are more news stories available
    $count = 0;
    $queryArguments = [
      'post_type' => 'post'
    ];

    if ( !is_null($idList) ) {
        $queryArguments['post__not_in'] = $idList;
    }

    if ( $category !== 'all' ) {
        $queryArguments['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( $category ),
            )
        );
    }

    $the_query = new WP_Query( $queryArguments );
    $count = $the_query->post_count; 
    wp_reset_postdata();

    return $count;
}

add_action('wp_ajax_nopriv_morenews', 'morenews_function');
add_action('wp_ajax_morenews', 'morenews_function');

function morenews_function(){
    // get more news / blog posts
    // parmeters:
    // category filter 'all','insight','award',...
    // ids of items already display (omit from query)
    // number of items to return

    $currentFilter = $_GET['filter'];
    $visibleItems = explode(',',$_GET['visible']);
    $pageSize = (int) $_GET['pagesize'];

    // query posts:
    // not already shown
    // match category filter if filter is not 'all' 

    $queryArguments = [
        'post_type' => 'post',
        'posts_per_page' => $pageSize,
        'post__not_in' => $visibleItems,
        'post_status'=> 'publish',
        'ignore_sticky_posts' => true  //**
    ];

    if ( $currentFilter !== 'all' ) {
        /*
        $queryArguments['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( $currentFilter ),
            )
        );
        */
        $queryArguments['category_name'] = $currentFilter;
    }
    $the_query = new WP_Query( $queryArguments );
    $bx = $the_query->post_count;

    // process found posts 
    $newIDs = [];
    $items = [];
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();

            $post_id = get_the_ID();

            $thisPostImageURL = ( !empty(get_field( 'post_image', $post_id )) ) ? wp_get_attachment_image_src(get_field( 'post_image', $post_id ), 'full')[0] : '';
            $thisExternalSiteLogo = ( !empty(get_field( 'external_site_logo', $post_id )) ) ? wp_get_attachment_image_src(get_field( 'external_site_logo', $post_id ), 'full')[0] : '';
            $thisPostCategories = wp_get_post_categories( $post_id, [] ); // returns category ID
            $cat = get_category( $thisPostCategories[0] ); // get FIRST category

            $thisPost = [
                'id' => $post_id,
                'title' => get_the_title(),
                'image' => $thisPostImageURL,
                'external_site_logo' => $thisExternalSiteLogo,
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

    $updatedIDList = array_merge($visibleItems, $newIDs);    

    // check to see if there are more news stories available
    
    $remainingPosts = getAllPostsForCategory($currentFilter, $updatedIDList);

    // show 'load more' if there are more posts available
    $showLoadMore = ( $remainingPosts > 0 );
    
    $response = [
        'action' => 'Load more news',
        'current_filter' => $currentFilter,
        'items-per-page' => $pageSize,
        'newposts' => $bx,
        'extraposts' => $remainingPosts,
        'ids' => implode(',',$updatedIDList),
        'newids' => $newIDs,
        'items' => $items,
        'loadmore' => $showLoadMore
    ];

    echo json_encode( $response );

    exit();
}


add_action('wp_ajax_nopriv_filternews', 'filternews_function');
add_action('wp_ajax_filternews', 'filternews_function');

function filternews_function(){
    // * NOTE switching filters resets back to first page

    // parameters:
    // current filter
    $filter = $_GET['filter'];
    // items per initial ppge
    $initial = (int) $_GET['initial'];

    $allPostCount = getAllPostsForCategory($filter);

    $queryArguments = [
        'post_type' => 'post',
        'posts_per_page' => $initial,
        'post_status'=> 'publish',
        'ignore_sticky_posts' => true  //**
    ];
    if ( $filter !== 'all' ) {
        /*
        $queryArguments['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( $currentFilter ),
            )
        );
        */
        $queryArguments['category_name'] = $filter;
    }
    $the_query = new WP_Query( $queryArguments );
    $initalPostCount = $the_query->post_count;

    // massages data
    $leadItem = [];
    $items = [];
    $newids = [];
    if ( $the_query->have_posts() ) {
        $index = 0;
        while ( $the_query->have_posts() ) {
            $the_query->the_post();

            $post_id = get_the_ID();
            $newids[] = $post_id;

            $thisPostImageURL = ( !empty(get_field( 'post_image', $post_id )) ) ? wp_get_attachment_image_src(get_field( 'post_image', $post_id ), 'full')[0] : '';
            $thisExternalSiteLogo = ( !empty(get_field( 'external_site_logo', $post_id )) ) ? wp_get_attachment_image_src(get_field( 'external_site_logo', $post_id ), 'full')[0] : '';
            $thisPostCategories = wp_get_post_categories( $post_id, [] ); // returns category ID
            $cat = get_category( $thisPostCategories[0] ); // get FIRST category

            $thisItem = [
                'id' => $post_id,
                'title' => get_the_title(),
                'image' => $thisPostImageURL,
                'external_site_logo' => $thisExternalSiteLogo,
                'synopsis' => get_field( 'post_synopsis', $post_id ),
                'has_external_link' => get_field( 'link_is_internal', $post_id ),
                'link_url' => get_field( 'link_url', $post_id ),
                'link_caption' => get_field( 'link_caption', $post_id ),
                'date' => get_the_date("d.m.y", $post_id),
                'category' => $cat->slug,
                'permalink'=> get_permalink($post_id)
            ];

            if ( $index === 0) {
                $leadItem = $thisItem;
            } else {
                $items[] = $thisItem;                  
            }
            
            $index++;
        }
    }
    wp_reset_postdata();

    // compare initial posts for current category against all posts
    // if all < initial number of posts hide load more
    $showLoadMore = ($allPostCount > $initial);

    $response = [
        'action' => 'Filter news',
        'filter' => $filter,
        'initial' => $initial,
        'ids' => implode(',',$newids),
        'items' => $items,
        'lead' => $leadItem,
        'posts' => $initalPostCount,
        'allposts' => $allPostCount,
        'loadmore' => $showLoadMore
    ];

    echo json_encode( $response );

    exit();
}


// case study page preload
add_action('wp_ajax_nopriv_casestudypreload', 'casestudypreload');
add_action('wp_ajax_casestudypreload', 'casestudypreload');

function casestudypreload(){
    // parameters
    // 'casestudy' custom post ID

    // query / getpost  using supplied ID
    // get ACF fields for post

    // ? get more case studies entries for case study post

    $response = [
        'action' => 'Case Study preload',
        'id' => 999,
        'casestudy' => [] // populate with relevant case study banner/module data
    ];

    echo json_encode( $response );

    exit();
}

// people images preload
// run on page load on home page - fetch all people images
add_action('wp_ajax_nopriv_peoplepreload', 'peoplepreload');
add_action('wp_ajax_peoplepreload', 'peoplepreload');

function peoplepreload(){
    // parameters
    // context -> page $post->ID
    $context = (int) $_GET['context'];
    $items = [];

    /*
    // get all defined people in cutoms post type 'person'
    $queryArguments = [
      'post_type' => 'person',
      'posts_per_page' => -1,
      'post_status' => 'publish'
    ];
    */

    // wp_query with post(page) ID in $context
    $queryArguments = [
      'page_id' => $context
    ];
    $the_query = new WP_Query( $queryArguments );
    $personCount = $the_query->post_count;

    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();

            $post_id = get_the_ID();

            /* fun and games with page fields and flexible content repeater layout fields */
            $px = get_field('modules', $post_id); //get page field flexible content repeater 'modules'

            // index (position in page) depends on page 0 for people, 4 for home
            foreach($px AS $index => $field) {
                if ( $field['acf_fc_layout'] === 'people') {
                    $pn = $px[$index]['people_to_show']; // drill down to sub field 'people_to_show'
                    break;
                }
            }

            if (!empty($pn)) {
                foreach( $pn AS $peopleX ) {
                    $person_id = $peopleX['person'];
                    $thisPerson = get_post($person_id);
                    $slug = get_post_field( 'post_name', $person_id);
                    $portraitID = get_field('portrait_photo', $person_id);
                    $fullID = get_field('full_photo', $person_id);
                    $thisPersonPortrait = ( !empty($portraitID) ) ? wp_get_attachment_image_src($portraitID, 'full')[0] : '';
                    $thisPersonFull = ( !empty($fullID) ) ? wp_get_attachment_image_src($fullID, 'full')[0] : '';

                    $thisPost = [
                        'id' => 'person-'.$person_id,
                        'slug' => $slug,
                        'portrait' => $thisPersonPortrait,
                        'full' => $thisPersonFull,
                        'forename' => get_field('forename', $person_id),
                        'surname' => get_field('surname', $person_id),
                        'role' => get_field('role', $person_id),
                        'bio' => get_field('bio', $person_id), // html_entity_decode() no use for JSON/AJAX
                        'linkedin' => get_field('linkedin', $person_id),
                        'twitter' => get_field('twitter', $person_id),
                        'instagram' => get_field('instagram', $person_id),
                        'faceboon' => get_field('facebook', $person_id),
                        'email' => get_field('email', $person_id),
                        'phone' => get_field('phone', $person_id)
                    ];
                    $items[] = $thisPost;
                }
            }
        }
    }
    wp_reset_postdata();

    $response = [
        'action' => 'people preload',
        'context' => $context,
        'count' => count($items),
        'people' => $items
    ];
    echo json_encode( $response );

    exit();
}

/*
// people bio/details
add_action('wp_ajax_nopriv_persondetails', 'persondetails');
add_action('wp_ajax_persondetails', 'persondetails');

function persondetails(){
    // parameters
    // 'person' custom post ID

    $response = [
        'action' => 'Person details',
        'id' => 999,
        'name' => 'NAME',
        'role' => 'ROLE',
        'bio' => 'LOREM IPSIM',
        'socialmedia' => [
            'twiiter' => '',
            'linkedin' => '',
            'facebook' => '',
            'instagram' => ''
        ]
    ];

    echo json_encode( $response );

    exit();
}
*/

/* --------------------------------------------- */

/*
// assign parent page to post

//Add the meta box callback function
function admin_init(){
    add_meta_box("case_study_parent_id", "Case Study Parent ID", "set_case_study_parent_id", "casestudy", "normal", "low");
}
add_action("admin_init", "admin_init");

//Meta box for setting the parent ID
function set_case_study_parent_id() {
    global $post;
    $custom = get_post_custom($post->ID);
    $parent_id = $custom['parent_id'][0];
    ?>
    <p>Please specify the ID of the page or post to be a parent to this Case Study.</p>
    <p>Leave blank for no heirarchy.  Case studies will appear from the server root with no assocaited parent page or post.</p>
    <input type="text" id="parent_id" name="parent_id" value="<?php echo $post->post_parent; ?>" />
    <?php
    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="parent_id_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

// Save the meta data
function save_case_study_parent_id($post_id) {
    global $post;

    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['parent_id_noncename'],__FILE__))
        return $post_id;

    if(isset($_POST['parent_id']) && ($_POST['post_type'] == "casestudy")) {
        $data = $_POST['parent_id'];
        update_post_meta($post_id, 'parent_id', $data);
    }
}
add_action("save_post", "save_case_study_parent_id");
*/

/* --------------------------------------------- */

// customize logo on admin login page
// https://codex.wordpress.org/Customizing_the_Login_Form
// place at END of functions.php

// change login page logo image

function my_login_logo() {
?>
<style>
#login h1 a, .login h1 a {
    background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/svg/LOGO/MBAstack logo_MBAstack-logo-master-colour.svg');
    height: 85px;
    width:auto;
    background-size: auto 125px;
    background-repeat: no-repeat;
    padding-bottom: 30px;
}
</style>
<?php
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// change login page logo to point to home page of site instead of wqordpress.org

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

// change title attribute of login page logo

function my_login_logo_url_title() {
    return 'MBAstack';
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );


// --------------------------------------------------------------
// https://imranhsayed.medium.com/saving-the-acf-json-to-your-plugin-or-theme-file-f3b72b99257b
// --------------------------------------------------------------

// save ACF pro custom fields to /themes/mbastack-2021/acf-json/ 
define( 'MY_PLUGIN_DIR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {
    
    // Update path
    $path = MY_PLUGIN_DIR_PATH . '/acf-json';

    // Return path
    return $path;
}

// load ACF-json files
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
/*
 * Register the path to load the ACF json files so that they are version controlled.
 * @param $paths The default relative path to the folder where ACF saves the files.
 * @return string The new relative path to the folder where we are saving the files.
 */
function my_acf_json_load_point( $paths ) {
   // Remove original path
   unset( $paths[0] );
// Append our new path
   $paths[] = MY_PLUGIN_DIR_PATH . '/acf-json';
   return $paths;
}


/* form test code START */

// set up php session
function register_my_session()
{
  if( !session_id() )
  {
    session_start();
  }
}
add_action('init', 'register_my_session');

// https://medium.com/nerd-for-tech/how-to-create-a-simple-nonce-in-php-a5afe046beee
/* Nonce class */
// session_start();

define('NONCE_SECRET', 'CEIUHET745T$^&%&%^gFGBF$^');

class Nonce {
/**
     * Generate a Nonce. 
     * 
     * The generated string contains four tokens, separated by a colon.
     * The first part is the salt. 
     * The second part is the form id.
     * The third part is the time until the nonce is invalid.
     * The fourth part is a hash of the three tokens above.
     * 
     * @param $length: Required (Integer). The length of characters to generate
     * for the salt.
     * 
     * @param $form_id: Required (String). form identifier.
     * 
     * @param $expiry_time: Required (Integer). The time in minutes until the nonce 
     * becomes invalid. 
     * 
     * @return string the generated Nonce.
     *
     */

    public function generateSalt($length = 10){
        //set up random characters
        $chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        //get the length of the random characters
        $char_len = strlen($chars)-1;
        //store output
        $output = '';
        //iterate over $chars
        while (strlen($output) < $length) {
            /* get random characters and append to output till the length of the output 
             is greater than the length provided */
            $output .= $chars[ rand(0, $char_len) ];
        }
        //return the result
        return $output;
    }

    private function storeNonce($form_id, $nonce){
        //Argument must be a string
        if (is_string($form_id) == false) {
            throw new InvalidArgumentException("A valid Form ID is required");
        }
        //group Generated Nonces and store with md5 Hash
        $_SESSION['nonce'] = [];
        $_SESSION['nonce'][$form_id] = md5($nonce);
        return true;
    }

    public function generateNonce($length = 10, $form_id, $expiry_time){
        //our secret
        $secret = NONCE_SECRET;

        //secret must be valid. You can add your regExp here
        if (is_string($secret) == false || strlen($secret) < 10) {
            throw new InvalidArgumentException("A valid Nonce Secret is required");
        }

        //generate our salt
        $salt = self::generateSalt($length);
        //convert the time to seconds
        $time = time() + (60 * intval($expiry_time));
        //concatenate tokens to hash
        $toHash = $secret.$salt.$time;
        //send this to the user with the hashed tokens
        $nonce = $salt .':'.$form_id.':'.$time.':'.hash('sha256', $toHash);
        //store Nonce
        self::storeNonce($form_id, $nonce);
        //return nonce
        return $nonce;
    }

    /**
     * Verify a Nonce. 
     * 
     * This method validates a nonce
     *
     * @param $nonce: Required (String). This is passed into the verifyNonce
     * method to validate the nonce.
     *  
     * @return boolean: Check whether or not a nonce is valid.
     * 
     */

    public function verifyNonce($nonce){
        //our secret
        $secret = NONCE_SECRET;
        //split the nonce using our delimeter : and check if the count equals 4
        $split = explode(':', $nonce);
        if(count($split) !== 4){
            return false;
        }

        //reassign variables
        $salt = $split[0];
        $form_id = $split[1];
        $time = intval($split[2]);
        $oldHash = $split[3];
        //check if the time has expired
        if(time() > $time){
            return false;
        }

        /* Nonce is proving to be valid, continue ... */

        //check if nonce is present in the session
        if(isset($_SESSION['nonce'][$form_id])){
            //check if hashed value matches
            if($_SESSION['nonce'][$form_id] !== md5($nonce)){
                return false;
            }
        }else{
             return false;
        }

        //check if the nonce is valid by rehashing and matching it with the $oldHash
        $toHash = $secret.$salt.$time;
        $reHashed = hash('sha256', $toHash);
        //match with the token
        if($reHashed !== $oldHash){
            return false;
        }
        /* Wonderful, Nonce has proven to be valid*/
        return true;
    }
}
/* form test code END */

?>

