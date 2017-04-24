<?php


$datab = 'bd_demo_13';
$host = '10.1.4.10';
$port = 3306;
$usr = 'prolog';
$pwd = 'f4Tnps.03';


$db = new mysqli($host, $usr, $pwd, $datab, $port) or die ('No se pudo hacer la conexión al servidor de usuarios ('. $dbLogin->errno . '): ' . $dbLogin->error);


 ?>
