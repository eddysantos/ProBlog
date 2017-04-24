$(document).ready(function(){

  $('#numRefHeader').click(function(){
    if ($(this).html() != "Seleccione un movimiento de tráfico") {
      $('#thread-details-modal').modal('show');
    }
  });

  $('#threadDetailsOptions li a').click(function(){
    moveContainer = $(this).attr('direction');
    $('#threadDetailsOptions li a').toggleClass('active');

    if (moveContainer == "left") {
      $(".movable-container").animate({marginLeft: -1000}, 500);
    } else {
      $(".movable-container").animate({marginLeft: 0}, 500);
    }
  })

  $('#editReference').click(function(){
    $(this).toggleClass('fa-floppy-o fa-pencil-square-o');
    $('#refThreadDetails').toggle();
    $('#newReferenceValue').toggle();
    if ($('#newReferenceValue:hidden').length == 0) {
      $('#newReferenceValue').css('display', 'inline')
    } else {
      var origRef = $('#numRefHeader').html();
      var newRef = $('#newReferenceValue').val();
      var threadId = $('#refThreadDetails').attr('idThread');

      $.ajax({
        method: 'POST',
        url: '../Resources/PHP/Registros/editThread.php',
        data: {
          action: "refId",
          newRef: newRef,
          threadId: threadId
        },
        success: function(result){
          console.log(result);

          dataReturn = jQuery.parseJSON(result);
          if (dataReturn.systemCode == 1) {
            $('#newReferenceValue').hide().val("");
            $('div [reftraf="'+origRef+'"]').attr('reftraf', newRef);
            $('#numRefHeader').html(newRef);
            $('#refThreadDetails').html(newRef);
          } else {
            console.warn("There was an error processing the update. System returned:" + dataReturn.systemMessage);
          }
        },
        error: function(exception){
          console.warn(exception);
        }
      });
    }

  });

  $('#thread-details-modal').on('shown.bs.modal', function(){
    var ref = $('#thread-details-modal').find('#refThreadDetails').attr('idThread');
    $.ajax({
      method: 'POST',
      url: '../Resources/PHP/Registros/fetchUploadedFiles.php',
      data:{
        numReferencia: ref
      },
      success: function(result){
        $('#fileBody').html(result);
      },
      error: function(exception){
        console.error(exception);
      }
    });
  });



});
