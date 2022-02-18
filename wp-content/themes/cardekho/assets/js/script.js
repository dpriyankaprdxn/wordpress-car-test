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

  // search titles in car posts
  $('.search-button').on('click', function() {
    search_value = $('#search').val();

    $.ajax({
      type: 'POST',
      url: car_ajax_params.ajaxurl,
      data: {
        action: 'search_cars',
        search: search_value,
      },
      success: function(res) {
        $('.car-tab').html(res);
      }
    })
  });
});