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

$hasTitle = (!empty(get_sub_field('title')));
$hasTitleColour = (!empty(get_sub_field('title_colour')));
$hasTitleOpacity = (!empty(get_sub_field('title_opacity')));
$hasBackgroundColor = (!empty(get_sub_field('background-colour')));
$hasIntroTitle = (!empty(get_sub_field('intro_title')));
$hasIntroCopy = (!empty(get_sub_field('intro_copy')));

$tools = get_sub_field('tools');
?>
<style>
  #<?php echo $moduleIdentifier; ?>{  }
  #<?php echo $moduleIdentifier; ?> p.x{
    position:absolute;
    left:1rem;
    top:1rem;
    font-weight:400;
  }
  #<?php echo $moduleIdentifier; ?> p.x span{
    color:#f00;
  }
  #<?php echo $moduleIdentifier; ?> .toolsWrapper2{ width:<?php echo (100 * count($tools)); ?>%; }
</style>
<section class="module tools <?php if ( !empty(get_sub_field('module_css_class')) ) : echo get_sub_field('module_css_class');  endif; ?>" id="<?php echo $moduleIdentifier; ?>">

  <div class="stickyBox">
    <header class="topclip">
      <div class="container">
        <div>
          <h2>Tools</h2>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="row">

        <div class="col posrel">
          <!-- <button class="nextItem"><span>Next</span></button> -->
          <header class="sub">
            <?php if ( $hasIntroTitle) : ?>
            <h3><?php echo get_sub_field('intro_title'); ?></h3>
            <?php endif; ?>
            <?php if ( $hasIntroCopy ) : ?>
            <p><?php echo get_sub_field('intro_copy'); ?></p>
            <?php endif; ?>
          </header>

          <div class="toolsWrapper1">
            <div class="swiper"></div>
            <div class="toolsWrapper2">
            <?php foreach ( $tools AS $index => $tool ) : ?>
              <!-- template START -->
              <div class="item" id="<?php echo $tool['id']; ?>" data-icon="<?php echo $tool['icon']; ?>">
                
                <figure class="animater-icon" id="icon-<?php echo $tool['id']; ?>">
                  <!-- <div class="lst-tools lst-tools<?php echo ($index + 1);?>" data-source="<?php echo $tool['icon']; ?>"></div> -->
                  <lottie-player src="<?php echo $tool['icon']; ?>" background="transparent" speed="1" style="width: 256px; height: 256px;" loop autoplay></lottie-player>
                </figure>
                
                <div>
                  <h4><span>#<?php echo sprintf('%02d', ($index + 1)); ?></span><br><?php echo $tool['title']; ?></h4>
                  <?php echo $tool['full_copy']; ?>
                </div>

              </div>                
              <!-- template END -->
            <?php endforeach; ?>
            </div>

            <p class="info-m">Scroll down to move on</p>
            <?php if ( count( $tools )  > 0 ) : ?>
            <div class="toolsProgress">
              <ul>
                
                <?php foreach ( $tools AS $index => $tool ) : ?>
                <li><button data-p="<?= $index ?>"><span><?= ( $index + 1 ) ?></span></button></li>
                <?php endforeach; ?>
              </ul>
              <button class="nextItem"><span>Next</span></button>
            </div>
            <?php endif; ?>
            <p class="info-d">Scroll down to move on</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
