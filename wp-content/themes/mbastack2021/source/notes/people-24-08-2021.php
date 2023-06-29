<?php
/*
Modules flexuble content template for layout: people
blocks/common/modules/people
*/

/*
title
title_colour
title_opacity
text_colour ('dark','light')
intro_header
intro_copy
is_carousel ('no','yes')
page_items (2,3,4,6,8)
people[
]
has_following_link ('no','yes')
link_text
link_target ('self','blank')
;ink_url
*/

$moduleIdentifier = $args['module_identifier'];

$people = get_sub_field('people');
$hasTitle = ( !empty(get_sub_field('title')) );
$hasFollowingLink = ( get_sub_field('has_following_link') === 'yes' ) ;
$introHeader = get_sub_field('intro_header');
$introCopy = get_sub_field('intro_copy');
$hasIntroHeader = !empty($introHeader);
$hasIntroCopy = !empty($introCopy);

$isCarousel = (get_sub_field('is_carousel') === 'yes');
$gridWidth = get_sub_field('page_items');

switch( $gridWidth ) {
  case 2 : {
    $gridClass = 'col-md-6';
    $itemsPerPage = $gridWidth;
  } break;
  case 3 : {
    $gridClass = 'col-md-4';
    $itemsPerPage = $gridWidth;
  } break;
  case 4 : {
    $gridClass = 'col-md-3';
    $itemsPerPage = $gridWidth;
  } break;
  case 6 : {
  	// double row of three
    $gridClass = 'col-md-4';
    $itemsPerPage = $gridWidth * 2;
  } break;
  case 8 : {
  	// double row of four
    $gridClass = 'col-md-3';
    $itemsPerPage = $gridWidth * 2;
  } break;
}

if ( $isCarousel ) {
	$itemCount = count($people);
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
	  $length = ( $length > count($people) ) ? count($people) : $length;

	  $pageSetBackwards[] = array_splice($people, $start, $length);

	  $cPage--;
	} while ( $cPage > 0);

	// reverse pages array
	$pageSet = array_reverse($pageSetBackwards);
}


?>
<?php if ( $hasTitle ) : ?>
	<style>
	#<?php echo $moduleIdentifier; ?>{}
	#<?php echo $moduleIdentifier; ?> .topclip h2{
  <?php if ( !empty(get_sub_field('title_colour') ) ) : ?>
  color: <?php echo get_sub_field('title_colour'); ?>;
  <?php endif; ?>
  <?php if ( !empty(get_sub_field('title_opacity') ) ) : ?>
  opacity: <?php echo get_sub_field('title_opacity'); ?>;
  <?php endif; ?>
	}
	</style>
<?php endif; ?>
	<section class="module people" id="<?php echo $moduleIdentifier; ?>" data-iscarousel="<?php echo ( $isCarousel ) ? 'true' : 'false'; ?>" data-gridwidth="<?php echo $gridWidth; ?>">

<?php if ( $hasTitle ) : ?>
  <header class="topclip">
    <div class="container">
      <div>
        <h2><?php echo get_sub_field('title'); ?></h2>
      </div>
    </div>
  </header>
<?php endif; ?>

		<?php if ( $hasIntroHeader || $hasIntroCopy) : ?> 
		<div class="container teamIntro">
			<div class="row">

				<?php if ( $hasIntroHeader && $hasIntroCopy) : ?>
				<div class="col-lg-6">
					<h3><?php echo $introHeader; ?></h3>
				</div>
				<div class="col-lg-6">
					<?php echo $introCopy; ?>
				</div>
				<?php endif; ?>

				<?php if ( $hasIntroHeader && !$hasIntroCopy) : ?>
				<div class="col">
					<h3><?php echo $introHeader; ?></h3>
				</div>
				<?php endif; ?>

				<?php if ( $hasIntroCopy && !$hasIntroHeader) : ?>
				<div class="col">
					<?php echo $introCopy; ?>
				</div>
				<?php endif; ?>

			</div>
		</div>		
	<?php endif; ?>

