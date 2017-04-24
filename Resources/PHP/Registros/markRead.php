<?php

include(dirname(__DIR__) . '/globalDatabase.php');

date_default_timezone_set('America/Monterrey');
header('Content-type: text/plain; charset=utf-8');

$pkIdUsuario = $_POST['pkIdUsuario'];
$pkIdMensaje = $_POST['pkIdMensaje'];


$db->set_charset('UTF-8');
//$pkIdUsuario = "'" . $db->real_escape_string($pkIdUsuario) . "'";
//$pkIdMensaje = "'" . $db->real_escape_string($pkIdMensaje) . "'";
$pkIdUsuario = $db->real_escape_string($pkIdUsuario);
$pkIdMensaje = $db->real_escape_string($pkIdMensaje);

$qry = "CALL problog.markAsRead($pkIdMensaje, $pkIdUsuario)";
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");

$cant_filas = $db->affected_rows;

echo $cant_filas;


$db->close(); ?>
