<?php

session_start();



if (isset($_POST['login'])) {
  if (isset($_POST['user']) && isset($_POST['pwd'])) {

    $usuario = $_POST['user'];
    $pass = $_POST['pwd'];

    include('Resources/PHP/database.php');

    $qry = "SELECT pkIdUsuario, gPrimerNombre, gPrimerApellido, gNombreUsuario, gClienteRS, gClienteRFC, gPrivilegios FROM tbl_usuarios WHERE gNombreUsuario = ? AND gPassword = ?";

    $stmt = $db->prepare($qry) or die ('Error Login('.$db->errno.'): '.$db->error);
    $stmt->bind_param('ss',$usuario, $pass);
    $stmt->execute();
    $results = $stmt->get_result();
    $row = $results->fetch_array(MYSQLI_ASSOC);

    //var_dump($results);

    $validador = $results->num_rows;

    if ($validador == 1) {
      $_SESSION['user_info'] = $row;
      setcookie('Nombre',$row['Nombre']);
      setcookie('Apellido',$row['Apellido']);
      setcookie('Usuario',$row['NombreUsuario']);
      setcookie('idUsuario',$row['pkIdUsers']);
      setcookie('nombreUsuario', $row['NombreUsuario']);
      header('location:Ubicaciones/');

      exit();
    }
    $db->close();
  }
}

//var_dump($_POST);

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <link rel="stylesheet" href="Resources/Bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="Resources/CSS/login.css">
     <link href="https://fonts.googleapis.com/css?family=Signika" rel="stylesheet">
     <title></title>
   </head>
   <body>
     <div class="container">
       <div class="page-header clearfix">
       <img src="Resources/Images/LogoPrologII.svg" alt="Logo" class="pull-left d-inline" style="width: 200px">
       <h1 class="d-inline ml-5">Acceso ProBlog!</h1>
       </div>

       <hr>

       <?php if (isset($_POST['login'])): ?>
         <div class="center-login-window" style="margin-top: 20px">
           <div class="alert alert-danger alert-dismissible text-center well-login" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <strong>Error:</strong> Usuario y/o contraseña incorrectos.
           </div>
         </div>
       <?php endif; ?>

       <div class="center-login-window">
         <div class="login-window card w-50 well-login">
           <div class="card-block">
             <form class="form-group" action="" method="post">
               <label for="usuario">Usuario</label>
               <input type="text" class="form-control" id="user" name="user" value="" placeholder="Escribe tu nombre de usuario...">
               <br>
               <label for="contrasena">Contraseña</label>
               <input type="password" class="form-control" id="pwd" name="pwd" value="" placeholder="Escribe tu contraseña">
               <br>
               <input class="btn btn-primary" type="submit" name="login" value="Entrar">
             </form>
           </div>
         </div>
       </div>

     </div>

    <script src="Resources/JQuery/jquery-3.1.1.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="Resources/Bootstrap/js/bootstrap.min.js" charset="utf-8"></script>
   </body>
 </html>
