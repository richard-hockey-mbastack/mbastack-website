<?php
/*
Template Name: wmb
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */
  get_template_part( 'blocks/common/page-banner','',['title' => get_field('page_title'), 'desktop' => get_field('page_image_desktop'), 'mobile' => get_field('page_image_mobile') ]);

  // the_content();

  /* PAGE STRUCTURE END */
?>
<script type="text/javascript">
var data = [
  {
    "name" : "Aaron Aardvark",
    "company" : "Exaco",
    "copy" : "Lorem upsum",
    "photo" : "",
    "logo" : "",
    "link" : ""
  }
]
</script>
<section class="wmbGrid">

<ul class="mosiac">
  <?php
  $n = 640; // 32 x 20
  while($n > 0) {
  ?>
  <li><div class="yellow"></div><figure><img alt="<?php echo $n; ?>" title="<?php echo $n; ?>" src="http://local.mbastack/wp-content/uploads/2021/07/people-stephen-maher.jpg"></figure></li>
  <?php
  $n--;
  }
  ?>
</ul>
  
</section>
<?php

  get_footer( null, [] );
}
?>
