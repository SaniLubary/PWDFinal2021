<?php
include "../configuration.php";

// Valida session del user
$sessionController = new SessionController();
$user_validado = false;
if ($sessionController->validar())
    $user_validado = true;   

// Setear esta pag como la ultima visitada para las redirecciones con redireccionarUltimaPagina() en utils/funciones.php 
$_SESSION['url'] = "$PROYECTO/vista/"; 

// Buscar Productos a mostrar
$productoController = new ProductoController();
$productos = $productoController->buscar();

$carritoController = new CarritoController();
if ( $user_validado ) {
    if (array_key_exists('producto',$_COOKIE)) {
        // La cookie 'producto' existe si el user intento comprar antes de ser validado
        // Ahora el user esta validado, El producto se agrega y la cookie se elimina
        if ($carritoController->agregarAlCarrito($_COOKIE['producto'], 1)) {
            // Se elimina la cookie seteando su fecha de vencimiento en el pasado
            setcookie("producto", "", time()-10, "/");
        }
    }
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html lang="en">
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
        <h4 class="mb-5" style="color: rgba(0,0,0,.7);"><strong>Tienda de Cuadros Online</strong></h4>
        <!-- // TODO Modificar avisos -->
        <?php 
            // Avisos de operaciones realizadas por el usuario
            if (isset($_SESSION['error'])) {
                echo "<p style='color: red;'>".$_SESSION['error']."</p>";
            }

            if (isset($_SESSION['agregado_al_carrito']) && is_numeric($_SESSION['agregado_al_carrito'])) {
                $nombre_producto = $productos[$_SESSION['agregado_al_carrito']-1]->getPronombre();
                echo "<p style='color: green;'>Producto '$nombre_producto' agregado al carrito!</p>";
            }
        ?>
        
        <?php 
          // Productos disponibles
          $cards_por_row = 4;
          $row_actual_tarjetas = 0;
          foreach ($productos as $key => $producto) {
              // Se crean 4 tarjetas por row
              $row_actual_tarjetas += 1;
              if ($row_actual_tarjetas == 1) echo "<div class=\"row\">"; 
              if ($row_actual_tarjetas <= 4) crearTarjeta($producto, $user_validado);
              if ($row_actual_tarjetas == 4) {
                  echo "</div>";
                  $row_actual_tarjetas = 0;
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
</body>
</html>

<?php

// Crea tarjetas dinamicamente
function crearTarjeta($producto, $user_validado) {
    $id = $producto->getIdproducto();
    $nombre = $producto->getPronombre();
    $detalle = $producto->getProdetalle();
    $stock = $producto->getProcantstock();

    $opciones_select = "";
    // Si el stock supera los 6, se da la opcion de escribir una cantidad deseada 
    if ($stock > 6) {
      $opciones_select = "
        <option value=\"1\">Cantidad: 1</option>
        <option value=\"2\">Cantidad: 2</option>
        <option value=\"3\">Cantidad: 3</option>
        <option value=\"4\">Cantidad: 4</option>
        <option value=\"5\">Cantidad: 5</option>
        <option value=\"6\">Cantidad: 6</option>
        <option value=\"escribir\">M&aacute;s cantidad</option>
      ";
    } else {
      for ($i=1; $i <= $stock; $i++) { 
        $opciones_select = $opciones_select."<option value=\"$i\">Cantidad: $i</option>";
      }
    }

    echo "
        <div class=\"col-lg-3 col-md-4 mb-4\">
        <div class=\"card card-redondeado\">
            <div class=\"\">
            <img src=\"./resources/mbfkytc9.bmp\" />
            </div>
            <div class=\"card-body\">
            <h5 class=\"card-title\">$nombre</h5>
            <p class=\"card-text\">
                $detalle
            </p>
            <hr>
            <select name=\"$id-cantidad\" onclick=\"inputEscribirCantidad(this)\" class=\"form-select form-select-sm mt-3 mb-3 custom-input\">
                <option selected>Elegir Cantidad ($stock disponibles)</option>
                $opciones_select
            </select>
            <input placeholder=\"Escriba una cantidad\" name=\"$id-cantidad\" type=\"number\" min=\"0\" max=\"$stock\" class=\"form-input form-input-sm mt-3 mb-3 custom-input cantidad-input\">
            <a href=\"#!\" class=\"btn btn-primary\">Comprar</a>
            <a href=\"#!\" class=\"btn btn-primary\"><i class=\"bi bi-cart4\"></i> Agregar</a>
            </div>
        </div>
        </div>
    ";
    
}

?>
