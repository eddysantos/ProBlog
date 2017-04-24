$(document).ready(function(){
    $('#formNewUser').change(function(){
      var form = $(this);

      var nombre = form.find('#primerNombre').val() != "";
      var apellido = form.find('#primerApellido').val() != "";
      var correo = form.find('#correoElectronico').val() != "" && form.find('#correoElectronico').hasClass('form-control-success');
      var cliente = (form.find('#tipoUsuario').val() == "Cliente" && form.find('#clientRFC').val() != "" && form.find('#tipoPrivilegios').val() == "Básico") || form.find('#tipoUsuario').val() == "Interno";

      if (nombre && apellido && correo && cliente) {
        $('#add-user-modal').find('#submitUser').attr('disabled', false);
      } else {
        console.warn("Errores en el llenado de la forma");
        $('#add-user-modal').find('#submitUser').attr('disabled', true);
      }
    });

    $('#formEditUser').change(function(){
      var form = $(this);

      var nombre = form.find('#primerNombre').val() != "";
      var apellido = form.find('#primerApellido').val() != "";
      var cliente = (form.find('#tipoUsuario').val() == "Cliente" && form.find('#clientRFC').val() != "" && form.find('#tipoPrivilegios').val() == "Básico") || form.find('#tipoUsuario').val() == "Interno";

      if (nombre && apellido && cliente) {
        $('#edit-user-modal').find('#editUser').attr('disabled', false);
      } else {
        console.warn("Errores en el llenado de la forma");
        $('#edit-user-modal').find('#editUser').attr('disabled', true);
      }
    });

    $('#form-group-email #correoElectronico').blur(function(){
        var mtv = $(this).val();
        if (mtv == "") {
          return false;
        }
        $.ajax({
          method: 'POST',
          data: {mailToValidate: mtv},
          url: '../Resources/PHP/Usuarios/validateMail.php',
          beforeSend: function(){
            $('#form-group-email i').removeClass('invisible');
          },
          success: function(result){
            if (result > 0) {
              console.log(result);
              $('#form-group-email').removeClass('has-success').addClass('has-danger');
              $('#form-group-email #correoElectronico').removeClass('form-control-success').addClass('form-control-danger');
              $('#form-group-email .form-control-feedback').html('Este correo electrónico ya esta registrado!');
              $('#form-group-email i').addClass('invisible');
            } else {
              console.log(result)
              $('#form-group-email').addClass('has-success').removeClass('has-danger');
              $('#form-group-email #correoElectronico').addClass('form-control-success').removeClass('form-control-danger');
              $('#form-group-email .form-control-feedback').html('');
              $('#form-group-email i').addClass('invisible');
            }
          },
        })
    });

    $('#formNewUser #tipoUsuario').change(function(){
      var valueToCheck = $(this).val();
      if (valueToCheck == 'Interno') {
        $('#formNewUser #tipoPrivilegios').attr('disabled', false);
      } else {
        $('#formNewUser #tipoPrivilegios').attr('disabled', true);
        $('#formNewUser #tipoPrivilegios').val('Básico');
        $('#formNewUser #tipoPrivilegios').find('[value="Básico"]').attr('selected', true);
        $('#formNewUser #tipoPrivilegios').find('[value="Administrador"]').attr('selected', false);
      }
    });

    $('.form-group #clientDefault').keyup(function(){
      cltSrch = $(this).val();
      var form = $(this).closest('.form-group');
      if (cltSrch != '') {
        $.ajax({
          method: 'POST',
          url: '../Resources/PHP/Clientes/listadoClientes.php',
          data: {cltSrch: cltSrch},
          beforeSend: function(){
            form.find('#loadingClients').removeClass('invisible');
          },
          success: function(results){
            form.find('#clientListUsers').fadeIn();
            form.find('#clientListUsers').html(results);
            form.find('#loadingClients').addClass('invisible')
          },
          error: function(exception){
            console.error(exception);
            form.find('#loadingClients').addClass('invisible')
          },
        });
      } else {
        form.find('#clientListUsers').fadeOut();
      }
    });

    $('#formNewUser').mouseup(function (e)
      {
          var container = $("#clientListUsers");

          if (!container.is(e.target) // if the target of the click isn't the container...
              && container.has(e.target).length === 0) // ... nor a descendant of the container
          {
            $('#clientListUsers').fadeOut();
          }
      });

    $('.form-group').on('click','#clientListUsers  li', function(){
      razonSocial = $(this).find('#razonSocial').html();
      rfc = $(this).find('#RFC').html();
      var form = $(this).closest('.form-group');
      form.find('#clientDefault').val(razonSocial);
      form.find('#clientRFC').val(rfc).change();
      form.find('#clientListUsers').fadeOut();
    });

    $('.modal').on('hide.bs.modal', function(){
      $('#formNewUser')[0].reset();
      $('#form-group-email').removeClass('has-success').removeClass('has-danger');
      $('#form-group-email #correoElectronico').removeClass('form-control-sucess').removeClass('form-control-danger');
      $('#form-group-email .form-control-feedback').html('');
      $('#form-group-email i').addClass('invisible');
      $('#submitUser').attr('disabled', true);
      $('.form-group #clientListUsers').fadeOut();
    });

    $('#submitUser').click(function(){
        $('#formNewUser').find('#tipoPrivilegios').attr('disabled', false);
        var newUserData = $('#formNewUser').serialize();
        $('#formNewUser').find('#tipoPrivilegios').attr('disabled', true);
        //console.log(newUserData);
        $.ajax({
            method: 'GET',
            url: '../Resources/PHP/Usuarios/addUser.php',
            data: newUserData,
            success: function(result){
              console.log("Usuario Agregado - " + result);
              location.reload();
            },
            error: function(exception){
              console.log(exception);
            }
        });
      });

    $('[name="cancelar"]').click(function(){
      //$('#formNewUser')[0].reset();
      $(this).closest('.modal').modal('hide');
    });


    $('[name="editUser"]').click(function(){
      idUsuario = $(this).attr('userid');

      $.ajax({
        method: 'POST',
        url: '../Resources/PHP/Usuarios/fetchSingleUser.php',
        data: {idUsuario: idUsuario},
        success: function(result){
          datosU = jQuery.parseJSON(result);
          console.log(datosU);
          $('#formEditUser').find('#primerNombre').val(datosU.gPrimerNombre);
          $('#formEditUser').find('#primerApellido').val(datosU.gPrimerApellido);
          $('#formEditUser').find('#correoElectronico').val(datosU.gNombreUsuario);
          $('#formEditUser').find('#clientDefault').val(datosU.gClienteRS);
          $('#formEditUser').find('#clientRFC').val(datosU.gClienteRFC);
          $('#formEditUser').find('#tipoUsuario [value="' + datosU.gTipoUsuario + '"]').attr('selected', true);
          $('#formEditUser').find('#tipoUsuario [value="' + datosU.gPrivilegios + '"]').attr('selected', true);
          $('#formEditUser').find('#idUsuario').val(datosU.pkIdUsuario);
          $('#edit-user-modal').modal('show');
        }
      });
    });

    $('#editUser').click(function(){
        $('#formEditUser #correoElectronico').attr('disabled', false);
        $('#formEditUser #clientRFC').attr('disabled', false);
        $('#formEditUser #tipoPrivilegios').attr('disabled', false);

        datos = $('#formEditUser').serialize();

        $.ajax({
          method: 'GET',
          url: '../Resources/PHP/Usuarios/editUser.php',
          data: datos,
          success: function(result){
            console.log(result);
            $('#edit-user-modal').modal('hide');
            location.reload();
          },
          error: function(exception){
            console.warn(exception);
          }
        });

      });

    $('#returnButton').click(function(){
      location.replace('index.php')
    });
});
