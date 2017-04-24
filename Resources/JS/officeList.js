$(document).ready(function(){

  $('.office-select').on('click', function(){
    if ($('.office-list').css('display') == 'none') {
      $('.office-list').fadeIn();
    }
  });

  $(document).mouseup(function (e){
    var container = $(".office-list");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) {  // ... nor a descendant of the container
      $('.office-list').fadeOut();
    }
  });

  $(document).on('click', '.office-list li', function(){
    oficina = $(this).attr('oficina');
    claveAduana = $(this).attr('claveAduana');
    $('#selectorOficina').attr('claveAduana', claveAduana);
    $('#selectorOficina').html(oficina);
    $('.office-list').fadeOut();
    $('#selectorOficina').change();
  });
});
