<?php
session_start();
session_destroy();
/*unset($_COOKIE['Nombre']);
unset($_COOKIE['Apellido']);
unset($_COOKIE['Usuario']);
unset($_COOKIE['idUsuario']);*/
foreach ($_COOKIE as $key => $value) {
  setcookie($key, "", time()-3600);
}
//var_dump($_COOKIE);
header('location:index.php');
 ?>
