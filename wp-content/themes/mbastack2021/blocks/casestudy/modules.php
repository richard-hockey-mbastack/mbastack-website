<?php
/*
'Case Study Modules'
flexible content element 'case_study_modules'
*/

if ( have_rows('case_study_modules') ) :

  $moduleCount = count(get_field('case_study_modules'));
  $n = 1;

  while ( have_rows('case_study_modules') ) :
    the_row();
    $moduleLayout = get_row_layout();

      $moduleIdentifier = "module-".$moduleLayout."-".get_row_index();

      $z = ($moduleCount - $n) + 1;
      switch ( $moduleLayout ) :

        case 'banner' : {
          get_template_part( 'blocks/casestudy/modules/banner','',['module_identifier' => $moduleIdentifier, 'title' => get_field('title'),'z' => $z]);
        } break;

        case 'intro' : {
          get_template_part( 'blocks/casestudy/modules/intro','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'text-and-image' : {
          get_template_part( 'blocks/casestudy/modules/text-and-image','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'full_width_image' : {
          get_template_part( 'blocks/casestudy/modules/full_width_image','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'full_width_video' : {
          get_template_part( 'blocks/casestudy/modules/full_width_video','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'audio_player' : {
          get_template_part( 'blocks/casestudy/modules/audio_player','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'two_column_media' : {
          get_template_part( 'blocks/casestudy/modules/two_column_media','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'mismatched_media' : {
          get_template_part( 'blocks/casestudy/modules/mismatched_media','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;
        
        case 'triple_staggered_images' : {
          get_template_part( 'blocks/casestudy/modules/triple_staggered_images','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'image_carousel' : {
          get_template_part( 'blocks/casestudy/modules/image_carousel','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'media_carousel' : {
          get_template_part( 'blocks/casestudy/modules/media_carousel','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'awards' : {
          get_template_part( 'blocks/casestudy/modules/awards','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'two_column_text' : {
          get_template_part( 'blocks/casestudy/modules/two_column_text','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'single_column_text' : {
          get_template_part( 'blocks/casestudy/modules/single_column_text','',['module_identifier' => $moduleIdentifier,'z' => $z ]);
        } break;

        case 'share' : {
          get_template_part( 'blocks/casestudy/modules/share','',['module_identifier' => $moduleIdentifier, 'id' => $args['id'],'z' => $z ]);
        } break;
    
        case 'more_case_studies' : {
          get_template_part( 'blocks/casestudy/modules/more_case_studies','',['module_identifier' => $moduleIdentifier, 'id' => $args['id'],'z' => $z ]);
        } break;

      endswitch;
      $n++;
  endwhile;
endif;
?>

