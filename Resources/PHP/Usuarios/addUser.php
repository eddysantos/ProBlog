<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');


$primerNombre = $_GET['primerNombre'];
$primerApellido = $_GET['primerApellido'];
$correoElectronico = $_GET['correoElectronico'];
$razonCliente = $_GET['clientDefault'];
$rfcCliente = $_GET['clientRFC'];
$tipoUsuario = $_GET['tipoUsuario'];
$tipoPrivilegios = $_GET['tipoPrivilegios'];
$pwd = substr(md5(rand()), 0, 7);

$db->set_charset('UTF-8');
$primerNombre = "'" . $db->real_escape_string($primerNombre) . "'";
$primerApellido = "'" . $db->real_escape_string($primerApellido) . "'";
$correoElectronico = "'" . $db->real_escape_string($correoElectronico) . "'";
$razonCliente = "'" . $db->real_escape_string($razonCliente) . "'";
$rfcCliente = "'" . $db->real_escape_string($rfcCliente) . "'";
$tipoUsuario = "'" . $db->real_escape_string($tipoUsuario) . "'";
$tipoPrivilegios = "'" . $db->real_escape_string($tipoPrivilegios) . "'";
$pwd = "'" . $db->real_escape_string($pwd) . "'";


$qry = "CALL addUser($primerNombre, $primerApellido, $razonCliente, $pwd, $tipoUsuario, $tipoPrivilegios, $rfcCliente, $correoElectronico)";
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");

echo $db->affected_rows;



$db->close(); ?>
