<?php

$productoController = new ProductoController();
$productos = $productoController->buscar([]);

?>

<div class="mt-5 mb-2" style="min-height: 50px;">
  <hr>
  <h5 id="main-title" style="color: rgba(0,0,0,.7);"><strong>Menus</strong></h5>
</div>

<div class="table-responsive">
  <table id="tabla_nuevas_solicitudes" class="table tablas_solicitudes_nuevas">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Nombre</th>
        <th scope="col">Detalle</th>
        <th scope="col">Stock</th>
        <th scope="col" style="color: #076AB3;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <!-- INICIO DE TABLA CON UN FOREACH -->
      <?php
      foreach ($productos as $producto) {
        $id = $producto->getidproducto();
        $nombre = $producto->getPronombre();
        $detalle = $producto->getProdetalle();
        $stock = $producto->getProcantstock();
        
      ?>
        <tr id="tr-<?= $id ?>">
          <td>
            <?= $id ?>
            <!-- Input de id oculto para enviar al controller el id a modificar -->
            <input readonly name="idproducto" id="idproducto" class="form-control d-none" type="number" value="<?= $id ?>">
          </td>
          <td><input name="pronombre" id="<?=$id?>pronombre" class="form-control" type="text" value="<?=$nombre?>"></td>
          <td><input name="prodetalle" id="<?=$id?>prodetalle" class="form-control" type="text" value="<?=$detalle?>"></td>
          <td><input name="procantstock" id="<?=$id?>procantstock" class="form-control" type="text" value="<?=$stock?>"></td>
          <td>
            <!-- Acciones  -->
            <button class="btn btn-success" onclick="actualizar('tr-<?=$id?>', 'producto')">
            <i class="bi bi-arrow-repeat"></i> Actualizar
            </button>
            <button class="btn btn-danger" onclick="eliminarElemento(<?=$id?>, 'producto')">
            <i class="bi bi-x-lg"></i> Eliminar
            </button>
          </td>
        </tr>
      <!-- CIERRE PHP FOREACH -->
      <?php } ?> 
    </tbody>
  </table>
  <!-- Abrir Modal -->
  <button id="agregar-elemento" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-nuevo-elemento">
  Agregar
  </button>

</div>
<hr>

<!-- Modal nuevo Elemento -->
<div class="modal fade" id="modal-nuevo-elemento" tabindex="-1" aria-labelledby="modal-nuevo-elemento-Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-nuevo-elemento-Label">Nuevo Elemento</h5>
        <button id="btn-cerrar-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-body">

        <input name="pronombre" class="form-control mb-3" type="text" placeholder="Nombre" >
        <input name="prodetalle" class="form-control mb-3" type="text" placeholder="Detalle" >
        <input name="procantstock" class="form-control mb-3" type="number" placeholder="Stock" >
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button onclick="guardarElemento('producto')" type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
