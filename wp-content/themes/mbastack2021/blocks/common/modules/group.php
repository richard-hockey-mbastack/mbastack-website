<?php
/*
Modules flexuble content template for layout: group
blocks/commom/modules/group.php
*/

/*
module_css_class
background_colour
title
title_colour
title_opacity
map_image
msq_logo
msq_text
copy
*/

$moduleIdentifier = $args['module_identifier'];
// $mapURL = ( !empty(get_sub_field('map_image'))) ? wp_get_attachment_image_src(get_sub_field('map_image'), 'full')[0] : '';
// $msqURL = ( !empty(get_sub_field('msq_logo'))) ? wp_get_attachment_image_src(get_sub_field('msq_logo'), 'full')[0] : '';
?>
<style>
<?php if ( !empty(get_sub_field('background_colour')) ) : ?>
#<?php echo $moduleIdentifier; ?>{
  background-color: <?php echo get_sub_field('background_colour'); ?>;
}
<?php endif; ?>
</style>
<section class="module group" id="<?php echo $moduleIdentifier; ?>">
  <header class="topclip">
    <div class="container">
      <div>
        <h2><?php echo get_sub_field('title'); ?></h2>
      </div>
    </div>
  </header>

  <div class="msqinfo">
    <div class="container">
      <div class="row">
        <div class="copy">
          <h3><?php echo get_sub_field('msq_text'); ?></h3>
          <?php echo get_sub_field('copy'); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="maps" id="groupMap">
    <figure class="desktop mapgraph">
      <div class="lst-group lst-group-d" data-source="https://assets6.lottiefiles.com/packages/lf20_xkcrxeln/Map_Desktop.json"></div>
      <!-- <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_xkcrxeln/Map_Desktop.json" background="transparent" speed="1" style="width: 100%;" loop autoplay></lottie-player> -->
    </figure>

    <figure class="mobile mapgraph">
      <!--0 <div class="lst-group lst-group-m" data-source="https://assets5.lottiefiles.com/packages/lf20_fttz0vjx/MBAstack_Mobile.json"></div> -->
      <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_fttz0vjx/MBAstack_Mobile.json" background="transparent" speed="1" style="width: 100%;" loop autoplay></lottie-player>
    </figure>

  </div>

  
</section>
