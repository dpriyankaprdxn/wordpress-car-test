jQuery(function($) {
    $('.all-car').on('change', function() {
    let car_select_value = $(this).val();

    $.ajax({
      type: 'POST',
      url: car_ajax_params.ajaxurl,
      data: {
        action: 'filter_cars',
        category: car_select_value,
      },
      success: function(res) {
        $('.car-tab-container').html(res);
      }
    })
  });

});