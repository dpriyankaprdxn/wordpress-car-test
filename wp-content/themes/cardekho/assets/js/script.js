jQuery(function($) {
    $('.all-car').on('change', function() {
    let car_select_value = $(this).val();
    let id_count = 0;
    $('.load_more_main').remove();

    $.ajax({
      type: 'POST',
      url: car_ajax_params.ajaxurl,
      data: {
        action: 'filter_load_more_cars',
        id_count: id_count,
        selected_category: car_select_value,
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

  //load cars
  $(document).on('click','.load_more',function(){
    let selected_category = $('.all-car').val();
    console.log(selected_category);
    let id_count = $(this).attr('id');

    $('.load_more').hide();
    $.ajax({
      type:'POST',
      url: car_ajax_params.ajaxurl,
      data: {
        action: 'filter_load_more_cars',
        id_count: id_count,
        selected_category: selected_category,
      },
      success:function(html){
        $('#load_more_main'+id_count).remove();
        $('.car-tab-container').append(html);
      }
  });
});
});