$(document).ready(function(){

  $('#clientLabel').on('click', function(){
      $(this).attr('hidden', true);
      $('#changeClientBox').attr('hidden', false);
      //$(this).fadeOut();
      //$('#changeClientBox').fadeIn();
      $('#changeClientBox').find('input[name="clientName"]').focus();
      $('#clientList').hide();

  });


  $(document).on('click','#changeClientBox #clientList  li', function(){
    razonSocial = $(this).find('#razonSocial').html();
    rfc = $(this).find('#RFC').html();
    $('#clientLabelNombre').html(razonSocial);
    $('#clientLabelNombre').attr('rfc', rfc);
    $('#changeClientBox').find('input[name="clientName"]').val("");
    $('#changeClientBox').attr('hidden', true);
    $('#clientLabel').attr('hidden', false);
    $('#clientLabelNombre').change();
  });

  /*$('#changeClientBox').find('input[name="clientName"]').blur(function(){
    $(this).val("");
    $('#changeClientBox').attr('hidden', true);
    $('#clientLabel').attr('hidden', false);
    //$('#changeClientBox').fadeOut();
    //$('#clientLabel').fadeIn();
  });*/

  $(document).mouseup(function (e)
{
    var container = $("#clientList");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
      $('#changeClientBox').find('input[name="clientName"]').val("");
      $('#changeClientBox').attr('hidden', true);
      $('#clientLabel').attr('hidden', false);
    }
});


  $('#changeClientBox').find('[name="clientName"]').keyup(function(){
    cltSrch = $(this).val();
    if (cltSrch != '') {
      $.ajax({
        method: 'POST',
        url: '../Resources/PHP/Clientes/listadoClientes.php',
        data: {cltSrch: cltSrch},
        success: function(results){
          $('#clientList').fadeIn();
          $('#clientList').html(results);
        },
        error: function(exception){
          console.error(exception);
        },
      });
    } else {
      $('#clientList').fadeOut();
    }
  });

});
