<?php
/*
module: home/casestudies/
source/scss/pages/home.scss -> assets/css/styles.css

$arguments = [
  'block-title'                         (string)
  'block-title-colour'                  (string)
  'block-subtitle'                      (string)
  'block-subtitle-colour'               (string)
  'background-colour'                   (string)
  'background-image'                    (image ID)
  'grid-width'                          (2,3,4)
  'block-link-present'                  ('yes','no')
  'block-link' => [
    'block_link_url',                   (string)
    'block_link_style',                 ('white','black')
    'block_link_target',                ('self','blank')
    'block_link_caption'                (string)
  ]
  'clients' => [
    [
      'client' => [
        'title',                        (string)
        'logo'                          (image ID)
      ]
    ]
  ]
];
*/

$clientLogos = $args['clients'];
// echo "<pre>".print_r($clientLogos,true)."</pre>\n";

// use grid width to break collection of logo up into pages
// $gridWidth * 2 per page

$gridWidth = $args['grid-width'];

switch( $gridWidth ) {
  case 2 : {
    $gridClass = 'col-md-6';
  } break;
  case 3 : {
    $gridClass = 'col-md-4';
  } break;
  case 4 : {
    $gridClass = 'col-md-3';
  } break;
  case 6 : {
    $gridClass = 'col-md-2';
  } break;
}

$itemsPerPage = $gridWidth * 2;
$itemCount = count($clientLogos);
$pages = floor($itemCount / $itemsPerPage);
// $pages = $pages + ( $itemCount % $itemsPerPage !== 0 ) ? 1 : 0;
$x = ( $itemCount % $itemsPerPage );
if ( $x > 0 )
  $pages++;

$cPage = $pages;
$pageSet = [];
$pageSetBackwards = [];
do {
  $start = ($cPage - 1) * $itemsPerPage;
  $length = $itemsPerPage;

  // check to see if end of current pge exceeds end of items
  $length = ( $length > count($clientLogos) ) ? count($clientLogos) : $length;

  $pageSetBackwards[] = array_splice($clientLogos, $start, $length);

  $cPage--;
} while ( $cPage > 0);

// reverse pages array
$pageSet = array_reverse($pageSetBackwards);
?>

<style>
  section.latestClients{
    <?php if ( !empty($args['background-image']) ) : ?>
    background-image: url('<?php echo wp_get_attachment_image_src($args['background-image'], 'full')[0]; ?>');
    <?php endif; ?>
    <?php if ( !empty($args['background-colour']) ) : ?>
    background-color:<?php echo $args['background-colour']; ?>;
    <?php endif; ?>
  }
  <?php if ( !empty($args['block-title-colour']) ) : ?>
  section.latestClients header h2{
    color:<?php echo $args['block-title-colour']; ?>;
  }
  <?php endif; ?>
  <?php if ( !empty($args['block-subtitle-colour']) ) : ?>
  section.latestClients header h3{
    color:<?php echo $args['block-subtitle-colour']; ?>;
  }
  <?php endif; ?>
</style>

<section class="module latestClients" id="<?php echo $args['module_identifier']; ?>">
    <?php if ( !empty($args['block-title']) ) : ?>
  <header class="topclip">
    <div class="container">
      <div>
        <h2><?php echo $args['block-title']; ?></h2>
      </div>
    </div>
  </header>
    <?php endif; ?>
    <div class="container">
    <?php if ( !empty($args['block-subtitle']) ) : ?>
    <header>
      <h3><?php echo $args['block-subtitle']; ?></h3>
    </header>
    <?php endif; ?>

<!--  -->

<div id="homeClientsCarousel" class="carousel slide" data-gridwidth="<?php echo $gridWidth; ?>" data-pause="hover" <?php /* data-ride="carousel" data-interval="4000" */ ?>>
  <div class="carousel-inner">
<?php foreach( $pageSet AS $index1 => $page) : ?>
  <div class="carousel-item <?php if ( $index1 === 0 ) : ?>active<?php endif; ?>">
    <div class="container">
      <div class="row">
      <?php
      foreach( $page AS $index2 => $item) :
        $thisClientLogoImageID = $item['logo'];
        $thisClientLogoImage = ( !empty($thisClientLogoImageID) ) ? wp_get_attachment_image_src($thisClientLogoImageID, 'full')[0] : '';
      ?>
        <div class="col-6 item <?php echo $gridClass; ?>" data-client-id="<?php echo $item['id']; ?>">
          <div class="outer">
            <figure><img alt="<?php echo $item['title']; ?>" src="<?php echo $thisClientLogoImage; ?>"></figure>
          </div>
        </div>
<?php
  // split items into rows
  if ( $index2 % $gridWidth === ($gridWidth - 1) && $index2 !== ( count($page) - 1 ) ) :
?>
      </div>
      <div class="row">
<?php endif; ?>

      <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
  </div>

  <div class="controls">
    <button class="carousel-control-prev" type="button" data-target="#homeClientsCarousel" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <!--
    <div class="carousel-indicators">
<?php foreach( $pageSet AS $index1 => $page) : ?>
        <button type="button" data-target="#homeClientsCarousel" data-slide-to="<?php echo $index1; ?>" aria-label="Slide <?php echo ($index1 + 1); ?>" <?php if ($index1 === 0) : ?>class="active" aria-current="true"<?php endif; ?> ></button>
<?php endforeach; ?>

    </div>
    -->
    <button class="carousel-control-next" type="button" data-target="#homeClientsCarousel" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>  

</div>

<!--  -->
      <?php if ( $args['block-link-present'] === 'yes' ) : ?>
      <div class="container text-end g-0">
        <a href="<?php echo $args['block-link']['block_link_url']; ?>" class="arrowLink followLink <?php echo $args['block-link']['block_link_style']; ?>" <?php if ( $args['block-link']['block_link_target'] !== 'self' ) : echo "target=\"_blank\""; endif;?>><?php echo $args['block-link']['block_link_caption']; ?></a>
      </div>
      <?php endif; ?>

  </section>
