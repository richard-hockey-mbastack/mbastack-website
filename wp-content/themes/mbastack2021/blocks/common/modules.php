<?php
/* 'Modules' modules flexible content element
layout types:
single_column
two_column
latest_clients
full_width_image
people
news
cxm
tools
group
nimble
case_studies_shared_version
*/

if ( have_rows('modules') ) :
  while ( have_rows('modules') ) :
    the_row();
    $moduleLayout = get_row_layout();

      $moduleIdentifier = "module-".$moduleLayout."-".get_row_index();

      // echo "<p class=\"testex\">module: $moduleLayout</p>\n";

      switch ( $moduleLayout ) :

        case 'single_column' : {
          get_template_part( 'blocks/common/modules/single_column','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'two_column' :{
          get_template_part( 'blocks/common/modules/two-column','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'full_width_image' : {
          get_template_part( 'blocks/common/modules/full_width_image','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // home page + work
        case 'latest_clients' : {

          /*
            clients_to_display[
              client (WP_POST 'clients' ID)
            ]

            WP_POST 'clients'[
              title
              logo (image ID)
            ]            
          */ 

          $selectedClientIDs = [];
          $clients = [];

          // manually select which clients to display
          
          foreach( get_sub_field('clients_to_display') AS $logo ) {
            $thisItem = get_post($logo['client']);

            $selectedClientIDs[] = $thisItem->ID;
            $clients[] = [
              'id' => $thisItem->ID,
              'title' => get_field('title', $thisItem->ID),
              'logo' => get_field('logo', $thisItem->ID)
            ];
          }

          // get rest of clients, omitting selected items          
          $queryArguments = [
            'post_type' => 'clients',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order'   => 'ASC',
            'post__not_in' => $selectedClientIDs
          ];

          $the_query = new WP_Query( $queryArguments );
          $initalPostCount = $the_query->post_count;

          if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
              $the_query->the_post();

              $post_id = get_the_ID();
              $clients[] = [
                'id' => $post_id,
                'title' => get_field('title', $post_id),
                'logo' => get_field('logo', $post_id)
              ];
            }          
          }
          wp_reset_postdata();

          $arguments = [
            'module_identifier' => $moduleIdentifier,
            'block-title' => get_sub_field('title'),
            'block-title-colour' => get_sub_field('title_colour'),
            'block-subtitle' => get_sub_field('subtitle'),
            'block-subtitle-colour' => get_sub_field('subtitle_colour'),
            'background-colour' => get_sub_field('background_colour'),
            'background-image' => get_sub_field('background_image'),
            'grid-width' => get_sub_field('grid_width'),
            'block-link-present' => get_sub_field('block_link_present'),
            'block-link' => get_sub_field('block_link'),
            'clients' => $clients
          ];

          get_template_part( 'blocks/common/modules/latest-clients','',$arguments);
        } break;

        // home page
        case 'case_studies_shared_version' : {
          get_template_part( 'blocks/common/modules/case_studies_shared_version','',['module_identifier' => $moduleIdentifier, 'layoutClass' => 'gridLayout' ]);
        } break;

        // work page
        case 'case_studies_masonry' : {
          get_template_part( 'blocks/common/modules/case_studies_masonry','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // home page + people
        case 'people' : {
          get_template_part( 'blocks/common/modules/people','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // home page + news
        case 'news' : {
          get_template_part( 'blocks/common/modules/news','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // who we are
        case 'who_we_are_intro_block' : {
          get_template_part( 'blocks/common/modules/who_we_are_intro_block','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'cxm' : {
          get_template_part( 'blocks/common/modules/cxm','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'fuse' : {
          get_template_part( 'blocks/common/modules/fuse','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // who we are
        case 'tools' : {
          get_template_part( 'blocks/common/modules/tools','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // who we are
        case 'six_degrees' : {
          get_template_part( 'blocks/common/modules/six_degrees','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // who we are
        case 'nimble' : {
          get_template_part( 'blocks/common/modules/nimble','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // who we are
        case 'group' : {
          get_template_part( 'blocks/common/modules/group','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // values
        case 'trinity' : {
          get_template_part( 'blocks/common/modules/trinity','',['module_identifier' => $moduleIdentifier ]);
        } break;

        // values + contact
        case 'two_column_parallax' : {
          get_template_part( 'blocks/common/modules/twoColumnParallax','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'values_blocks' : {
          get_template_part( 'blocks/common/modules/values_blocks','',['module_identifier' => $moduleIdentifier ]);
        } break;
        
        // contact
        case 'awards' : {
          get_template_part( 'blocks/common/modules/awards','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'mismatched_images' : {
          get_template_part( 'blocks/common/modules/mismatched_images','',['module_identifier' => $moduleIdentifier ]);
        } break;

        case 'form_block' : {
          get_template_part( 'blocks/common/modules/form','',['module_identifier' => $moduleIdentifier ]);
        } break;

      endswitch;
  endwhile;
endif;
?>

