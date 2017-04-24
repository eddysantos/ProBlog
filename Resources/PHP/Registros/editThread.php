<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');



$db->set_charset('UTF-8');
$action = $_POST['action'];

$response = array();

switch ($action) {
  case 'refId':

    $newRef = $_POST['newRef'];
    $threadId = $_POST['threadId'];

    $qry = "UPDATE tbl_Hilos SET referencia = ? WHERE pkIdHilo = ?";
    $stmt = $db->prepare($qry) or die($response['systemMessage'] = $db->error);
    $stmt->bind_param('ss',
      $newRef,
      $threadId
    );
    $stmt->execute();
    $affected_rows = $db->affected_rows;

    if ($affected_rows > 0) {
      $response['systemCode'] = 1;
      $response['systemMessage'] = "Referencia modificada con éxito..";
    } else {
      $response['systemCode'] = 0;
      $response['systemMessage'] = $db->error;
    }

    break;

  default:
    echo "No Action was selected!!";
    break;
}

$json_response = json_encode($response);
echo $json_response;

?>


<?php $db->close(); ?>
