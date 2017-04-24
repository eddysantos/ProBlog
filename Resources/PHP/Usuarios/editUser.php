<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');


$primerNombre = $_GET['primerNombre'];
$primerApellido = $_GET['primerApellido'];
$razonCliente = $_GET['clientDefault'];
$rfcCliente = $_GET['clientRFC'];
$tipoUsuario = $_GET['tipoUsuario'];
$tipoPrivilegios = $_GET['tipoPrivilegios'];
$idUsuario = $_GET['idUsuario'];

$db->set_charset('UTF-8');
$primerNombre = "'" . $db->real_escape_string($primerNombre) . "'";
$primerApellido = "'" . $db->real_escape_string($primerApellido) . "'";
$razonCliente = "'" . $db->real_escape_string($razonCliente) . "'";
$rfcCliente = "'" . $db->real_escape_string($rfcCliente) . "'";
$tipoUsuario = "'" . $db->real_escape_string($tipoUsuario) . "'";
$tipoPrivilegios = "'" . $db->real_escape_string($tipoPrivilegios) . "'";
$idUsuario = "'" . $db->real_escape_string($idUsuario) . "'";


$qry = "CALL editUser($primerNombre, $primerApellido, $razonCliente, $rfcCliente, $tipoUsuario, $tipoPrivilegios, $idUsuario)";
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");

echo $db->affected_rows;



$db->close(); ?>
