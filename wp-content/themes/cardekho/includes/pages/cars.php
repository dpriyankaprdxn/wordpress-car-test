<?php

function get_car_post($cars){
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

function cars_filter_arguments($id_count,$category,$tax_query=0,$posts_per_page=6) {
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
    'order' => 'ASC',
    'tax_query' => $tax_query_array
  ];
}

// search car by title
function search_cars() 
{
  $search_content = $_POST['search'];

  $arguments = [
    'post_type' => 'Car',
    'post_status' => 'publish',    
    's' => $search_content,
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

?>