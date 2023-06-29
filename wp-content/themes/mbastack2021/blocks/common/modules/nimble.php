<?php
/*
Modules flexuble content template for layout: Who We are 'nimble' module
layout name: nimble
blocks/commom/modules/nimble.php
*/

/*
module_css_class

title
title_colour
title_opacity

intro_title
intro_copy
Full_copy
points[
  copy
]
*/

$moduleIdentifier = $args['module_identifier'];
$hasTtitle = !empty(get_sub_field('title'));

?>

<style>
#<?php echo $moduleIdentifier; ?>{}
<?php if ( $hasTtitle ) : ?>
#<?php echo $moduleIdentifier; ?> .topclip h2{
  <?php if ( !empty(get_sub_field('title_colour')) ) : ?>
    color: <?php echo get_sub_field('title_colour'); ?>;
  <?php endif; ?>
  <?php if ( !empty(get_sub_field('title_opacity')) ) : ?>
    opacity: <?php echo get_sub_field('title_opacity'); ?>;
  <?php endif; ?>
}
<?php endif; ?>
</style>

<section class="module nimble <?php if ( !empty(get_sub_field('module_css_class')) ) : echo get_sub_field('module_css_class');  endif; ?>" id="<?php echo $moduleIdentifier ?>">

  <?php if ( $hasTtitle ) : ?>
    <header class="topclip">
      <div class="container">
        <div>
          <h2><?php echo get_sub_field('title'); ?></h2>
        </div>
      </div>
    </header>
  <?php endif; ?>

  <div class="container">
    <div class="row">
      <div class="col copy">
        <?php if ( !empty(get_sub_field('intro_title')) ) : ?>
        <h3><?php echo get_sub_field('intro_title'); ?></h3>
        <?php endif; ?>

        <?php if ( !empty(get_sub_field('intro_copy')) ) : ?>
        <p class="ic"><?php echo get_sub_field('intro_copy'); ?></p>
        <?php endif; ?>

        <div class="clipper">
          <button class="expander">read more <span></span></button>

          <div class="outer">
            <div class="inner">
              <?php if ( !empty(get_sub_field('full_copy')) ) : ?>
              <?php echo get_sub_field('full_copy'); ?>
              <?php endif; ?>

              <?php if ( !empty( get_sub_field('points') ) ) :
                foreach ( get_sub_field('points') AS $index => $point ) : ?>
              <div class="point" data-point="point-<?php echo $index; ?>">
                <h4><?php echo $point['copy']; ?></h4>
              </div>
            <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <button class="closer hide">close <span></span></button>
        </div>
        
      </div>

    </div>
  </div>

</section>
