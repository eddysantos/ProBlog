<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');

$qry = "CALL fetchUsers()";

$stmt = $db->query($qry) or die("Error extrayendo usuarios ($db->errno): $db->error");
$users = array();

while ($row = $stmt->fetch_assoc()) {
    $users[]=$row;
}

 ?>
