

  $('#newMessage').on('submit', function(){
    var mensaje = $(this).find('input[name="message"]').val();
    var idReferencia = $(this).find('input[name="referencia"]').val();
    var idUsuario = $(this).find('input[name="idUsuario"]').val();
    if (mensaje == "" || idReferencia == "") {
      return false;
    }
    /*$(this).find('input[name="message"]').val('');
    $('#messageBoard').prepend('<div><p class="message-own">'+mensaje+'</p></div>')
    conn.send(mensaje);*/
    $.ajax({
      method: 'POST',
      data:{referencia: idReferencia, content: mensaje, idUsuario: idUsuario},
      url: '../Resources/PHP/Registros/submitEntry.php',
      beforeSend: function(){
        $('#enviarMensajesValor').html('');
        $('#enviarMensajesValor').parent().attr('disabled', true);
        $('#enviarMensajesValor').addClass('fa').addClass('fa-spin').addClass('fa-spinner');
      },
      success: function(result){
        //console.log(result);
        $('#newMessage').find('input[name="message"]').val('');
        $('#enviarMensajesValor').removeClass('fa').removeClass('fa-spin').removeClass('fa-spinner');
        $('#enviarMensajesValor').parent().attr('disabled', false);
        $('#enviarMensajesValor').html('Enviar');
      },
      error: function(exception){
        console.log(exception);
        $('#enviarMensajesValor').removeClass('fa').removeClass('fa-spin').removeClass('fa-spinner');
        $('#enviarMensajesValor').parent().attr('disabled', false);
        $('#enviarMensajesValor').html('Enviar');
      },
    });

    $(document).ready(function(){
         $('#messageBoard').scroll(function(){
           $('#messageBoard').find('div').css('transform', 'translate3d(0,' + $(this).scrollTop()*-1 + 'px, 0)');
        }).scroll();
    });

    return false;
  });

  //$('.reference-container').on('click', function(){
  function connectSocket(idMensaje, idReferencia){
      //idReferencia = $(this).attr('id');
      $('#modifyReference').val(idMensaje);
      $('#fileThreadId').val(idMensaje);
      $('#numRefHeader').html(idReferencia);
      $('#refThreadDetails').html(idReferencia).attr('idThread', idMensaje);
      $('#loading-spinner').toggleClass('invisible');
      $('aside .reference-container').removeClass('active');
      $(this).addClass('active');
      console.log('Creating connection with server on topic: ' + idMensaje + '..');

  //Revisamos si hay alguna subscipción pre-existente, y si es así la cerramos.
      if (typeof conn == 'undefined') {
        console.log("Conn is undefined...");
        /*conn = new autobahn.Connection({
          url: 'wss://plt.prolog-mex.com/wss2/'
        });*/
      } else {
        console.log(conn);
        conn.close(); // Cerramos la conexión que ya existía, para evitar duplicar conexiones.
      }



      /*conn.onopen = function(session){
            function eventoReferencia(topic, data){
              console.log(data);
            }

            session.subscribe(idMensaje, eventoReferencia).then(
              function (subscription){
                console.log('Subscription succeded!');
              },
              function (error){
                console.log('Subscription was not possible!');
              }
            );
      }

      conn.open();*/


      conn = new ab.Session('wss://plt.prolog-mex.com/wss2/',
      //conn = new autobahn.Session('wss://plt.prolog-mex.com/wss2/',

          function() {
              conn.subscribe(idMensaje, function(topic, data) {
                  boardUser = $('#messageBoard').attr('usuario');
                  if (boardUser == data.idUsuario) {
                    clase = 'message-own';
                  } else {
                    clase = 'message-nonown';
                  }

                  $('#messageBoard').prepend('<p class="'+ clase +'"><span><b>'+data.usuario+' | '+data.when+'</b></span><br>'+data.article+'</p>')
                  usuario_id = $('#pkIdUsuario').val();
                  console.log(usuario_id + " - " + data.idMensaje);
                  $.ajax({
                    method: 'POST',
                    url: '../Resources/PHP/Registros/markRead.php',
                    data: {pkIdUsuario: usuario_id, pkIdMensaje: data.idMensaje},
                    success: function(result){
                      if (result == 0) {
                        console.error("El mensaje no se pudo marcar como leído..");
                      } else {
                        console.log("Mensaje marcado como leído!");
                      }
                    },
                    error: function(exception){
                      console.warn(exception);
                    }
                  });
              });
          },
          function() {
              console.warn('WebSocket connection closed');
          },
          {'skipSubprotocolCheck': true}
      );
      console.log(conn);

      boardUser = $('#messageBoard').attr('usuario');
      $.ajax({
        method: 'POST',
        data: {numReferencia: idMensaje, idUsuario: boardUser},
        url: '../Resources/PHP/Registros/fetchEntries.php',
        success: function(result){
          $('#messageBoard').html(result);
          $('#'+idMensaje).find('#mnl').html('');
          $('#loading-spinner').toggleClass('invisible');
        },
        error: function(exception){
          console.log('Failed!');
          console.log(exception);
          $('#loading-spinner').toggleClass('invisible');
        }
      });

  }

  $('.modal').on('hide.bs.modal', function(){
    $(this).find('form')[0].reset();
  });

  $('#logout').on('click', function(){
    var salir = confirm("¿Desea cerrar sesión?");
    if (salir == true) {
      console.log('Cerrando sesión..');
      location.replace('/problog/cerrarSesion.php');
      console.log('Cerrar sesion!');
      return false;
    }
  });


  $(document).on('click','#formNewThread #clientList  li', function(){
    razonSocial = $(this).find('#razonSocial').html();
    rfc = $(this).find('#RFC').html();
    $('#clienteHilo').val(razonSocial);
    $('#clienteRFCHilo').val(rfc);
    $('#formNewThread #clientList').fadeOut();
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
    var container = $('#formNewThread #clientList');

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
      $('#formNewThread #clientList').fadeOut();
    }
});


  $('#formNewThread').find('[name="clienteHilo"]').keyup(function(){
    cltSrch = $(this).val();
    if (cltSrch != '') {
      $.ajax({
        method: 'POST',
        url: '../Resources/PHP/Clientes/listadoClientes.php',
        data: {cltSrch: cltSrch},
        success: function(results){
          $('#formNewThread #clientList').fadeIn();
          $('#formNewThread #clientList').html(results);
        },
        error: function(exception){
          console.error(exception);
        },
      });
    } else {
      $('#formNewThread #clientList').fadeOut();
    }
  });

  $('#add-thread-modal').find('#cancelar').click(function(){
    $('#add-thread-modal').modal('hide');
    $('#formNewThread')[0].reset();
  });

  $('#add-thread-modal').find('#submit').click(function(){
    datosHilo = $('#formNewThread').serialize();

    $.ajax({
      method: 'GET',
      url: '../Resources/PHP/Registros/newThread.php',
      data: datosHilo,
      success: function(result){
        if (result > 0) {
          console.log("Hilo creado con éxito");
          $('#add-thread-modal').modal('hide');
          $('#formNewThread')[0].reset();
        } else {
          console.log(result);
          alert('Hubo un error al crear el hilo..');
        }
      },
      error: function(exception){
        console.warn(exception);
      }
    });
  });
