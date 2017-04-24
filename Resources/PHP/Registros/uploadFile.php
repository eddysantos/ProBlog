<?php

include(dirname(__DIR__) . '/database.php');
$db->set_charset('UTF-8');

date_default_timezone_set('America/Monterrey');



//echo $_POST;
//echo $_FILES;

$response = array();

$ahora = date('Y-m-d H:i:s');
$ahoraFormat = date('F-d H:i');

$referencia = $_POST['referenceId'];
$thread = $_POST['thread'];
$filename = $_FILES['file']['name'];
$tipoArchivo = pathinfo($filename, PATHINFO_EXTENSION);
$location = "/home/esantos/plt/problog/Resources/uploads/$thread";
$file = $location . "/" . basename($filename);
$usuario = $_POST['userId'];

//CHECK IF FILE EXISTS

if (file_exists($file)) {
  $uploadcheck1 = 0;
  $response['returnMessage']='El archivo ya existe en el servidor para este hilo de comunicación';
  $response['uploadCode']=0;
} else {
  $uploadcheck1 = 1;
}

// ALLOW ONLY VALID FILE TYPES

if (
  $tipoArchivo != "docx" &&
  $tipoArchivo != "doc" &&
  $tipoArchivo != "xlsx" &&
  $tipoArchivo != "xls" &&
  $tipoArchivo != "ppt" &&
  $tipoArchivo != "pptx" &&
  $tipoArchivo != "jpeg" &&
  $tipoArchivo != "jpg" &&
  $tipoArchivo != "gif" &&
  $tipoArchivo != "png" &&
  $tipoArchivo != "pdf" &&
  $tipoArchivo != "zip" &&
  $tipoArchivo != "rar"
) {
  $uploadcheck2 = 0;
  $response['returnMessage']="Unicamente se permiten los siguientes tipos de archivo: \n - Word \n -Excel \n -Powerpoint \n -Imagenes (JPEG, JPG, PNG ó GIF) \n -PDFs \n -Archivos comprimidos ZIP.";
  $response['uploadCode']=0;
} else {
  $uploadcheck2 = 1;
}

// If all validation passes, then upload the file..

if ($uploadcheck1 == 1 && $uploadcheck2 == 1) {
  if (!file_exists($location)) {
    mkdir($location, 0777, true);
  }
  if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
    $response['systemMessage'] = "Archivo subido con éxito";
    $response['uploadCode'] = 1;
  } else {
    $response['systemMessage'] = "Hubo un error al subir el archivo";
    $response['uploadCode'] = 0;
    $response['errorMessage'] = $_FILES['file']['error'];
  }
} else {
  $response['systemMessage'] = "Fallo la validación del archivo";
  $response['uploadCode'] = 2;
}

$json_response = json_encode($response);

echo $json_response;

if ($response['uploadCode'] == 1) {


  // Now that the file is uploaded, it needs to be saved and displayed as a message.

  $ahora = date('Y-m-d H:i:s');
  $ahoraFormat = date('F-d H:i');
  $referencia = $_POST['thread']; //Equivalente al topico
  $contenido = "<a href='../Resources/PHP/Registros/downloadFile.php?fn=$filename&fd=$thread'>$filename</a>"; //El contenido de la entrada
  $path = "../Resources/PHP/Registros/downloadFile.php?fn=$filename&fd=$thread";
  //$usuario = $_POST['userId']; //El usuario que ingresa la entrada

  $qry = "SELECT gPrimerNombre, gPrimerApellido FROM tbl_usuarios WHERE pkIdUsuario = ?";
  $stmt = $db->prepare($qry);
  $stmt->bind_param('s', $usuario);
  $stmt->execute();
  $rslt = $stmt->get_result();

  while ($row = $rslt->fetch_assoc()) {
    $nomUsuario = $row['gPrimerNombre'] . " " . $row['gPrimerApellido'];
  }



  //$qry = "CALL fetchAddBlogEntry(?,?,?,?)";
  //$qry = "INSERT INTO blgEntries (grlReferencia, grlContenido, grlTimestamp, fkIdUsuario) VALUES(?,?,?,?)";

  $q_Referencia = "'" . $db->real_escape_string($referencia) . "'";
  $q_Contenido = "'" . $db->real_escape_string($contenido) . "'";
  $q_Timestamp = "'" . $db->real_escape_string($ahora) . "'";
  $q_idUsuario = "'" . $db->real_escape_string($usuario) . "'";
  $q_tipoMensaje = "'Archivo'";
  $q_path = "'" . $db->real_escape_string($path) . "'";
  $q_fileName = "'" . $db->real_escape_string($filename) . "'";

  $qry = "CALL submitEntry($q_Referencia, $q_Contenido, $q_Timestamp, $q_idUsuario, $q_tipoMensaje, $q_path, $q_fileName)";

  $stmt2 = $db->query($qry) or die(error_log("Error ($db->errno): $db->error"));

  $resultados = '';

  while ($row = $stmt2->fetch_assoc()) {
    $resultados = $row;
  }

  //$resultados = $stmt->fetch_assoc();
  //$rslt = $stmt->get_result();

  $entryData = array(
          'category' => $_POST['thread']
        , 'title'    => 'The Title!'
        , 'article'  => $contenido
        , 'when'     => $ahoraFormat
        , 'usuario'  => $nomUsuario
        , 'idUsuario' => $usuario
        , 'idMensaje' => $resultados['MensajeInsertado']
      );

  // Push the new record into the subscribed user's window.

  $context = new ZMQContext();
  $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
  $socket->connect("tcp://127.0.0.1:5555");

  $socket->send(json_encode($entryData));

}

/*
$qry = "SELECT gPrimerNombre, gPrimerApellido FROM tbl_usuarios WHERE pkIdUsuario = ?";
$stmt = $db->prepare($qry);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  $nomUsuario = $row['gPrimerNombre'] . " " . $row['gPrimerApellido'];
}



//$qry = "CALL fetchAddBlogEntry(?,?,?,?)";
//$qry = "INSERT INTO blgEntries (grlReferencia, grlContenido, grlTimestamp, fkIdUsuario) VALUES(?,?,?,?)";

$q_Referencia = "'" . $db->real_escape_string($referencia) . "'";
$q_Contenido = "'" . $db->real_escape_string($contenido) . "'";
$q_Timestamp = "'" . $db->real_escape_string($ahora) . "'";
$q_idUsuario = "'" . $db->real_escape_string($usuario) . "'";

$qry = "CALL submitEntry($q_Referencia, $q_Contenido, $q_Timestamp, $q_idUsuario)";

$stmt2 = $db->query($qry) or die(error_log("Error ($db->errno): $db->error"));

$resultados = '';

while ($row = $stmt2->fetch_assoc()) {
  $resultados = $row;
}

//$resultados = $stmt->fetch_assoc();
//$rslt = $stmt->get_result();

$entryData = array(
        'category' => $_POST['referencia']
      , 'title'    => 'The Title!'
      , 'article'  => $_POST['content']
      , 'when'     => $ahoraFormat
      , 'usuario'  => $nomUsuario
      , 'idUsuario' => $usuario
      , 'idMensaje' => $resultados['MensajeInsertado']
    );

// Push the new record into the subscribed user's window.

$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
$socket->connect("tcp://127.0.0.1:5555");

$socket->send(json_encode($entryData));

$db->close();
*/
?>
