<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');



$db->set_charset('UTF-8');

$rfc = $_POST['c_RFC'];
$tipoOperacion = $_POST['tOperacion'];
$oficina = $_POST['cveAduana'];
$pkIdUsuario = $_POST['pkIdUsuario'];


$qry = "CALL fetchThreads(?,?,?,?)";

$stmt = $db->prepare($qry);
$stmt->bind_param('ssss',
  $rfc,
  $tipoOperacion,
  $oficina,
  $pkIdUsuario
);

$stmt->execute() or die("Error trayendo los hilos($stmt->errno): $stmt->error");
$rslt = $stmt->get_result();
$threads = array();

while ($row = $rslt->fetch_assoc()) {
  $threads[] = $row;
}

$cant_filas = $stmt->affected_rows;

?>


<?php if ($cant_filas > 0): ?>
  <?php foreach ($threads as $thread): ?>
    <div class="reference-container btn-outline-secondary" id="<?php echo $thread['idHilo']?>" reftraf="<?php echo $thread['referencia']?>"  role="button">
      <?php echo $thread['nombre'] ?> <span class="badge badge-danger" id="mnl" reftraf="<?php $thread['referencia']?>"><?php echo $thread['MensajesNoLeidos'] > 0 ? $thread['MensajesNoLeidos'] : ""; ?></span>
    </div>
  <?php endforeach; ?>

<?php else: ?>
  <p class="text-center">No se encontraron referencias</p>
<?php endif; ?>


<?php $db->close(); ?>
