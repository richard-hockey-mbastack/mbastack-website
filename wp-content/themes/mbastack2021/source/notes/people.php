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
	<!-- carousel END -->
<?php endif; ?> 

<!--
---------------------------------------------------------------------------------------------------
-->


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

