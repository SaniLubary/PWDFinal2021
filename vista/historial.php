<?php
include "../configuration.php";

unset($_SESSION['url']);
if (!$user_validado) {
  redireccionarUltimaPagina();
}

$usr = $sessionController->getUsuario();

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>TP Final PWD</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="./css/index.css" />
</head>
<body class="d-flex flex-column min-vh-100">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Se incluye el header -->
        <?php include './header.php'; ?>

<main class="my-5">
    <div class="container">
        <section class="text-center">
          <!--  -->
          <!-- Control de estado de las compras -->
          <!--  -->
          <div class="mt-5 mb-2" style="min-height: 50px;">
            <hr>
            <h5 id="main-title" style="color: rgba(0,0,0,.7);"><strong>Compras</strong></h5>
          </div>

          <div class="table-responsive">
            <table id="tabla_nuevas_solicitudes" class="table tablas_solicitudes_nuevas">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Id Compra</th>
                  <th scope="col">Fecha Compra</th>
                  <th scope="col">Estado Actual</th>
                </tr>
              </thead>
              <tbody>
                <!-- INICIO DE TABLA CON UN FOREACH -->
                <?php
                $usuarioController = new UsuarioController();
                $compraController = new CompraController();
                $compraEstadoController = new CompraEstadoController();
                $compraEstadoTipoController = new compraEstadoTipoController();

                $compras = $compraController->buscar(['idusuario' => $usr->getIdusuario()]);
                
                // Creacion de filas para cada compra
                foreach ($compras as $compra) {
                  $id = $compra->getIdcompra();
                  
                  // Se formatea la fecha
                  $fecha_compra = $compra->getCofecha();
                  if (isset($fecha_compra) && $fecha_compra !== (null or '')){
                    $fecha_compra = date("Y-m-d",strtotime($fecha_compra));
                  } else $fecha_compra = ''; 
            
                  // Se busca el nombre del estado de la compra actual
                  $compraEstado = $compraEstadoController->buscar(['idcompra' => $id], true);
                  if (!empty($compraEstado)) {
                    $compraEstado = $compraEstado[0];
                    $estado = $compraEstado->getCompraestadotipo()->getCetdescripcion();
                  } else continue; // No se muestra el estado 'Posee Productos en el Carro'
                  
                ?>
                  <tr id="tr-<?=$id?>">
                    <td>
                      <?=$id?>
                      <!-- Input de id oculto para facilitar el request de update de este elemento -->
                      <input readonly name="idmenu" id="idmenu" class="form-control d-none" type="number" value="<?=$id?>">
                    </td>
                    <td><input readonly name="cofecha" id="<?=$id?>cofecha" class="form-control" type="date" value="<?= $fecha_compra ?>"></td>
                    <td><input readonly name="estado" id="<?=$id?>estado" class="form-control" type="text" value="<?= $estado ?>"></td>
                  </tr>
                <!-- CIERRE PHP FOREACH -->
                <?php } ?> 
              </tbody>
            </table>
          </div>
          <hr>

        </section>
    </div>
</main>
        <!-- Footer -->
        <?php include './footer.php'; ?>
    
        <script src="./js/md5.js" async defer></script>
        <script src="./js/index.js" async defer></script>
        <script src="./js/admin.js" async defer></script>
    </body>
</html>
