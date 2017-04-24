<?php

$fileName = $_GET['fn'];
$fileDirectory = $_GET['fd'];

$file = "/home/esantos/plt/problog/Resources/uploads/$fileDirectory/$fileName";


if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/x-zip-compressed');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

 ?>
