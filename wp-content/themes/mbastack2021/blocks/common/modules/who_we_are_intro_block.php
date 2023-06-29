<?php
/*
Modules flexuble content template for layout: who_we_are_intro_block
/blocks/commom/modules/who_we_are_intro_block.php
/source/scss/common/whoWeAreIntro.scss
*/

/*
module_css_class
mbastack_logo (image ID)
copy
*/

$moduleIdentifier = $args['module_identifier'];


// echo "<pre>".print_r(get_row(),true)."</pre>";"

?>
<style>
#<?php echo $moduleIdentifier; ?>{
</style>
<section class="module whoWeAreIntro" id="<?php echo $moduleIdentifier ?>">
  <div class="container">
    <div class="row">
      <div class="col">

        <div class="background">
          <figure>
            <img alt="MBAstack" src="<?php echo get_template_directory_uri(); ?>/assets/svg/LOGO/MBAstack%20logo_MBAstack-logo-master-colour.svg">
          </figure>
          <div id="mba-stack-merge" class="videoWrapper inlineWrapper" data-format="inline" data-mode="scrollstart"  data-height="1080" data-width="1920" data-source="<?php echo get_template_directory_uri(); ?>/assets/video/mba+stack.mp4">
            <div class="pr"></div>
          </div>
        </div>
          
      </div>
    </div>
    <div class="row">
      <div class="text">
        <?php echo get_sub_field('copy'); ?>
      </div>
    </div>
  </div>

</section>
