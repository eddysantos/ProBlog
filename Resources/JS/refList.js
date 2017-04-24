$(document).ready(function(){

  function callRefs(){
    tOperacion = $('#selectTOperacion').find('.active').attr('tipooperacion');
    tAduana = $('#selectorOficina').attr('claveaduana');
    c_RFC = $('#clientLabelNombre').attr('rfc');
    id_usuario = $('#pkIdUsuario').val();

    if (tAduana == "todas") {
      tAduana = "";
    }
    if (
      typeof tOperacion != undefined &&
      //tAduana != '' &&
      c_RFC != ''
    ) {
      $.ajax({
        method: 'POST',
        url: '../Resources/PHP/Registros/fetchThreads.php',
        data: {c_RFC: c_RFC, cveAduana: tAduana, tOperacion: tOperacion, pkIdUsuario: id_usuario},
        beforeSend: function(){
          $('#reference-box').html('');
          $('#loading-spinner-refs').removeClass('invisible');
        },
        success: function(result){
          $('#reference-box').html(result);
          $('#loading-spinner-refs').addClass('invisible');
          $('.reference-container').click(function(){
            idReferencia = $(this).attr('reftraf');
            idMensaje = $(this).attr('id');
            connectSocket(idMensaje, idReferencia);

          });
        },
        error: function(exception){
          console.error(exception);
          $('#loading-spinner-refs').addClass('invisible');
        }
      });
    } else {
      $('#reference-box').html('');
      console.warn('Faltan parametros para poder hacer la búsqueda de referencias activas!');
    }
  }

  /*function callRefs(){
    tOperacion = $('#selectTOperacion').find('.active').attr('tipooperacion');
    tAduana = $('#selectorOficina').attr('claveaduana');
    c_RFC = $('#clientLabelNombre').attr('rfc');
    id_usuario = $('#pkIdUsuario').val();
    if (
      typeof tOperacion != undefined &&
      tAduana != '' &&
      c_RFC != ''
    ) {
      $.ajax({
        method: 'POST',
        url: '../Resources/PHP/Referencias/refList.php',
        data: {c_RFC: c_RFC, cveAduana: tAduana, tOperacion: tOperacion, pkIdUsuario: id_usuario},
        beforeSend: function(){
          $('#reference-box').html('');
          $('#loading-spinner-refs').removeClass('invisible');
        },
        success: function(result){
          $('#reference-box').html(result);
          $('#loading-spinner-refs').addClass('invisible');
          $('.reference-container').click(function(){
            idReferencia = $(this).attr('id');
            connectSocket(idReferencia);

          });
        },
        error: function(exception){
          console.error(exception);
          $('#loading-spinner-refs').addClass('invisible');
        }
      });
    } else {
      $('#reference-box').html('');
      console.warn('Faltan parametros para poder hacer la búsqueda de referencias activas!');
    }
  }*/

  $(document).on('click', '#selectTOperacion li', function(){
      $('#selectTOperacion').find('.active').removeClass('active');
      $(this).find('a').addClass('active');
      callRefs();
  });

  $('#selectorOficina, #clientLabelNombre').change(function(){
    callRefs();
  });

});
