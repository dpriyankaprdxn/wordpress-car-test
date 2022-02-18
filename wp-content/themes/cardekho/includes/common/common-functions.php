<?php
// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// Enabling Support for Featured Image 
add_theme_support( 'post-thumbnails' );

// css file enquque
function add_theme_scripts() {
  wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.1', 'all');
}

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

// adding js files
function scripts() {
	wp_enqueue_script('jquery');
	wp_register_script( 'axioned_ajax_script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery') );
	wp_localize_script( 'axioned_ajax_script', 'axioned_ajax_params', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	) );

 	wp_enqueue_script( 'axioned_ajax_script' );
}
 
add_action( 'wp_enqueue_scripts','scripts' );

// custom post type
function post_type($post_type_name,$dashicon_icon ='dashicons-admin-post') 
{
  $labels = array(
  'name'                => _x( $post_type_name. 's','Post Type General Name'),
  'singular_name'       => _x( $post_type_name,'Post Type Singular Name'),
  'menu_name'           => __( $post_type_name.'s' ),
  'parent_item_colon'   => __( 'Parent '.$post_type_name ),
    'all_items'           => __( 'All '.$post_type_name.'s' ),
    'view_item'           => __( 'View '.$post_type_name ),
    'add_new_item'        => __( 'Add New '.$post_type_name ),
    'add_new'             => __( 'Add New '.$post_type_name ),
    'edit_item'           => __( 'Edit '.$post_type_name ),
    'update_item'         => __( 'Update '.$post_type_name ),
    'search_items'        => __( 'Search '.$post_type_name ),
  );

  $arguments = array(
    'label'               => __( $post_type_name ),
    'description'         => __( $post_type_name.' information and reviews' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor','thumbnail','revisions' ),
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_icon'           => $dashicon_icon,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'post',
  );

  register_post_type( $post_type_name, $arguments );
}

add_action( 'init', 'post_type', 0 );
?>