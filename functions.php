<?php

add_action('wp_enqueue_scripts', 'bk');
function bk() {
    // garante estilos do core
    wp_enqueue_style('wp-block-library');
    wp_enqueue_style('wp-block-library-theme');

    wp_enqueue_style(
        'customstyle',
        get_template_directory_uri() . '/css/style.css',
        array(),
        filemtime(get_template_directory() . '/css/style.css'),
        'all'
    );
    wp_enqueue_style('fontstyle', get_template_directory_uri() . '/font-style.css', array(), '1.0.1', 'all');
    wp_enqueue_style('slickcss', get_template_directory_uri() . '/slick/slick.css', array(), '1.8.0', 'all');
    wp_enqueue_style('slicktheme', get_template_directory_uri() . '/slick/slick-theme.css', array(), '1.8.0', 'all');
    wp_enqueue_script('slickjs',  get_template_directory_uri() . '/slick/slick.js', array('jquery'), '', false);
    wp_enqueue_script(
        'customjs',
        get_template_directory_uri() . '/js/index.js',
        array('jquery'), // remova se não usar jQuery
        filemtime( get_template_directory() . '/js/index.js' ),
        true // carrega no footer
      );
}


// function legit_block_editor_styles() {
//     wp_enqueue_style( 'legit-editor-styles', get_theme_file_uri( '/style-editor.css' ), false, '2.3', 'all' );
// } 
// add_action( 'enqueue_block_editor_assets', 'legit_block_editor_styles' );

function bk_add_custom_image_sizes() {

     // Add "vertical" image
    add_image_size( 'vertical', 600, 730, true);
    add_image_size( 'vertical-larger', 890, 970, true);
    //horizontal
    add_image_size( 'horizontal', 740, 540, true);
    add_image_size( 'horizontal-b', 1700, 780, true);
    add_image_size( 'horizontal-c', 381, 226, true);
    add_image_size( 'horizontal-plus', 715, 225, true);
    add_image_size( 'quarter', 225, 225, true);
    add_image_size( 'horizontal16x9', 1200, 675, true);

    //others
    add_image_size('image_desktop_full_no_crop', 3000 , 3500, false);
}

add_action('after_setup_theme', 'bk_add_custom_image_sizes' );

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {
    Container::make( 'theme_options', __( 'Theme Options', 'crb' ) )
        ->add_fields( array(
            Field::make( 'text', 'email', 'E-mail Geral' ),
            Field::make( 'text', 'novospublicacoes', 'Novos publicacoes' ),
            Field::make( 'text', 'imprensa', 'Imprensa' ),
            Field::make( 'rich_text', 'endereco', 'Endereço' ),

            Field::make( 'text', 'instagram', 'Instagram' ),
            Field::make( 'text', 'youtube', 'Youtube' ),
            Field::make( 'text', 'facebook', 'Facebook' ),
            Field::make( 'text', 'linkedin', 'Linkedin' ),
            Field::make( 'textarea', 'logo', 'Logo svg' ),

        ) );
}

// Permitir upload de SVG
function allow_svg_upload( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'allow_svg_upload' );
  




add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	//define o caminho para o carbon-fields
	define(
		'Carbon_Fields\URL',
		get_template_directory_uri() . '/vendor/htmlburger/carbon-fields'
	);
  require_once('vendor/autoload.php');
  \Carbon_Fields\Carbon_Fields::boot();
}
add_action('after_setup_theme', 'register_carbon_fields');
function register_carbon_fields() {
	require_once('blocks/load.php');
}

///////////
///MENU////
///////////


/**
 * Main menu navigation
 */
register_nav_menus(array(
  'main-menu' => 'Menu principal',
  'footer' => 'Footer',
));

add_action( 'wp_head', 'add_viewport_meta_tag' , '1' );

function add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
}

///////////
////post///
///////////
function my_theme_setup(){
    add_theme_support('post-thumbnails');
    add_image_size('cc__thumbnail_a4_horizontal_crop', 999, 700, array('center', 'center'));
}

add_action('after_setup_theme', 'my_theme_setup');

//////////
//excerpt//
///////////
add_post_type_support( 'page', 'excerpt' );


////////
//vcard///
///////////
function _thz_enable_vcard_upload( $mime_types ){
    $mime_types['vcf'] = 'text/vcard';
    $mime_types['vcard'] = 'text/vcard';
    return $mime_types;
}
add_filter('upload_mimes', '_thz_enable_vcard_upload' );





/**
* Removes or edits the 'Protected:' part from posts titles
*/
function the_title_trim($title) {

    $title = esc_attr($title);

    $findthese = array(
        '#Protected:#',
        '#Private:#',
        '#Protegido:#'
    );

    $replacewith = array(
        '', // What to replace "Protected:" with
        '' // What to replace "Private:" with
    );

    $title = preg_replace($findthese, $replacewith, $title);
    return $title;
}
add_filter('the_title', 'the_title_trim');

