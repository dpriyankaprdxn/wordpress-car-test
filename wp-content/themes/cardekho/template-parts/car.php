<?php
while ( $cars->have_posts() ) {
	$cars->the_post(); 
  $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', TRUE);       
?>
	<li>
		<a href='<?php echo get_permalink( $cars->ID ) ?>' class='img-zoom' title='<?php echo $cars->post_title ?>'>
		  <img src="<?php echo the_post_thumbnail_url(); ?>" alt="<?php echo $image_alt; ?>">
    </a>
		<h3><?php echo get_the_title(); ?></h3>
    <?php if(get_field('excerpt', $cars->ID)) { ?>
		  <p><?php echo get_field('excerpt', $cars->ID); ?></p>
    <?php } ?>
	</li>
<?php } ?>