$queryArguments = [
  'post_type' => 'post',
  'posts_per_page' => -1,
  'ignore_sticky_posts' => true // add this to the query to fix the odd query results order
];
$the_query = new WP_Query( $queryArguments );

$tp = [];
if ( $the_query->have_posts() ) {
  while ( $the_query->have_posts() ) {
    $the_query->the_post();

    $thisPostCategories = ( !empty($post->ID) ) ? wp_get_post_categories( $post->ID, [] ) : false; // returns category ID
    $cat = ($thisPostCategories) ? get_category( $thisPostCategories[0] ) : '';

    // tp for my bunghole
    $tp[] = [
      'id' => $post->ID,
      'title' => $post->post_title,
      'date' => get_the_date("d.m.y", $post->ID),
      'd2' => $post->post_date
    ];
  }
}
wp_reset_postdata();

echo "<pre>".print_r( $tp, true )."</pre>";