// enable gutenberg for woocommerce
function activate_gutenberg_product( $can_edit, $post_type ) {
 if ( $post_type == 'product' ) {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'activate_gutenberg_product', 10, 2 );

// enable taxonomy fields for woocommerce with gutenberg on
function enable_taxonomy_rest( $args ) {
    $args['show_in_rest'] = true;
    return $args;
}
add_filter( 'woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest' );

/////////// 
/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = 3;
  return $cols;
}






/**
 * Remove product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );          // Remove the description tab
    unset( $tabs['reviews'] );          // Remove the reviews tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab

    return $tabs;
}

add_filter ('yith_wcan_use_wp_the_query_object', '__return_true');

/**
* Create taxonomy portfolio
*/

////////////////
////taxonomia///
////////////////

/* -- Post Type - publicacoes -- */
function custom_post_type_publicacoes() {
    $labels = [
        "name" => __( "Publicações"),
        "singular_name" => __( "publicacoes"),
    ];

    $args = [
        "label" => __( "publicacoes"),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "delete_with_user" => false,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => [ "slug" => "publicacoes", "with_front" => false, 'hierarchical' => true ],
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-book-alt",
        "supports" => [ "title", "editor", "thumbnail", "excerpt", "trackbacks", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats" ],
        "taxonomies" => [ "publicacao"],
    ];

    register_post_type( "publicacoes", $args );
}

add_action( 'init', 'custom_post_type_publicacoes' );

/* ------------------------------ Taxonomias - Genero -----------------------------*/
function custom_taxonomy_publicacao() {

    /**
     * Taxonomy: publicacao.
     */

    $labels = [
        "name" => __( "Tipos de publicação"),
        "singular_name" => __( "Tipo de publicaçãoo"),
    ];

    $args = [
        "label" => __( "Tipo de publicação"),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => [ "slug" => "publicacao", "with_front" => false, 'hierarchical' => true ],
        "show_admin_column" => true,
        "show_in_rest" => true,
        "rest_base" => "publicacao",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "show_in_quick_edit" => true,
    ];

    register_taxonomy( "publicacao", [ "publicacoes" ], $args );
}
add_action( 'init', 'custom_taxonomy_publicacao' );

// Campo Ordem - adicionar
add_action('publicacao_add_form_fields', function () {
    ?>
    <div class="form-field">
      <label for="term_order">Ordem</label>
      <input type="number" name="term_order" id="term_order" value="0">
      <p class="description">Define a ordem de exibição.</p>
    </div>
    <?php
  });
  
  // Campo Ordem - editar
  add_action('publicacao_edit_form_fields', function ($term) {
    $order = get_term_meta($term->term_id, 'term_order', true);
    ?>
    <tr class="form-field">
      <th scope="row"><label for="term_order">Ordem</label></th>
      <td>
        <input type="number" name="term_order" id="term_order" value="<?php echo esc_attr($order); ?>">
        <p class="description">Define a ordem de exibição.</p>
      </td>
    </tr>
    <?php
  });
  
  // Salvar campo Ordem
  add_action('created_publicacao', 'save_publicacao_order');
  add_action('edited_publicacao', 'save_publicacao_order');
  
  function save_publicacao_order($term_id) {
    if (isset($_POST['term_order'])) {
      update_term_meta($term_id, 'term_order', intval($_POST['term_order']));
    }
  }
  
//add breadcrumb
function get_breadcrumb() {
    echo ' <div class="breadcrumb structure-container" >
    <div class="structure-container__content structure-container__side">
    <div class="breadcrumb__content">
    <a href="'.home_url().'" rel="nofollow">Página inicial</a>';
    if (is_category() || is_single()) {
        echo "&nbsp;&nbsp;>&nbsp;&nbsp;";
        the_category(' &bull; ');
            if (is_single()) {
                echo " &nbsp;&nbsp;>&nbsp;&nbsp; ";
                the_title();
            }
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;>&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;&nbsp;>&nbsp;&nbsp;Search Results for... ";
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
    echo '</div>
    </div>
    </div>';
}

add_action('wp_ajax_filtrar_posts_por_categoria', 'filtrar_posts_por_categoria');
add_action('wp_ajax_nopriv_filtrar_posts_por_categoria', 'filtrar_posts_por_categoria');

function filtrar_posts_por_categoria() {
    if (isset($_POST['categorias_ids'])) {
                
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND'
            )
        );
        
        if ( ! empty($_POST['categorias_ids']) && is_array($_POST['categorias_ids']) ) {
            foreach ($_POST['categorias_ids'] as $cat_id) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => intval($cat_id),
                    'include_children' => false,
                );
            }
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            ob_start();
            while ($query->have_posts()) : $query->the_post();
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'horizontal');
            $post_url = get_permalink();
            $excerpt = get_the_excerpt();
            ?>
            <a href="<?php echo esc_url($post_url); ?>"  class="project-list__item">
                <?php if ($thumbnail_url) : ?>
                    <div class="post-thumbnail">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                        <span></span>
                </div>
                <?php endif; ?>
                <div class="project-list__item-description">
                    <h2 class="post-title">
                        <?php the_title(); ?>
                    </h2>
                    <p>
                    <?php
                        $tags = get_the_tags();

                        if ($tags) {
                            foreach ($tags as $tag) {
                            echo esc_html($tag->name);
                            }
                        }
                    ?>
                    </p>
                </div>
                </a>
            <?php
            endwhile ;
            wp_reset_postdata();
            echo ob_get_clean();
        else :
            echo '<p>Nenhum publicacao encontrado nessas categorias.</p>';
        endif;
        
    }

    die();

}

