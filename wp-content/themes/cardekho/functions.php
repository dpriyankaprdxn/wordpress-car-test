<?php

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// Enabling Support for Featured Image 
add_theme_support( 'post-thumbnails' );

// css file enquque
function add_theme_scripts() 
{
  wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.1', 'all');
}

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

// adding js files
function scripts() 
{
	wp_enqueue_script('jquery');
	wp_register_script( 'car_ajax_script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery') );
	wp_localize_script( 'car_ajax_script', 'car_ajax_params', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	) );

 	wp_enqueue_script( 'car_ajax_script' );
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

// custom taxonomy
function create_custom_taxonomy($post_name , $taxonomy_name ='') 
{
  if ($taxonomy_name == '')
    $taxonomy_name = $post_name;

  register_taxonomy(
    $taxonomy_name.'_category',
    $post_name,
    array(
      'label' => __( ucfirst($taxonomy_name).' Category' ),
      'rewrite' => array( 'slug' => $taxonomy_name.'-category' ),
      'hierarchical' => true,
      'show_in_rest' => true
    )
  );
}

add_action( 'init', 'create_custom_taxonomy' );

// for displaying car post 
function get_car_post($cars)
{
  while ( $cars->have_posts() ) {
    $cars->the_post(); 
    $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', TRUE);       
    ?>
    <li>
    <img src="<?php echo the_post_thumbnail_url(); ?>" alt="<?php echo $image_alt; ?>">
    <h3><?php echo get_the_title(); ?></h3>
    <?php if(get_field('excerpt', $cars->ID)) { ?>
    <p><?php echo get_field('excerpt', $cars->ID); ?></p>
    <?php } ?>
    </li>
  <?php }
}
 
// meta query arguments for filter load more 
function cars_filter_arguments($id_count,$category,$tax_query=0,$posts_per_page=6) 
{
  $tax_query_array = '';
  if ($tax_query) {
    $tax_query_array = array(
      array(
      'taxonomy' => 'color_category',
      'field' => 'slug',
      'terms' => $category,
      )
    );
  } 

  return $arguments = [
    'post_type' => 'Car',
    'posts_per_page' => $posts_per_page,
    'offset' => $id_count ,
    'post_status' => 'publish',
    'orderby' => 'ID',
    'order' => 'desc',
    'tax_query' => $tax_query_array
  ];
}
  
// search car by title
function search_cars() 
{
  $search_content = $_POST['search'];
  $category = $_POST['selected_category'];

  $tax_query_array = '';
  if ($category != 'all') {
    $tax_query_array = array(
      array(
      'taxonomy' => 'color_category',
      'field' => 'slug',
      'terms' => $category,
      )
    );
  } 

  $arguments = [
    'post_type' => 'Car',
    'post_status' => 'publish',    
    's' => $search_content,
    'tax_query' => $tax_query_array
  ];

  
      
  $cars = new WP_Query($arguments);
  $response = '';

  if($cars->have_posts()) {
    get_car_post($cars);
  } 

  echo $response;
  exit;
}

add_action('wp_ajax_search_cars', 'search_cars');
add_action('wp_ajax_nopriv_search_cars', 'search_cars');


// filter with load more
function filter_load_more_cars() 
{
  $category = $_POST['selected_category'];
  $id_count =  $_POST['id_count'];

  if ($category == 'all') {
    $arguments = cars_filter_arguments($id_count,$category);
    $total_count_argument = cars_filter_arguments($id_count,$category,0,-1);
  } else {
    $arguments = cars_filter_arguments($id_count,$category,1);
    $total_count_argument = cars_filter_arguments($id_count,$category,1,-1);
  }
    
  $cars = new WP_Query($arguments);
  $total_cars = new WP_Query($total_count_argument);

  $response = '';

  if($cars->have_posts()) {
    $ids = array_column($cars->posts, 'ID');
    $new_count_ids = $id_count + count($ids);
    get_car_post($cars);
    $total_ids = array_column($total_cars->posts, 'ID');
    $total_cars_count = count($total_ids);

    if ($total_cars_count > $new_count_ids) {
    ?>
    <li class="load_more_main" id="load_more_main<?php echo $new_count_ids; ?>">
      <a href='#FIXME' id="<?php echo $new_count_ids; ?>" class="load_more" title="Load more posts">Load more</a>
    </li>
  <?php }
  } else {
    $response = '';
  }

  echo $response;
  exit;
}

add_action('wp_ajax_filter_load_more_cars', 'filter_load_more_cars');
add_action('wp_ajax_nopriv_filter_load_more_cars', 'filter_load_more_cars');

// car post type
post_type('Car','dashicons-car');

// custom taxonomy for car
create_custom_taxonomy('car','color');

?>