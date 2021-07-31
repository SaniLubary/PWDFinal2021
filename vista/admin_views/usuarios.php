<?php

$usr = $sessionController->getUsuario();

$usuarioController = new UsuarioController();
$usuarios = $usuarioController->buscar([]);

$usuariorolcontroller = new usuariorolcontroller();
$rolcontroller = new RolController();

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
        <th scope="col">Mail</th>
        <th scope="col">Rol</th>
        <th scope="col" style="color: #076AB3;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <!-- INICIO DE TABLA -->
      <?php
      foreach ($usuarios as $usuario) {
        // No nos mostramos a nosotros mismos en la lista, para no quitarnos sin querer rol de admin? supongo
        if ($usuario->getIdusuario() == $usr->getidusuario()) {
          continue;
        }
        
        $id = $usuario->getidusuario();
        $nombre = $usuario->getUsnombre();
        $mail = $usuario->getUsmail();
        
        $usuariorol = $usuariorolcontroller->buscar(['idusuario' => $id]);
        if (!empty($usuariorol)) {
          $rol = $rolcontroller->buscar(['idrol'=>$usuariorol[0]->getidrol()]);
          if (!empty($rol)) {
            $rol = $rol[0]->getRodescripcion();
          }
        }
        
      ?>
        <tr id="tr-<?= $id ?>">
          <td>
            <?= $id ?>
            <!-- Input de id oculto para enviar al controller el id a modificar -->
            <input readonly name="idusuario" id="idusuario" class="form-control d-none" type="number" value="<?=$id?>">
          </td>
          <td><input readonly name="usnombre" class="form-control" type="text" value="<?=$nombre?>"></td>
          <td><input readonly name="usmail" class="form-control" type="text" value="<?=$mail?>"></td>
          <td><input readonly name="usrol" class="form-control" type="text" value="<?=$rol?>"></td>
          <td>
            <!-- Acciones  -->
            <button class="btn btn-<?= $rol=='admin'?'success':'danger'?> " onclick="setearRol(<?=$id?>, '<?= $rol=='admin'?2:1?>')">
              <i class="bi bi-arrow-repeat"></i> <?= $rol=='admin'?'Dar rol Cliente':'Dar rol Admin'?> 
            </button>
          </td>
        </tr>
      <!-- FIN TABLA CIERRE PHP FOREACH -->
      <?php } ?> 
    </tbody>
  </table>
</div>
<hr>
