<?php

include(dirname(__DIR__) . '/database.php');
$db->set_charset('UTF-8');

date_default_timezone_set('America/Monterrey');

$ahora = date('Y-m-d H:i:s');
$ahoraFormat = date('F-d H:i');
$referencia = $_POST['referencia']; //Equivalente al topico
$contenido = $_POST['content']; //El contenido de la entrada
$usuario = $_POST['idUsuario']; //El usuario que ingresa la entrada

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
$q_tipoMensaje = "'Mensaje'";
$q_path = "'N/A'";
$q_fileName = "'N/A'";

$qry = "CALL submitEntry($q_Referencia, $q_Contenido, $q_Timestamp, $q_idUsuario, $q_tipoMensaje, $q_path, $q_fileName)";

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
?>
