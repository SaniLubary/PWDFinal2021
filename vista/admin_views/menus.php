<?php
$menuController = new MenuController();
$menus = $menuController->buscar([]);

// Crear array de opciones para el select idpadre dentro de la tabla
// Durante la creacion de la tabla, se quita el elemento del arr con 'key' => 'id propio', ya que menu padre no puede ser si mismo
$opciones = [];
foreach ($menus as $menu) {
  $idmenu = $menu->getIdmenu();
  $menombre = $menu->getMenombre();
  $opciones[$idmenu] = "<option value=\"$idmenu\">$idmenu: $menombre</option>";
}

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
        <th scope="col">Descripcion</th>
        <th scope="col">Id padre</th>
        <th scope="col">Deshabilitado</th>
        <th scope="col" style="color: #076AB3;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($menus as $menu) {
        // opciones para el idpadre, el menu no deberia ser su propio padre
        $opciones_select = $opciones;
        $opciones_select[$menu->getIdmenu()] = null; 
        $opciones_select_imploded = implode(" ", $opciones_select);
        $id = $menu->getIdmenu();
        $medeshabilitado = $menu->getMedeshabilitado();
        if (isset($medeshabilitado) && $medeshabilitado !== (null or '')){
          $medeshabilitado = date("Y-m-d",strtotime($medeshabilitado));
        }else $medeshabilitado = ''; 
      ?>
        <tr id="tr-<?= $id ?>">
          <td>
            <?= $id ?>
            <!-- Input de id oculto para enviar al controller el id a modificar -->
            <input readonly name="idmenu" id="idmenu" class="form-control d-none" type="number" value="<?= $id ?>">
          </td>
          <td><input name="menombre" id="<?=$id?>menombre" class="form-control" type="text" value="<?= $menu->getMenombre() ?>"></td>
          <td><input name="medescripcion" id="<?=$id?>medescripcion" class="form-control" type="text" value="<?= $menu->getMedescripcion() ?>"></td>
          <td>
            <select name="idpadre" id="<?=$id?>idpadre" class="form-select form-select-sm">
                <option value='<?= $menu->getIdpadre() ?>' selected><?= $menu->getIdpadre() ?></option>
                <?= $opciones_select_imploded ?>
            </select>
          <td>
            <input name="medeshabilitado" id="<?=$id?>medeshabilitado" <?= $medeshabilitado === '' ?'':'checked' ?> class="form-check-input text-center float-none" style="text-align: initial;" type="checkbox">
            <input readonly type="date" value="<?=$medeshabilitado?>" class="form-control">
          <td>
            <!-- Acciones  -->
            <button class="btn btn-success" onclick="actualizar('tr-<?= $id?>')">
            <i class="bi bi-arrow-repeat"></i> Actualizar
            </button>
            <button class="btn btn-danger" onclick="eliminarElemento(<?= $id?>)">
            <i class="bi bi-x-lg"></i> Eliminar
            </button>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <!-- Abrir Modal -->
  <button id="agregar-elemento" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-nuevo-elemento">
  Agregar
  </button>

</div>
<hr>

<!-- Modal nuevo elemento -->
<div class="modal fade" id="modal-nuevo-elemento" tabindex="-1" aria-labelledby="modal-nuevo-elemento-Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-nuevo-elemento-Label">Nuevo Elemento</h5>
        <button id="btn-cerrar-modal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-body">

        <input name="menombre" id="nuevo-nombre" class="form-control mb-3" type="text" placeholder="Nombre" >
        <input name="medescripcion" id="nuevo-descripcion" class="form-control mb-3" type="text" placeholder="Descripcion" >
        <select name="idpadre" id="nuevo-idpadre" class="form-select form-select-sm mb-3">
                <option value='' selected>Id Padre</option>
                <?= $opciones_select = implode(" ", $opciones); ?>
            </select>
          <input class="form-check-input text-start" style="text-align: initial;" type="checkbox" name="medeshabilitado" id="nuevo-medeshabilitado">
          <label class="form-check-label" for="nuevo-medeshabilitado">Crear el menu en estado deshabilitado</label>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button id="btn-guardar" type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
