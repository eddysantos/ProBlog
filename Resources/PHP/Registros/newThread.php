<?php

include(dirname(__DIR__) . '/database.php');
$db->set_charset('UTF-8');

date_default_timezone_set('America/Monterrey');


$nombre = $_GET['nuevoHilo'];
if ($_GET['referenciaHilo' != ""]) {
  $referencia = $_GET['referenciaHilo'];
} else {
  $referencia = "Referencia no especificada";
}
$tipoOperacion = $_GET['tipoOperacion'];
$oficina = $_GET['oficina'];
$clienteRFC = $_GET['clienteRFCHilo'];
$creadoPor = $_GET['creadoPor'];

$qry = "CALL addThread(?,?,?,?,?,?,?)";

$stmt = $db->prepare($qry);

$stmt->bind_param('sssssss',
  $nombre,
  $referencia,
  $creadoPor,
  $clienteRFC,
  $tipoOperacion,
  $oficina,
  $creadoPor
);

$stmt->execute() or die ("Error agregando hilo ($stmt->errno): $stmt->error");

$aff_rows = $stmt->affected_rows;

echo $aff_rows;

$db->close();
?>
