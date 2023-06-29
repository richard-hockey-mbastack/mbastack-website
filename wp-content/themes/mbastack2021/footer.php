<footer class="pagefooter">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 address1">
        
        <div class="addresses">
          <div>
            <h3>MBAstack London</h3>
            <address>34 Bow Street,<br>London,<br>WC2E 7AU</address>
            <!--  -->
            <p class="phone" data-tel="+442079273500">020 7927 3500</p>
            <a href="mailto:hello@mbastack.com" class="email">hello@mbastack.com</a>
            <a href="/contact-london/" class="gettingHere">Getting here</a>
          </div>
          <div>
            <h3>MBAstack NY</h3>
            <address>71 Fifth Avenue, 8th Floor,<br>New York, <br>NY 10003</address>
            <p class="phone" data-tel="+12125083400">(212) 508-3400</p>
            <a href="mailto:hello@mbastack.com" class="email">hello@mbastack.com</a>
            <a href="/contact-new-york/" class="gettingHere">Getting here</a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 tailnav">
      <?php
        wp_nav_menu( array(
          'theme_location' => 'footer',
          'menu_class' => 'navbar-nav',
          'container' => false,
        ) );
      ?>
      </div>
    </div>
  </div>    
  <div class="container ftwo">
    <div class="row">
      <div class="col-lg-4 socialMedia">
        <div class="outer">
          <ul>
            <?php get_template_part( 'blocks/snippets/linkedin','',['linkedin' => 'https://www.linkedin.com/company/mba-stack/' ]); ?>
            <?php get_template_part( 'blocks/snippets/twitter','',['twitter' => 'https://twitter.com/MBAstack' ]); ?>
            <?php get_template_part( 'blocks/snippets/instagram','',['instagram' => 'https://www.instagram.com/mbastack/' ]); ?>
            <?php get_template_part( 'blocks/snippets/facebook','',['facebook' => 'https://www.facebook.com/mbastack/' ]); ?>
          </ul>
        </div>
      </div>
      <div class="col-lg-8 copyright">
        <p><span>&copy;</span>MBAstack, 2022. All rights reserved.</p>
        <ul id="menu-footer-links" class="navbar-nav">
          <li id="menu-item-41" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-privacy-policy menu-item-41"><a href="/privacy-policy/">Privacy Policy</a></li>
          <li id="menu-item-49" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-privacy-policy menu-item-49"><a href="/cookie-policy/">Cookie Policy</a></li>
        </ul>        
      </div>
    </div>
  </div>

  <div class="tail">
    <a class="navbar-brand" href="<?php echo site_url(); ?>" title="MBAstack">

      <!-- SVG colour logo -->
      <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/LOGO/MBAstack logo_MBAstack-logo-msq-colour.svg" alt="MBAstack">

    </a>
  </div>
</footer>

<?php if(true) : ?>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/library/parallax.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9K1uqYnEWHVrYvOc7iQ4mJ2ZFX8ODOPk&callback=initmap"></script>

<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script> -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/library/slick.min.js"></script>

<!-- autoplay inline lottie animation player -->
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<!-- scroll triggered lottie player -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.6.6/lottie.min.js"></script>

<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.3.1/gsap.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollTrigger.min.js"></script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/ScrollTrigger.min.js"></script>

<!--
-->
<?php endif; ?>

<!-- vimeo player SDK -->
<script src="https://player.vimeo.com/api/player.js"></script>

<?php wp_footer(); ?>
</body>
</html>
