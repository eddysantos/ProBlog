<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');


$mailToValidate = $_POST['mailToValidate'];


$db->set_charset('UTF-8');
$mailToValidate = "'" . $db->real_escape_string($mailToValidate) . "'";


$qry = "SELECT gNombreUsuario FROM tbl_usuarios WHERE gNombreUsuario = $mailToValidate";
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");

echo $stmt->num_rows;



$db->close(); ?>
