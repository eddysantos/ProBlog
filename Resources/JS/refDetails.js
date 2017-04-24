$(document).ready(function(){

  $('#refDetalleEntradas div').click(function(){
    var numEntrada = $(this).html();

    $('#headerValue').html(numEntrada).css('margin-left', '70%');
    $('#refDetalleEntradas').slideUp(500);
    $('#refDetalleFacturas').slideDown(500);
    $('#header2Detalles .fa-chevron-left').fadeIn();



  });

  $('.entradasChevron').click(function(){
    $('#refDetalleFacturas').slideUp(500); 
    $('#refDetalleEntradas').slideDown(500);

    $('#header2Detalles .fa-chevron-left').fadeOut();
    $('#headerValue').html('Entradas').css('margin-left', 0);
  });

  $('[btn-type="collapse"]').click(function(){
    var action = $(this).find('i').hasClass('fa-chevron-up');
    var target = $(this).attr('data-target');
    if (action) {
      $(target).slideUp();
    } else {
      $(target).slideDown();
    }
    $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
  });
});
