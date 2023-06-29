<?php
/*
Modules flexuble content template for layout: people
blocks/common/modules/people
*/

/*
title
title_colour
title_opacity
background_colour
text_colour ('dark','light')
intro_header
intro_copy
is_carousel ('no','yes')
show_bio_overlay  ('no','yes')
page_items (2,3,4,6,8)

people_to_show[
	person[
		<POST ID custom post type 'people'>
	]
]

has_following_link ('no','yes')
link_text
link_target ('self','blank')
;ink_url
*/

/*
swap out people repeatwer for custom poset 'person' post object repeater
	forename
	surname
	role
	portrait_photo
	full_photo
	link
	bio
	linkedin (url/ID) optional
	twitter (url/ID) optional
	instagram (url/ID) optional
	facebook (url/ID) optional
	isAuthor
*/

$moduleIdentifier = $args['module_identifier'];

// check for link from home page people section
// /home/#FORENAME-SURNAME
// /people\/#([a-z\-]+)/
// set overlay to open
// prepopulate fields in overlay

$textStyle = get_sub_field('text_colour');
$hasTitle = ( !empty(get_sub_field('title')) );
$hasBioOverlayTriggers = ( get_sub_field('show_bio_overlay') === 'yes' ) ;
$hasFollowingLink = ( get_sub_field('has_following_link') === 'yes' ) ;
$hasBackgroundColour = (!empty(get_sub_field('background_colour')));
$backgroundColour = get_sub_field('background_colour');
$introHeader = get_sub_field('intro_header');
$introCopy = get_sub_field('intro_copy');
$hasIntroHeader = !empty($introHeader);
$hasIntroCopy = !empty($introCopy);
$isCarousel = (get_sub_field('is_carousel') === 'yes');

$peopleCTP = get_sub_field('people_to_show');
$peopleIDs = [];

$noPeople = empty($peopleCTP);

if ( !$noPeople ) {
	foreach( $peopleCTP AS $peopleX ) {
		$thisPersonID = $peopleX['person'];
		$thisPerson = get_post($thisPersonID);

		$peoplePosts[] = [
			'id' => $thisPersonID,
			'slug' => get_post_field( 'post_name', $thisPersonID),
			'forename' => get_field('forename', $thisPersonID),
			'surname' => get_field('surname', $thisPersonID),
			'role' => get_field('role', $thisPersonID),
			'bio' => get_field('bio', $thisPersonID),
			'full' => get_field('full_photo', $thisPersonID),
			'portrait' => get_field('portrait_photo', $thisPersonID),
			'linkedin' => get_field('linkedin', $thisPersonID),
			'twitter' => get_field('twitter', $thisPersonID),
			'instagram' => get_field('instagram', $thisPersonID),
			'facebook' => get_field('facebook', $thisPersonID),
			'email' => get_field('email', $thisPersonID),
			'phone' => get_field('phone', $thisPersonID),
			'slug' => get_post_field( 'post_name', $thisPersonID)
		];
	}

}
?>


	<?php if ( $hasBioOverlayTriggers ) : ?>
		<div class="bioOverlay" id="bioOverlay">
			<div class="white"></div>
			<div class="blue"  style="position:relative;">
				<a href="" class="closeBioOverlay"><span>CLOSE</span></a>
				<div class="container">
					<div class="row row1">
						
						<div class="col-md-6 photo">
							<figure>
								<img id="bioPhoto" alt="FORENAME SURNAME - ROLE" src="">
							</figure>
						</div>

						<div class="col-md-6 info">
							<h2 id="bioName">FORENAME SURNAME</h2>
							<h3 id="bioRole">ROLE</h3>
							<div id="bioCopy">
								<p>BIO</p>
							</div>

							<div class="socialLinks">
								<h4>Contact <span class="contactForename">FORENAME</span></h4>
	          		<ul>
	          			<?php get_template_part( 'blocks/snippets/linkedin','',['linkedin' => '' ]); ?>
	          			<?php get_template_part( 'blocks/snippets/email','',['email' => '' ]); ?>
	          		</ul>
							</div>

							<ul class="paging">
								<li class="previous"><button class="prevPerson"><span>Previous</span></button></li>
								<li class="next"><button class="nextPerson"><span>Next</span></button></li>
							</ul>
						</div>

					</div>
				</div>
		</div>
	</div>
