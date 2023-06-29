<?php
/*
Template Name: home

common header/navbar
full width image carousel with sticky background images * not a good combination with respect to iOS
two column text/image with background image + colour
...
common footer
*/

if ( have_posts() ) {
  the_post();

  get_header( null, [] ); 

  /* PAGE STRUCTURE START */

?>  

<main class="homePage">
  <header class="pageTitle homePageTitle"><h1>MBAstack</h1></header>

<?php
get_template_part( 'blocks/home/banner','',[]);

/*
two-column: about
full width image
case studies home version
latest clients
news & awards home version
people
*/
get_template_part( 'blocks/common/modules','',[]);

/* gutenberg content render */
// the_content();

?>
<section class="module homegroup group">
  <header class="topclip">
    <div class="container">
      <div>
        <h2>MSQ GROUP</h2>
      </div>
    </div>
  </header>

  <div class="maps">
    <figure class="desktop mapgraph"><div class="lst-group lst-group-d" data-source="https://assets6.lottiefiles.com/packages/lf20_xkcrxeln/Map_Desktop.json"></div></figure>
    <figure class="mobile mapgraph">
      <!--0 <div class="lst-group lst-group-m" data-source="https://assets5.lottiefiles.com/packages/lf20_fttz0vjx/MBAstack_Mobile.json"></div> -->
      <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_fttz0vjx/MBAstack_Mobile.json" background="transparent" speed="1" style="width: 100%;" loop autoplay></lottie-player>
    </figure>
  </div>

</section></main>

<?php

  /* PAGE STRUCTURE END */


  get_footer( null, [] );
}
?>