add_action('wp_ajax_custom_search', 'custom_search');
add_action('wp_ajax_nopriv_custom_search', 'custom_search');

function custom_search() {
    $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        's' => $search_query,
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1
    );

    $the_query = new WP_Query($args);

    ?>
    <div id="resultado-posts" class="project-list">
    <?php

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) : $the_query->the_post();
        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'horizontal');
        $post_url = get_permalink();
        $excerpt = get_the_excerpt();
        ?>
        <div class="project-list__item">
            <?php if ($thumbnail_url) : ?>
                <a href="<?php echo esc_url($post_url); ?>" class="post-thumbnail">
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                    <span></span>
                </a>
            <?php endif; ?>
            <div class="project-list__item-description">
                <h2 class="post-title"><a href="<?php echo esc_url($post_url); ?>"><?php the_title(); ?></a></h2>
                <p>
                <?php
                    $tags = get_the_tags();

                    if ($tags) {
                        foreach ($tags as $tag) {
                        echo esc_html($tag->name);
                        }
                    }
                ?>
                </p>
            </div>
        </div>
        <?php
        endwhile;
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__( 'Nenhum publicacao foi encontrado com essa busca', 'text-domain' ) . '</p>';
    }
    ?>
    </div>
    <?php

    wp_die();
}

add_action('rest_api_init', function () {
    register_rest_route('myplugin', '/check-category-projects', array(
        'methods'  => 'GET',
        'callback' => 'check_category_projects',
        'permission_callback' => '__return_true',
    ));
});


function check_category_projects(WP_REST_Request $request) {
    $category_id = intval($request->get_param('category_id'));
    
    // Verifica se a categoria ID foi fornecida
    if (!$category_id) {
        return new WP_Error('no_category_id', 'Category ID not provided', array('status' => 400));
    }
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $category_id,
                'include_children' => false,
            ),
        ),
        'fields' => 'ids',
        'posts_per_page' => 1,
    );
    
    $query = new WP_Query($args);
    return array('hasProjects' => $query->have_posts());
}





add_action( 'after_setup_theme', function() {
    add_theme_support( 'wp-block-styles' );     // estilos extras do core
    add_theme_support( 'responsive-embeds' );   // só bônus, mas é bom
});
add_action( 'after_setup_theme', function() {
    add_theme_support( 'wp-block-styles' ); // estilos extras de blocos
});



add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
function load_more_posts() {

    if ( ! defined('DOING_AJAX') || ! DOING_AJAX ) {
        wp_die();
    }

    $offset   = isset($_POST['offset']) ? max(0, intval($_POST['offset'])) : 0;
    $per_page = 12;

    $args = [
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $per_page,
        'offset'              => $offset,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    ];

    // filtros válidos
    $filters = ['tipologia','local','fase','decada','escala','premiado'];
    $tax_query = [];

    foreach ($filters as $filter) {
        if ( isset($_POST[$filter]) && $_POST[$filter] !== '' ) {
            $tax_query[] = [
                'taxonomy'         => 'category',
                'field'            => 'term_id',
                'terms'            => (int) $_POST[$filter],
                'include_children' => false,
            ];
        }
    }

    if ( ! empty($tax_query) ) {
        $args['tax_query'] = array_merge(
            ['relation' => 'AND'],
            $tax_query
        );
    }

    $query = new WP_Query($args);

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();

            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'horizontal');
            $post_url      = get_permalink();
            $excerpt       = get_the_excerpt();
            ?>

            <a href="<?php echo esc_url($post_url); ?>" class="project-list__item">
                <?php if ($thumbnail_url) : ?>
                    <div class="post-thumbnail">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                        <span></span>
                    </div>
                <?php endif; ?>

                <div class="project-list__item-description">
                    <h2 class="post-title"><?php the_title(); ?></h2>
                    <p>
                    <?php
                        $tags = get_the_tags();

                        if ($tags) {
                            foreach ($tags as $tag) {
                            echo esc_html($tag->name);
                            }
                        }
                    ?>
                    </p>
                </div>
            </a>

        <?php
        endwhile;
        wp_reset_postdata();
    endif;

    wp_die();
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');


function studio_format_date_pt($date) {

    $timestamp = strtotime($date);

    $months = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro'
    ];

    return date('d', $timestamp) . ' de ' . $months[(int) date('m', $timestamp)];
}
