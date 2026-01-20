<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package project-name
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta name="viewport" content="initial-scale=1">
	<meta name="viewport" content="maximum-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">   
  <link href="//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.css" type="text/css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/>
  <!-- Add the slick-theme.css if you want default styling -->
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"/>
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>
  data-component="class-toggle"
  data-class-toggle-target-class="menu-open">
	<!-- <div id="mobile-menu-overlay"></div> -->
 <header
    id="main-header"
    class="active structure-block"
    data-component="collapsible-header">
    <div class="header__content">
      <div class="header-1">
      <a
        aria-label="Ir para home"
        id="logo-anchor"
        href="<?php echo esc_url( home_url( '/' ) ); ?>">

        <?php
          $logo_svg = carbon_get_theme_option( 'logo' );

          if ( ! empty( $logo_svg ) ) {
            echo $logo_svg;
          }
        ?>

      </a>






      </div>
      <div class="header-2">
        <button
          aria-label="abrir menu mobile"
          id="mobile-menu-trigger"
          data-component="trigger"
          data-trigger-target="body">
          <div>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </button>

        <nav
          class="<?php if (is_archive()){echo"header-justify";}?> header-menu-container"
          id="main-menu-container"
          data-component="menu">
          <?php
            wp_nav_menu(array(
              'theme_location' => 'main-menu',
              'menu_id'        => 'main-menu',
            ));
          ?>
          <?php 
            // if (is_archive()) {
            //     echo '<div id="menu-filter">Filtrar por 
            //     <img id="open-filter"alt="abrir filtro" src="'.get_template_directory_uri().'/resources/icons/seta-top.png">
            //     </div>';
            // }
          ?>
          <div class="only-mobile">

          	<ul class="social-media">

                    <?php if ( $instagram = carbon_get_theme_option('instagram') ) : ?>
                    <li>
                      <a target="_blank" rel="noopener noreferrer"
                      href="<?php echo esc_url( $instagram ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                          <g clip-path="url(#clip0_78_282)">
                          <path d="M10.4999 1.89081C13.3054 1.89081 13.6376 1.90311 14.7409 1.95233C15.7663 1.99745 16.32 2.16971 16.6891 2.31327C17.1772 2.50194 17.53 2.73162 17.895 3.09666C18.2641 3.4658 18.4897 3.81443 18.6784 4.30251C18.8219 4.67165 18.9942 5.22946 19.0393 6.25074C19.0885 7.35815 19.1009 7.69038 19.1009 10.4917C19.1009 13.2972 19.0885 13.6294 19.0393 14.7327C18.9942 15.7581 18.8219 16.3118 18.6784 16.6809C18.4897 17.169 18.26 17.5218 17.895 17.8868C17.5259 18.2559 17.1772 18.4815 16.6891 18.6702C16.32 18.8137 15.7622 18.986 14.7409 19.0311C13.6335 19.0803 13.3013 19.0926 10.4999 19.0926C7.69448 19.0926 7.36226 19.0803 6.25894 19.0311C5.23356 18.986 4.67985 18.8137 4.31071 18.6702C3.82263 18.4815 3.4699 18.2518 3.10486 17.8868C2.73572 17.5177 2.51014 17.169 2.32147 16.6809C2.17792 16.3118 2.00565 15.754 1.96053 14.7327C1.91132 13.6253 1.89901 13.2931 1.89901 10.4917C1.89901 7.68628 1.91132 7.35405 1.96053 6.25074C2.00565 5.22536 2.17792 4.67165 2.32147 4.30251C2.51014 3.81443 2.73983 3.4617 3.10486 3.09666C3.474 2.72752 3.82263 2.50194 4.31071 2.31327C4.67985 2.16971 5.23766 1.99745 6.25894 1.95233C7.36226 1.90311 7.69448 1.89081 10.4999 1.89081ZM10.4999 0C7.64936 0 7.29253 0.0123046 6.17281 0.061523C5.05719 0.110741 4.29021 0.291209 3.62576 0.549606C2.9326 0.820307 2.34608 1.17714 1.76366 1.76366C1.17714 2.34608 0.820307 2.9326 0.549606 3.62166C0.291209 4.29021 0.110741 5.05309 0.061523 6.16871C0.0123046 7.29253 0 7.64936 0 10.4999C0 13.3505 0.0123046 13.7073 0.061523 14.8271C0.110741 15.9427 0.291209 16.7097 0.549606 17.3741C0.820307 18.0673 1.17714 18.6538 1.76366 19.2362C2.34608 19.8186 2.9326 20.1796 3.62166 20.4462C4.29021 20.7046 5.05309 20.885 6.16871 20.9342C7.28843 20.9835 7.64526 20.9958 10.4958 20.9958C13.3464 20.9958 13.7032 20.9835 14.823 20.9342C15.9386 20.885 16.7056 20.7046 17.37 20.4462C18.0591 20.1796 18.6456 19.8186 19.228 19.2362C19.8104 18.6538 20.1714 18.0673 20.438 17.3782C20.6963 16.7097 20.8768 15.9468 20.926 14.8312C20.9753 13.7114 20.9876 13.3546 20.9876 10.504C20.9876 7.65347 20.9753 7.29663 20.926 6.17691C20.8768 5.0613 20.6963 4.29431 20.438 3.62986C20.1796 2.9326 19.8227 2.34608 19.2362 1.76366C18.6538 1.18124 18.0673 0.820307 17.3782 0.553707C16.7097 0.295311 15.9468 0.114843 14.8312 0.0656246C13.7073 0.0123046 13.3505 0 10.4999 0Z" fill="white"/>
                          <path d="M10.499 5.10645C7.52127 5.10645 5.10547 7.52225 5.10547 10.5C5.10547 13.4777 7.52127 15.8935 10.499 15.8935C13.4767 15.8935 15.8925 13.4777 15.8925 10.5C15.8925 7.52225 13.4767 5.10645 10.499 5.10645ZM10.499 13.9986C8.56716 13.9986 7.00038 12.4318 7.00038 10.5C7.00038 8.56814 8.56716 7.00135 10.499 7.00135C12.4308 7.00135 13.9976 8.56814 13.9976 10.5C13.9976 12.4318 12.4308 13.9986 10.499 13.9986Z" fill="white"/>
                          <path d="M17.366 4.89296C17.366 5.59022 16.8 6.15213 16.1068 6.15213C15.4096 6.15213 14.8477 5.58612 14.8477 4.89296C14.8477 4.1957 15.4137 3.63379 16.1068 3.63379C16.8 3.63379 17.366 4.1998 17.366 4.89296Z" fill="white"/>
                          </g>
                          <defs>
                          <clipPath id="clip0_78_282">
                          <rect width="20.9999" height="20.9999" fill="white"/>
                          </clipPath>
                          </defs>
                        </svg>
                      </a>
                    </li>
                    <?php endif; ?>

                    <?php if ( $linkedin = carbon_get_theme_option('linkedin') ) : ?>
                    <li>
                      <a target="_blank" rel="noopener noreferrer"
                      href="<?php echo esc_url( $linkedin ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                          <g clip-path="url(#clip0_78_287)">
                          <path d="M19.4454 0H1.55038C0.693159 0 0 0.676753 0 1.51347V19.4823C0 20.319 0.693159 20.9999 1.55038 20.9999H19.4454C20.3026 20.9999 20.9999 20.319 20.9999 19.4864V1.51347C20.9999 0.676753 20.3026 0 19.4454 0ZM6.23023 17.895H3.11307V7.87085H6.23023V17.895ZM4.67165 6.50504C3.67087 6.50504 2.86287 5.69703 2.86287 4.70036C2.86287 3.70369 3.67087 2.89568 4.67165 2.89568C5.66832 2.89568 6.47632 3.70369 6.47632 4.70036C6.47632 5.69293 5.66832 6.50504 4.67165 6.50504ZM17.895 17.895H14.7819V13.0224C14.7819 11.8616 14.7614 10.3646 13.1618 10.3646C11.5417 10.3646 11.2956 11.632 11.2956 12.9403V17.895H8.18667V7.87085H11.1726V9.24076H11.2136C11.6279 8.45327 12.645 7.62065 14.1585 7.62065C17.3126 7.62065 17.895 9.69603 17.895 12.3948V17.895Z" fill="white"/>
                          </g>
                          <defs>
                          <clipPath id="clip0_78_287">
                          <rect width="20.9999" height="20.9999" fill="white"/>
                          </clipPath>
                          </defs>
                        </svg>
                      </a>
                    </li>
                    <?php endif; ?>

                    <?php if ( $facebook = carbon_get_theme_option('facebook') ) : ?>
                    <li>
                      <a target="_blank" rel="noopener noreferrer"
                      href="<?php echo esc_url( $facebook ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                          <g clip-path="url(#clip0_78_290)">
                          <path d="M10.4999 0C4.70103 0 0 4.70103 0 10.4999C0 15.424 3.39022 19.5559 7.96357 20.6907V13.7087H5.79848V10.4999H7.96357V9.1173C7.96357 5.54354 9.58098 3.88707 13.0896 3.88707C13.7549 3.88707 14.9028 4.01769 15.3723 4.14789V7.05637C15.1245 7.03033 14.694 7.01731 14.1594 7.01731C12.4378 7.01731 11.7725 7.66957 11.7725 9.3651V10.4999H15.2022L14.613 13.7087H11.7725V20.923C16.9717 20.2951 21.0003 15.8683 21.0003 10.4999C20.9999 4.70103 16.2988 0 10.4999 0Z" fill="white"/>
                          </g>
                          <defs>
                          <clipPath id="clip0_78_290">
                          <rect width="20.9999" height="20.9999" fill="white"/>
                          </clipPath>
                          </defs>
                        </svg>
                      </a>
                    </li>
                    <?php endif; ?>

                    </ul>
                  </div>











          <!-- <a
            class="cc-menu-search link link--icon-lg"
            href="<?php echo get_home_url(); ?>?s=">
          </a> -->
        </nav>
      </div>
    </div> 
	</header>
