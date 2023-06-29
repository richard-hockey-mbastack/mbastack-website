<?php
/*
Modules flexuble content template for layout: cxm
blocks/commom/modules/cxm.php
*/

/*
module_css_class
background-colour #0D0127
title
intro
nodes[
  phase ('creativty','data','technology')
  title
  subtitle
  panels[
    copy
  ]
]
graph (lottie file link 'https://assets10.lottiefiles.com/packages/lf20_ohzux8th/data.json')
*/

$moduleIdentifier = $args['module_identifier'];

$hasBackgroundColour = ( !empty(get_sub_field('background-colour')) );
$graph = get_sub_field('graph');
$graphNodes = get_sub_field('nodes');
?>

<?php if ( $hasBackgroundColour ) : ?> 
<style>
#<?php echo $moduleIdentifier; ?>{
  background-color:<?php echo get_sub_field('background-colour'); ?>;
</style>
<?php endif; ?>

<section class="cxmv3 <?php if ( !empty(get_sub_field('module_css_class')) ) : echo get_sub_field('module_css_class');  endif; ?>" id="<?php echo $moduleIdentifier; ?>">
  <div class="stickyBox">

    <div class="container">
      <div class="row">
        <div class="fullheight">

          <?php if ( !empty(get_sub_field('title')) ) : ?>
          <h2 class="cxmtitle"><?php echo get_sub_field('title'); ?></h2>
          <?php endif; ?>

          <?php if ( !empty(get_sub_field('intro')) ) : ?>
          <div class="introCopy"><?php echo get_sub_field('intro'); ?></div>
          <?php endif; ?>

          <figure data-flow="creativy,data,technology">
            <div class="lst-cxm" data-source="<?php echo $graph; ?>"></div>
            <!-- <lottie-player src="<?php echo $graph; ?>" background="transparent" speed="1" style="width: 100%;max-width:660px;margin:0 auto;" loop="" autoplay=""></lottie-player> -->
          </figure>

          <div class="phases">
            <?php foreach ( $graphNodes AS $index => $graphNode ) : ?>
            <div class="cbitem" id="cb-<?php echo substr($graphNode['phase'], 0,1); ?>">
              <div class="outer">
                <div class="inner">
                  <h3 ><?php echo $graphNode['title'].' '.$graphNode['subtitle']; ?></h4>

                  <?php if (count($graphNode['panels']) === 1) : ?>
                    <p><?php echo $graphNode['panels'][0]['copy']; ?></p>
                  <?php else : ?>
                  <div class="pages" id="pages-<?php echo substr($graphNode['phase'], 0,1); ?>">

                    <?php foreach ( $graphNode['panels'] AS $index => $panel ) :
                      $copy = $panel['copy'];
                    ?>
                    <div class="page<?php if ($index === 0) : ?> active<?php endif; ?>" id="page-<?php echo substr($graphNode['phase'], 0,1); ?>-<?php echo $index; ?>">
                      <div class="ip">
                        <p><?php echo $copy; ?></p>    
                      </div>
                    </div>
                  <?php endforeach; ?>
                    <ul>
                      <?php foreach ( $graphNode['panels'] AS $index => $panel ) : ?>
                      <li data-target="pages-<?php echo substr($graphNode['phase'], 0,1); ?>"<?php if ($index === 0) : ?> class="active"<?php endif; ?>><span><?php echo ($index + 1); ?></span></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <a class="scrolldown" data-container="section.cxmv3" data-target="nextmodule"><span>Go to next module</span></a>  
        </div>
        
      </div>
    </div>

  </div>
</section>

