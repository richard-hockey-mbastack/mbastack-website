<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <!-- <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="a30fabaf-4962-454e-b179-584a4f866abc" data-blockingmode="auto" type="text/javascript"></script> -->
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

<!-- 
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@mbastack" />
<meta name="twitter:title" content="MBAstack" />
<meta name="twitter:description" content="MBAstack" />
<meta name="twitter:image" content="" />
-->

<!-- facebook opengraph metatags -->
    <link rel="icon" href="<?php echo site_url(); ?>/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo site_url(); ?>/favicon.ico" type="image/x-icon" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/styles.css?t=<?php echo file_timestamp('/assets/css/styles.css'); ?>" rel="stylesheet">
    <!-- <link href="<?php echo get_template_directory_uri().auto_version('/assets/css/styles.css'); ?>" rel="stylesheet"> -->

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script> -->
<?php
wp_head();
?>

</head>
<body>

<!-- Google Tag Manager --> 
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PKQMRP" 
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> 
<script>(function (w, d, s, l, i) {
w[l] = w[l] || []; w[l].push({
'gtm.start':
new Date().getTime(), event: 'gtm.js'
}); var f = d.getElementsByTagName(s)[0],
j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
'//www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'dataLayer', 'GTM-PKQMRP');</script> 
<!-- End Google Tag Manager -->

<header class="navheader">

<nav class="navbar navbar-expand-xl navbar-dark">
  <div class="container-fluid">

    <a class="navbar-brand" href="<?php echo site_url(); ?>" title="MBAstack">
      <img class="navbar-brand-desktop show" src="<?php echo get_template_directory_uri(); ?>/assets/svg/LOGO/MBAstack logo_MBAstack-logo-msq-white.svg" alt="MBAstack" style="width:auto;height:72px;">
      <img class="navbar-brand-menu" src="<?php echo get_template_directory_uri(); ?>/assets/svg/LOGO/MBAstack logo_MBAstack-logo-msq-colour.svg" alt="MBAstack" style="width:auto;height:72px;">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

      <?php
        wp_nav_menu( array(
          'theme_location' => 'primary',
          'menu_class' => 'navbar-nav',
          'container' => false,
        ) );
      ?>

        <div class="socialMedia">
          <div class="outer">
            <p>Connect with us</p>
            <ul>
            <?php get_template_part( 'blocks/snippets/linkedin','',['linkedin' => 'https://www.linkedin.com/company/mba-stack/' ]); ?>
            <?php get_template_part( 'blocks/snippets/twitter','',['twitter' => 'https://twitter.com/MBAstack' ]); ?>
            <?php get_template_part( 'blocks/snippets/instagram','',['instagram' => 'https://www.instagram.com/mbastack/' ]); ?>
            <?php get_template_part( 'blocks/snippets/facebook','',['facebook' => 'https://www.facebook.com/mbastack/' ]); ?>
            </ul>
          </div>
        </div>

    </div>

  </div>
</nav>
</header>
