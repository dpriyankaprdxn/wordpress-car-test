<?php

// filter car
function filter_cars() 
{
  $category = $_POST['category'];

  if ($category == 'all') {
    $arguments = [
    'post_type' => 'Car',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    ];
    
  } else {
    $arguments = [
      'post_type' => 'Car',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'tax_query' => array(
        array(
          'taxonomy' => 'color_category',
          'field' => 'slug',
          'terms' => $category,
        )
      ),
    ];
  }
      
  $cars = new WP_Query($arguments);
  $response = '';

  if($cars->have_posts()) {
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
  } else {
    $response = 'No data available';
  }

  echo $response;
  exit;
}

add_action('wp_ajax_filter_cars', 'filter_cars');
add_action('wp_ajax_nopriv_filter_cars', 'filter_cars');
?>