<?php

/*
$db = 'plt';
$host = 'localhost';
$port = 3306;
$usr = 'plt';
$pwd = '3xcZm3-!hg';
*/

/*
$db = 'fele';
$host = '10.1.4.10';
$port = 3306;
$usr = 'prolog';
$pwd = 'f4Tnps.03';
*/


$datab = 'problog';
$host = '10.1.4.10';
$port = 3306;
$usr = 'prolog';
$pwd = 'f4Tnps.03';


$db = new mysqli($host, $usr, $pwd, $datab, $port) or die ('No se pudo hacer la conexión al servidor de usuarios ('. $dbLogin->errno . '): ' . $dbLogin->error);


 ?>
