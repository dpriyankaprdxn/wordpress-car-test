<?php get_header(); ?>
<main>
  <div class="wrapper">
    <section class='car-details'>
      <?php 
      $terms = get_the_terms( get_the_ID(), 'color_category' );

      if ( !empty( $terms ) ) {
      ?>
        <ul class='categories'>
          <?php foreach ($terms as $t)  ?>
            <li><?php echo $t->name; ?></li>
        </ul>
      <?php
      }
      $excerpt = get_field('excerpt');
      if (has_post_thumbnail()) {
      ?>
      <div class='banner'>
        <img src='<?php echo get_the_post_thumbnail_url(); ?>' alt=<?php echo get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>>
      </div>
      <?php } ?>
      <h3><?php  echo get_the_title(); ?></h3>
      <?php if (get_field('excerpt')) {   ?>
        <p class='excerpt'><?php  echo get_field('excerpt'); ?></p>
      <?php } ?>
      <?php if (get_field('discription')) {   ?>
        <p class='discription'><?php  echo get_field('discription'); ?></p>
      <?php } ?>
      </section>
  </div>
</main>
<?php get_footer(); ?>

