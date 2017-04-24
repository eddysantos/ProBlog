<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');

$idUsuario = $_POST['idUsuario'];
$db->set_charset('UTF-8');
$idUsuario = $db->real_escape_string($idUsuario);

$qry = "SELECT
  gPrimerNombre,
  gPrimerApellido,
  gTipoUsuario,
  gPrivilegios,
  gClienteRS,
  gClienteRFC,
  gNombreUsuario,
  pkIdUsuario

  FROM

  tbl_usuarios

  WHERE

  pkIdUsuario = $idUsuario
";

$stmt = $db->query($qry) or die("Error extrayendo usuario ($db->errno): $db->error");
$user = array();

while ($row = $stmt->fetch_assoc()) {
    $user=$row;
}

$user = json_encode($user);

echo $user;

 ?>
