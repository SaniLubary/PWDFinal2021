<?php
$menuController = new MenuController();
$menus = $menuController->buscar([]);
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
            foreach ($menus as $menu) {?>
                <tr id=<?= $menu->getIdmenu() ?>>
                    <td><?= $menu->getIdmenu() ?></td>
                    <td><input id="nombre" class="form-control" type="text" value="<?= $menu->getMenombre() ?>"></td>
                    <td><input id="descripcion" class="form-control" type="text" value="<?= $menu->getMedescripcion() ?>"></td>
                    <td><input id="idpadre" class="form-control" type="number" value="<?= $menu->getIdpadre() ?>"></td>
                    <td><input id="deshabilitado" class="form-control" type="date" value="<?= $menu->getMedeshabilitado() ?>"></td>
                    <td>
                      <!-- Acciones  -->
                      <button class="btn btn-success" onclick="actualizar(<?= $menu->getIdmenu()?>)">
                      <i class="bi bi-arrow-repeat"></i> Actualizar
                      </button>
                      <button class="btn btn-danger" onclick="eliminar(<?= $menu->getIdmenu()?>)">
                      <i class="bi bi-x-lg"></i> Eliminar
                      </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
      </table>
      <button class="btn btn-success" onclick="agregar()">Agregar</button>
</div>
<hr>

