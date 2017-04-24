<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');
header('Content-type: text/plain; charset=utf-8');



$cltSrch = $_POST['cltSrch'];
$cltList = array();

$db->set_charset('UTF-8');
$cltSrch = '"' . $db->real_escape_string($cltSrch) . '"';
//$cltSrch = $db->real_escape_string($cltSrch);

$qry = "CALL clientSearch($cltSrch)";
//$stmt = $db->prepare($qry);
//$stmt->bind_param('ss', $numReferencia, $usuario);
//$stmt->execute();
//$stmt->store_result();
//$stmt->use_result();
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");
//$rslt = $stmt->get_result();
while ($row = $stmt->fetch_assoc()) {
  $cltList[] = $row;
}
?>

<?php if ($cltSrch != ""): ?>
  <?php if ($stmt->num_rows == 0): ?>
    <p>No se encontró ningún cliente con ese nombre</p>

  <?php else: ?>

    <ul class="list-unstyled">
    <?php foreach ($cltList as $clt): ?>
      <li role="button">
        <p class="listClientName">
        <span id="razonSocial"><?php echo $clt['RazonSocial']?></span>
        <br>
        <span><small><i id="RFC"><?php echo $clt['RFC']?></i></small></span>
        <hr>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
<?php endif; ?>




<?php $db->close(); ?>
