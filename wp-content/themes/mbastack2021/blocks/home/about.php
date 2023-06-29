<?php
/*
module: home/about/
source/scss/pages/home.scss -> assets/css/styles.css
*/

/*
home_about_title
home_about_copy
home_about_link_present
home_about_link[
  home_about_linktext
  home_about_linkurl
  home_about_target
]
home_about_image
home_about_image_alt
home_about_image_width
*/

$linkProperties = false;
$copy = get_field('home_about_copy');
$isThereALink = get_field('home_about_link_present');
if( $isThereALink[0] === 'yes' ) {
  $thisLink = get_field('home_about_link');
  $linkProperties = [
    'text' => $thisLink['home_about_linktext'],
    'url' => $thisLink['home_about_linkurl'],
    'target' => $thisLink['home_about_target']
  ];
}
$image = get_field('home_about_image');
$imageURL = ( !empty($image) ) ? wp_get_attachment_image_src($image, 'full')[0] : '';
$imageAlt = get_field('home_about_image_alt');
?>

  <section class="module about" id="home-about">
    <header>
      <h2><?php echo get_field('home_about_title'); ?></h2>
    </header>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 copy">
          <div class="text">
            <div>
              <?php echo $copy; ?>
            </div>
            <?php if ($linkProperties) : ?>
              <a href="<?php echo $linkProperties['url']; ?>" class="arrowLink white" <?php if ($linkProperties['target'] !== 'self') : ?>target="_blank"<?php endif; ?>><?php echo $linkProperties['text']; ?></a>  
            <?php endif; ?> 
            
          </div>
        </div>
        <div class="col-md-6 chart">
          <figure><img alt="<?php echo $imageAlt; ?>" src="<?php echo $imageURL; ?>" <?php if ( get_field('home_about_image_width') ) : ?>style="width:<?php echo get_field('home_about_image_width'); ?>"<?php endif; ?>></figure>
        </div>
    </div>
  </section>
