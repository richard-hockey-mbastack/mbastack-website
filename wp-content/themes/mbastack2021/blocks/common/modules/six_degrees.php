<?php
/*
Modules flexuble content template for layout: tools
blocks/commom/modules/tools.php
*/

/*
module_css_class

title
title_colour
title_opacity

background-colour

intro_title
intro_copy

tools[
  icon (lottie URL)
  title
  initial_copy
  full_copy
]
*/

$moduleIdentifier = $args['module_identifier'];
$extra_css = get_sub_field('extra_css');
$animation = get_sub_field('animation');
// $animation = 'https://assets7.lottiefiles.com/private_files/lf30_lxkoo1yw.json';
$heading = get_sub_field('heading');
$copy = get_sub_field('copy');
// animation
// heading
// copy
?>
<style>
  #<?php echo $moduleIdentifier; ?>{  }
</style>
<section class="module six_degrees <?php if ( !empty(get_sub_field('$extra_css')) ) : echo get_sub_field('$extra_css');  endif; ?>" id="<?php echo $moduleIdentifier; ?>">
  <header class="topclip">
    <div class="container">
      <div>
        <h2><?= $heading ?></h2>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <figure>
          <lottie-player src="<?= $animation ?>" background="transparent" speed="1" style="width: 300px; height: 300px;" loop  autoplay></lottie-player>
        </figure>
      </div>
    <!-- </div>
    <div class="row">
    -->
      <div class="col-lg-6">
        <?= $copy ?>
      </div>
    </div>
  </div>

</section>
