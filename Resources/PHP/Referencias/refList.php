<?php

include(dirname(__DIR__) . '/Referencias/fetchActiveReferences.php');

 ?>


<?php if ($cant_filas > 0): ?>
  <?php foreach ($refList as $referencia): ?>
    <div class="reference-container btn-outline-secondary" id="<?php echo $referencia['idReferencia']?>" role="button">
      <?php echo $referencia['idReferencia'] ?> <span class="badge badge-danger" id="mnl"><?php echo $referencia['MensajesNoLeidos'] > 0 ? $referencia['MensajesNoLeidos'] : ""; ?></span>
      <!-- <?php echo $referencia['idReferencia'] ?> <span class="badge badge-danger"><?php echo $referencia['NumMensajes'] . " - " . $referencia['NumMensajesLeidos'] ?></span> -->
    </div>
  <?php endforeach; ?>

<?php else: ?>
  <p class="text-center">No se encontraron referencias</p>
<?php endif; ?>
