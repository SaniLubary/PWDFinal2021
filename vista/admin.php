<?php
include "../configuration.php";

// Valida session del user
$sessionController = new SessionController();
$user_validado = false;
if ($sessionController->validar())
    $user_validado = true;   

$rol = $sessionController->getRol();
if ($rol !== 1) {
  header("Location: ../");
  exit();
}

// Setear esta pag como la ultima visitada para las redirecciones con redireccionarUltimaPagina() en utils/funciones.php 
$_SESSION['url'] = "$PROYECTO/vista/admin/"; 

// Buscar Productos a mostrar
$productoController = new ProductoController();
$productos = $productoController->buscar();

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

  <!-- Se incluye el header -->
  <?php include './header.php'; ?>

  <!-- Contenido Principal -->
  <main class="my-5">
    <div class="container">
      <section class="text-center">
        <h4 id="main-title" class="mb-5" style="color: rgba(0,0,0,.7);"><strong>Administrador</strong></h4>
        <!-- Menu -->
        <a style="text-decoration: none;" href="./admin.php?view=usuarios">
          <button class="btn btn-primary">Usuarios</button>
        </a>
        
        <a style="text-decoration: none;" href="./admin.php?view=productos">
          <button class="btn btn-primary">Productos</button>
        </a>
        
        <a style="text-decoration: none;" href="./admin.php?view=menus">
          <button class="btn btn-primary">Menus</button>
        </a>
        
        <?php 
        if (isset($_GET) && isset($_GET['view'])) {
          switch ($_GET['view']) {
            case 'usuarios':
              include './admin_views/usuarios.php';
              break;
            case 'productos':
              include './admin_views/productos.php';
              break;
            case 'menus':
              include './admin_views/menus.php';
              break;
          }
        }        
        ?>
      </section>
    </div>
  </main>
  
  <!-- Se incluye el footer -->
  <?php include './footer.php'; ?>
  
  <script type="text/javascript" src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="./js/index.js"></script>
  <script type="text/javascript" src="./js/admin.js"></script>
</body>
</html>
