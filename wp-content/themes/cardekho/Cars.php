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
  'posts_per_page' => 6,
  'offset' => 0,
  'post_status' => 'publish',
  'orderby' => 'ID',
  'order' => 'ASC',
  'tax_query' =>''
];

$cars = new WP_Query($arguments);
if($cars->have_posts()) {  
  $ids = array_column($cars->posts, 'ID');
  $id_count = count($ids);
?>
  <ul class="car-tab-container cars">
    <?php
      include('template-parts/car.php');
    ?>
    <li class="load_more_main" id="load_more_main<?php echo $id_count; ?>">
      <a href='#FIXME' id="<?php echo $id_count; ?>" class="load_more" title="Load more posts">Load more</a>
</li>
  </ul>
 
<?php } ?>

<h3>Search Result</h3>
<ul class="car-tab cars"></ul>