<?php endif ?>

<style>
#<?php echo $moduleIdentifier; ?>{}
<?php if ( $hasBackgroundColour ) : ?>
#<?php echo $moduleIdentifier; ?>{
background-color: <?php echo $backgroundColour; ?>
}
<?php endif; ?>
<?php if ( $hasTitle ) : ?>
#<?php echo $moduleIdentifier; ?> .topclip h2{
/*
<?php if ( !empty(get_sub_field('title_colour') ) ) : ?>
color: <?php echo get_sub_field('title_colour'); ?>;
<?php endif; ?>
<?php if ( !empty(get_sub_field('title_opacity') ) ) : ?>
opacity: <?php echo get_sub_field('title_opacity'); ?>;
<?php endif; ?>
*/
}
<?php endif; ?>
</style>
<!-- <p class="debug">top <span id="dbTop">-</span>bottom <span id="dbBottom">-</span> height <span id="dbHeight">-</span> scrolltop <span id="dbScrolltop">-</span> start <span id="dbStart">-</span> end <span id="dbEnd">-</span> percent<span id="dbPercent">-</span> </p> -->
	<section class="module people <?php if ( $isCarousel ) : ?>peopleSlickSlider <?php else : ?>peopleGrid <?php endif; ?><?php if ( $hasBioOverlayTriggers ) : ?>bioOverlayTrigger <?php endif; ?><?php echo $textStyle; ?>" id="<?php echo $moduleIdentifier; ?>" data-context="<?php echo $post->ID; ?>">

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

<?php if($isCarousel) : ?>
	<!-- carousel START -->
	<div class="fwop">
		<div class="op">
	  	<div class="ssp">

<?php
			if( !$noPeople ) :
	  	foreach($peoplePosts AS $index => $person) :
	  		$fullname = $person['forename'].' '.$person['surname'];
	  		$thisPersonPortrait = ( !empty($person['portrait']) ) ? wp_get_attachment_image_src($person['portrait'], 'full')[0] : '';
	  		$thisPersonFull = ( !empty($person['full']) ) ? wp_get_attachment_image_src($person['full'], 'full')[0] : '';
	  	?>
	  		<div class="item" id="person-<?php echo $person['id']; ?>">
	        <div class="outer">
	        	<div class="frame">
	        		<a href="/people/#<?php echo $person['slug']; ?>" class="overlay"><span></span></a>
		          <figure><img alt="<?php echo $fullname; ?>" src="<?php echo $thisPersonPortrait; ?>"></figure>
		          <div class="caption">
			          <h3><?php echo $fullname;; ?></h3>
			          <h4><?php echo $person['role']; ?></h4>
			          <div class="falseLink"><div></div></div>
			          <!-- 
		          	<a href="/people/#<?php echo $person['slug']; ?>" class="arrowLink <?php if ( $textStyle === 'light' ) : ?>white<?php else: ?>black<?php endif; ?> noText"><span><?php echo $fullname;; ?></span></a> -->
		          </div>
		         </div>

	           	<div class="biov2">
	          		<div class="padding">
									<h3><?php echo $person['forename']; ?> <?php echo $person['surname']; ?></h3>
									<h4><?php echo $person['role']; ?></h4>
		          		<?php echo html_entity_decode($person['bio']); ?>
	<?php if (!empty( $person['linkedin'] ) || !empty( $person['twitter'] ) || !empty( $person['instagram'] )) : ?>
		          		<div class="socialLinks">

		          		<ul>
		          			<?php if (!empty( $person['linkedin'] )) : get_template_part( 'blocks/snippets/linkedin','',['linkedin' => $person['linkedin'] ]); endif; ?>
							<?php if (!empty( $person['email'] )) : get_template_part( 'blocks/snippets/email','',['email' => $person['email'] ]); endif; ?>
		          		</ul>
		          			
		          		</div>
		          		<?php endif; ?>
		          		<a href="" class="arrowLink black noText"><span>CLOSE</span></a>
		          	</div>
		          </div>

	        	</div>
	  		</div>
	  	<?php
	  	endforeach;
	  	endif;
