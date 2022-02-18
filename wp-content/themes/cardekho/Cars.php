<?php
  /*
    Template Name: Car Template
  */
?>

<?php 
get_header(); 

$categories = get_terms( 
  array(
    'taxonomy' => 'color_category',
    'hide_empty' => false,
  )
);

$categories_name = array_column($categories, 'name');

if (count($categories_name) > 0 ) {
?>
  <section class='cars-section'>
    <div class='wrapper'>
      <select class=all-car>
        <option value='all' selected>All</option>
        <?php foreach ($categories_name as $c) {  ?>
          <option value ='<?php echo $c; ?>' ><?php echo $c; ?></option>
        <?php } ?>
      </select>
      <aside class='search'>
        <form method='POST' action="#FIXME">
          <input type="text" placeholder="Search.." id='search' name="search">
          <button class='search-button' type="button">Search</button>
        </form>
      </aside>
      </div>
  </section>
<?php } 

$arguments= [
  'post_type' => 'Car',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'orderby' => 'ID',
  'order' => 'ASC'
];

$cars = new WP_Query($arguments);
if($cars->have_posts()) {  
  $ids = array_column($cars->posts, 'ID');
  $last_id = end($ids);
?>
  <ul class="car-tab-container cars">
    <?php
      include('template-parts/car.php');
    ?>
  </ul>
<?php } ?>
<?php if ($cars->max_num_pages >1) { ?>
  <div class="button_load_more ">
    <a  class="loadmore "href="#FIXME">Load More</a>
  </div>
<?php  }?>

<h3>Search Result</h3>
<ul class="car-tab cars"></ul>