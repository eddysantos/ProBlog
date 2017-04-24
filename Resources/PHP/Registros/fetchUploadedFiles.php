<?php

include(dirname(__DIR__) . '/database.php');

date_default_timezone_set('America/Monterrey');

$fileIcons = array(
  "doc"=>"fa-file-word-o",
  "docx"=>"fa-file-word-o",
  "xls"=>"fa-file-excel-o",
  "xlsx"=>"fa-file-excel-o",
  "ppt"=>"fa-file-powerpoint-o",
  "pptx"=>"fa-file-powerpoint-o",
  "jpeg"=>"fa-file-image-o",
  "jpg"=>"fa-file-image-o",
  "gif"=>"fa-file-image-o",
  "png"=>"fa-file-image-o",
  "pdf"=>"fa-file-pdf-o",
  "zip"=>"fa-archive-o",
  "rar"=>"fa-archive-o",
);


$numReferencia = $_POST['numReferencia'];

//$numReferencia = 'N17000004';
//$usuario = '1';
$blgFiles = array();

$db->set_charset('UTF-8');
$numReferencia = "'" . $db->real_escape_string($numReferencia) . "'";

$qry = "CALL fetchBlgFiles($numReferencia)";
$stmt = $db->query($qry) or die ("Error del query ($db->errno): $db->error");
//$rslt = $stmt->get_result();
while ($row = $stmt->fetch_assoc()) {
  $blgFiles[] = $row;
}
?>

<?php foreach ($blgFiles as $file): ?>
  <?php
  $tipoArchivo = pathinfo($file['FileName'], PATHINFO_EXTENSION);
   ?>
  <tr>
    <td><?php echo $file['FileName'] ?></td>
    <td><?php echo $file['PrimerNombre'] . " " . $file['PrimerApellido'] ?></td>
    <td><?php echo $file['FechaHora'] ?></td>
    <td class="text-center"><a href="<?php echo $file['FilePath']?>"><i class="fa <?php echo $fileIcons[$tipoArchivo]?>"></i></a></td>
  </tr>

<?php else: ?>
  <p>No existen documentos para este hilo</p>
<?php endforeach; ?>

<?php $db->close(); ?>
