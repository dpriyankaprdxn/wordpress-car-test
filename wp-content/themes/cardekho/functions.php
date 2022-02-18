<?php

// common wordpress functions
require get_template_directory() . '/includes/common/common-functions.php';

// car post type
post_type('Car','dashicons-car');

// custom taxonomy for car
create_custom_taxonomy('car','color');
?>