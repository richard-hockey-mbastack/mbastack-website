<?php
/*
module: home/casestudies/
source/scss/pages/home.scss -> assets/css/styles.css

home_case_studies[
  home_case_study[
    client
    campaign
    synopsis
    link
    image (id)
  ]
  ...
]
*/

$homeCaseStudies = get_field('home_case_studies');
?>

  <section class="module caseStudies">
    <header>
      <h2>WORK</h2>
    </header>
    <div class="container">
      <div class="row">

<?php
foreach ($homeCaseStudies as $inex => $caseStudyContainer) :
$thisCaseStudy = $caseStudyContainer['home_case_study'];
$thisCaseStudyImageURL = ( !empty($thisCaseStudy['image']) ) ? wp_get_attachment_image_src($thisCaseStudy['image'], 'full')[0] : '';
?>
        <div class="col-md-6 item">
          <div class="outer">
              <div class="copy">
                <h3><?php echo $thisCaseStudy['client']; ?> </h3>
                <h4><?php echo $thisCaseStudy['campaign']; ?> <a href="<?php echo $thisCaseStudy['link']; ?>" class="arrowLink noText"><span>Read case study</span></a></h4>
                <p><?php echo $thisCaseStudy['synopsis']; ?></p>
              </div>
              <figure><a href="<?php echo $thisCaseStudy['link']; ?>" title="<?php echo $thisCaseStudy['campaign']; ?>"><img alt="<?php echo $thisCaseStudy['campaign']; ?>" src="<?php echo $thisCaseStudyImageURL; ?>"></a></figure>
          </div>
        </div>
<?php endforeach; ?>

      </div>
      <div class="container text-end g-0">
        <a href="/work/" class="arrowLink">more work</a>
      </div>
    </div>
  </section>
