<?php

include(dirname(__DIR__) . '/globalDatabase.php');

date_default_timezone_set('America/Monterrey');
header('Content-type: text/plain; charset=utf-8');



$c_RFC = $_POST['c_RFC'];
$cveAduana = $_POST['cveAduana'];
$tOperacion = $_POST['tOperacion'];
$pkIdUsuario = $_POST['pkIdUsuario'];

$refList = array();

$db->set_charset('UTF-8');
$c_RFC = '"' . $db->real_escape_string($c_RFC) . '"';
$cveAduana = '"' . $db->real_escape_string($cveAduana) . '"';
$tOperacion = '"' . $db->real_escape_string($tOperacion) . '"';
$pkIdUsuario = '"' . $db->real_escape_string($pkIdUsuario) . '"';


$qry = "CALL fetchRefActivas($c_RFC, $cveAduana, $tOperacion, $pkIdUsuario)";
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");

$cant_filas = $stmt->num_rows;

while ($row = $stmt->fetch_assoc()) {
  $refList[] = $row;
}

$db->close(); ?>
