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
    </div>
  </section>

<?php } 

$arguments= [
  'post_type' => 'Car',
  'posts_per_page' => -1,
  'post_status' => 'publish'
];

$cars = new WP_Query($arguments);
if($cars->have_posts()) {  ?>
  <ul class="car-tab-container cars">
    <?php
      include('template-parts/car.php');
    ?>
  </ul>
<?php } ?>