jQuery(function($) {
  $('.search_result').hide();

  //filter category
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
    let search_value = $('#search').val();
    let selected_category = $('.all-car').val();
    
    $.ajax({
      type: 'POST',
      url: car_ajax_params.ajaxurl,
      data: {
        action: 'search_cars',
        search: search_value,
        selected_category: selected_category
      },
      success: function(res) {
        $('.car-tabs').hide();
        $('.search_result').show();
        $('.car-tab').html(res);
      }
    })
  });

  //load cars
  $(document).on('click','.load_more',function(){
    let selected_category = $('.all-car').val();
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

  $(document).on('click','.all-car',function(){
    let search_value = $('#search').val();

    if (search_value !== '') {
      $('.search-button').click();
    } else {
      $('.car-tabs').show();
      $('.search_result').hide();
    }
  });

});