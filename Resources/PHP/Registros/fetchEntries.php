<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');


$numReferencia = $_POST['numReferencia'];
$usuario = $_POST['idUsuario'];

//$numReferencia = 'N17000004';
//$usuario = '1';
$blgEntries = array();

$db->set_charset('UTF-8');
$numReferencia = "'" . $db->real_escape_string($numReferencia) . "'";
$usuarioQ = "'" . $db->real_escape_string($usuario)  . "'";

$qry = "CALL fetchBlgEntries($numReferencia,$usuarioQ)";
//$stmt = $db->prepare($qry);
//$stmt->bind_param('ss', $numReferencia, $usuario);
//$stmt->execute();
//$stmt->store_result();
//$stmt->use_result();
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");
//$rslt = $stmt->get_result();
while ($row = $stmt->fetch_assoc()) {
  $blgEntries[] = $row;
}
?>

<?php foreach ($blgEntries as $blgEntry): ?>
  <?php
  $tStamp = DateTime::createFromFormat('Y-m-d H:i:s',$blgEntry['FechaHora']);
  $tStamp = $tStamp->format('F-d H:i');
  ?>
  <p class="<?php echo $blgEntry['IdUsuario'] == $usuario ? 'message-own' : 'message-nonown';?>">
    <span><b><?php echo $blgEntry['PrimerNombre']. " " . $blgEntry['PrimerApellido']. " | " .  $tStamp?></b></span><br>
    <?php echo $blgEntry['Contenido']; ?>
  </p>
<?php endforeach; ?>

<?php $db->close(); ?>