<!-- carousel -->
<?php if($isCarousel) : ?>
	<!-- carousel START -->
	<div id="homePeopleCarousel" class="carousel slide" data-bs-ride="carousel" data-gridwidth="<?php echo $gridWidth; ?>">
		<div class="carousel-inner">

<?php foreach( $pageSet AS $index1 => $page) : ?>
			<div class="carousel-item <?php if ( $index1 === 0 ) : ?>active<?php endif; ?>">
		    <div class="container">
		      <div class="row">

      <?php
      foreach( $page AS $index2 => $thisPerson) :
			  $thisPersonImageID = $thisPerson['photo'];
			  $thisPersonImage = wp_get_attachment_image_src($thisPersonImageID, 'full')[0];
      ?>

        <div class="item <?php echo $gridClass; ?>">
          <div class="outer">
            <figure><img alt="<?php echo $thisPerson['name']; ?>" src="<?php echo $thisPersonImage; ?>"></figure>
            <h3><?php echo $thisPerson['name']; ?></h3>
            <h4><?php echo $thisPerson['role']; ?></h4>
            <div class="bioMeta" data-email="" data-linkedin="">
            	<div class="bio"><?php echo $thisPerson['bio']; ?></div>
            </div>
            <a href="<?php echo $thisPerson['link']; ?>" class="arrowLink white noText"><span><?php echo $thisPerson['name']; ?></span></a>
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
	    <button class="carousel-control-prev" type="button" data-bs-target="#homePeopleCarousel" data-bs-slide="prev">
	      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	      <span class="visually-hidden">Previous</span>
	    </button>
	    <div class="carousel-indicators">
	<?php foreach( $pageSet AS $index1 => $page) : ?>
	        <button type="button" data-bs-target="#homePeopleCarousel" data-bs-slide-to="<?php echo $index1; ?>" aria-label="Slide <?php echo ($index1 + 1); ?>" <?php if ($index1 === 0) : ?>class="active" aria-current="true"<?php endif; ?> ></button>
	<?php endforeach; ?>

	    </div>
	    <button class="carousel-control-next" type="button" data-bs-target="#homePeopleCarousel" data-bs-slide="next">
	      <span class="carousel-control-next-icon" aria-hidden="true"></span>
	      <span class="visually-hidden">Next</span>
	    </button>
	  </div>  
	
	</div>
<?php endif; ?> 

<!-- static -->
<?php if(!$isCarousel) : ?>
<!-- not carousel START -->
		<div class="container">
			<div class="row">
<?php
foreach( $people AS $index => $thisPerson) :
  $thisPersonImageID = $thisPerson['photo'];
  $thisPersonImage = wp_get_attachment_image_src($thisPersonImageID, 'full')[0];
?>

        <div class="col-md-4 item">
          <div class="outer">
            <figure><img alt="<?php echo $thisPerson['name']; ?>" src="<?php echo $thisPersonImage; ?>"></figure>
            <h3><?php echo $thisPerson['name']; ?></h3>
            <h4><?php echo $thisPerson['role']; ?></h4>
            <div class="bioMeta" data-email="" data-linkedin="">
            	<div class="bio"><?php echo $thisPerson['bio']; ?></div>
            </div>
            <a href="<?php echo $thisPerson['link']; ?>" class="arrowLink white noText"><span><?php echo $thisPerson['name']; ?></span></a>
          </div>
        </div>
<?php
  // 3 column grid
  if ( $index % 3 === 2 ) :
?>
      </div>
      <div class="row">
<?php
  endif;
endforeach;
?>
			</div>
		</div>
<!-- not carousel END -->
<?php endif; ?> 


			<?php if ( $hasFollowingLink ) : ?>
			<div class="container text-end g-0">
				<a href="<?php echo get_sub_field('link_url'); ?>" class="arrowLink white" <?php if ( get_sub_field('link_target') !== 'self' ) : ?>target="_blank"<?php endif; ?>><?php echo get_sub_field('link_text'); ?></a>
			</div>
			<?php endif; ?>
	</section>