?>	  		

			</div>
		</div>	  
	</div>
<?php endif; ?>		


<!-- static -->
<?php if(!$isCarousel && $hasBioOverlayTriggers ) : ?>
<!-- not carousel START -->
		<div class="container">
			<div class="row">

<?php
			if( !$noPeople ) :
	  	foreach($peoplePosts AS $index => $person) :
	  		$thisPersonPortrait = ( !empty($person['portrait']) ) ? wp_get_attachment_image_src($person['portrait'], 'full')[0] : '';
	  		$thisPersonFull = ( !empty($person['full']) ) ? wp_get_attachment_image_src($person['full'], 'full')[0] : '';
	  	?>
	  		<div class="item col-md-4" id="person-<?php echo $person['slug']; ?>">
	        <div class="outer">

	        	<div class="frame">
		          <figure>
		          	<img alt="<?php echo $person['forename']; ?> <?php echo $person['surname']; ?>" src="<?php echo $thisPersonPortrait; ?>">
		          </figure>
		          <div class="tail">
		          	<div class="caption">
				          <h3><?php echo $person['forename']; ?> <?php echo $person['surname']; ?></h3>
				          <h4><?php echo $person['role']; ?></h4>
		          	</div>
		          	<a href="/people/#<?php echo $person['slug']; ?>" class="arrowLink <?php if ( $textStyle === 'light' ) : ?>white<?php else: ?>black<?php endif; ?> noText"><span><?php echo $person['forename']; ?> <?php echo $person['surname']; ?></span></a>
		          </div>
	        	</div>

          	<div class="biov2">
          		<div class="padding">
								<h3><?php echo $person['forename']; ?> <?php echo $person['surname']; ?></h3>
								<h4><?php echo $person['role']; ?></h4>
	          		<?php echo html_entity_decode($person['bio']); ?>
<?php if (!empty( $person['linkedin'] ) || !empty( $person['twitter'] ) || !empty( $person['instagram'] )) : ?>
	          		<div class="socialLinks">

	          		<ul>
	          			<?php if (!empty( $person['linkedin'] )) : get_template_part( 'blocks/snippets/linkedin','',['linkedin' => $person['linkedin'] ]); endif; ?>
						<?php if (!empty( $person['email'] )) : get_template_part( 'blocks/snippets/email','',['email' => $person['email'] ]); endif; ?>
	          		</ul>
	          			
	          		</div>
	          		<?php endif; ?>
	          		<a href="" class="arrowLink black noText"><span>CLOSE</span></a>
          		</div>
	          </div>

	        </div>
	  		</div>
	  	<?php
	  	endforeach;
	  	endif;
?>	  		

			</div>
		</div>
<!-- not carousel END -->
<?php endif; ?> 


		<?php if ( $hasFollowingLink ) : ?>
		<div class="container text-end g-0">
			<a href="<?php echo get_sub_field('link_url'); ?>" class="arrowLink followLink <?php if ( $textStyle === 'light' ) : ?>white<?php else: ?>black<?php endif; ?>" <?php if ( get_sub_field('link_target') !== 'self' ) : ?>target="_blank"<?php endif; ?>><?php echo get_sub_field('link_text'); ?></a>
		</div>
		<?php endif; ?>

		<div class="imageBin"></div>
	</section>

