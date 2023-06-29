<?php
/*
module: home/people/
source/scss/pages/home.scss -> assets/css/styles.css
*/

/*
home_people[
  person[
    name
    role
    image
    link
  ]
]
*/

$people = get_field('home_people');
?>
  <section class="module people">
    <header>
      <h2>PEOPLE</h2>
    </header>
    <div class="container">
      <div class="row">
<?php
foreach( $people AS $index => $personContainer) :
  $thisPerson = $personContainer['person'];
  $thisPersonImageID = $thisPerson['image'];
  $thisPersonImage = ( !empty($thisPersonImageID) ) ? wp_get_attachment_image_src($thisPersonImageID, 'full')[0] : '';
?>

        <div class="col-md-4 item">
          <div class="outer">
            <figure><img alt="<?php echo $thisPerson['name']; ?>" src="<?php echo $thisPersonImage; ?>"></figure>
            <h3><?php echo $thisPerson['name']; ?></h3>
            <h4><?php echo $thisPerson['role']; ?></h4>
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

      <div class="container text-end g-0">
        <a href="/people/" class="arrowLink white">more people</a>
      </div>
    </div>
  </section>
