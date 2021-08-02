<?php
include "../configuration.php";

$_SESSION['error'] = '';

if (!$user_validado) redireccionarUltimaPagina();

$_SESSION['url'] = "$PROYECTO/vista/comprar.php"; 

// Buscar Carrito para mostrar
$carritoController = new CarritoController();
$carrito = $carritoController->verCarrito();

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

        <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                $_SESSION['error'] = '';
            }
        ?>
<main class="my-5">
    <div class="container">
        <section class="text-center">
            <?php 
                $productos = '';
                $pagar = '';
                $mensaje = '';

                // se inicia el card
                echo "
                <div class=\"card text-center\">
                    <div class=\"card-header\" style='background-color: rgba(20, 86, 154, 0.8); color: white;'>
                    Carrito de compras
                    </div>
                    <div class=\"card-body\">
                ";

                // Se crean las tarjetas
                if (count($carrito) > 0) {
                    $cards_por_row = 6;
                    $row_actual_tarjetas = 0;
                    foreach ($carrito[0]['productos'] as $producto) {
                        // Se crean 4 tarjetas por row
                        $row_actual_tarjetas += 1;
                        if ($row_actual_tarjetas == 1) echo "<div class=\"row\">";      // inicio de div row
                        if ($row_actual_tarjetas <= $cards_por_row) crearTarjetaCarrito($producto);  // tarjetas 1 a 4 se crean
                        if ($row_actual_tarjetas == $cards_por_row) {                                // en la tarjeta nro 4, se cierra el div y resetea $row_actual_tarjetas
                            echo "</div>";
                            $row_actual_tarjetas = 0;
                        }   
                    }
                    $pagar = "<a class='btn btn-success' onclick='pagar()'>Pagar Carrito</a>";
                } else {
                    $mensaje = 'Su carrito se encuentra vac&iacute;o';
                }

                // se cierra el card
                echo "
                
                    <p class=\"card-text\">$pagar $mensaje</p>
                    </div>
                </div>
                ";
            ?>
        </section>
    </div>
</main>
        <!-- Se incluye el footer -->
        <?php include './footer.php'; ?>
    
        <script src="./js/md5.js" async defer></script>
        <script src="./js/index.js" async defer></script>
    </body>
</html>


<?php

// Crea tarjetas dinamicamente con modificaciones para el carrito
function crearTarjetaCarrito($producto) {
    $id = $producto->getIdproducto();
    $nombre = $producto->getPronombre();
    $detalle = $producto->getProdetalle();
    $cicantidad = $producto->getCicantidad();
    $idcompraitem = $producto->getIdcompraitem();

    $opciones_select = "";
    // Si el cicantidad supera los 6, se da la opcion de escribir una cantidad deseada 
    if ($cicantidad > 6) {
      $opciones_select = "
        <option value=\"1\">Quitar 1</option>
        <option value=\"2\">Quitar 2</option>
        <option value=\"3\">Quitar 3</option>
        <option value=\"4\">Quitar 4</option>
        <option value=\"5\">Quitar 5</option>
        <option value=\"6\">Quitar 6</option>
        <option value=\"escribir\">M&aacute;s cantidad</option>
      ";
    } else {
      for ($i=1; $i <= $cicantidad; $i++) { 
        $opciones_select = $opciones_select."<option value=\"$i\">Cantidad: $i</option>";
      }
    }

    echo "
        <div class=\"col-lg-2 col-md-3 mb-3\">
        <div class=\"card card-redondeado\">
            <div class=\"\">
            <img src=\"./resources/mbfkytc9.bmp\" />
            </div>
            <div class=\"card-body\">
            <h5 class=\"card-title\">$nombre</h5>
            <hr>
            <select id=\"$id-cantidad-select\" onclick=\"inputEscribirCantidad($id)\" class=\"form-select form-select-sm mt-3 mb-3 custom-input\">
                <option value='' selected>$cicantidad Agregados</option>
                $opciones_select
            </select>
            <input placeholder=\"Escriba una cantidad (de $cicantidad)\" id=\"$id-cantidad-input\" type=\"number\" min=\"0\" max=\"$cicantidad\" class=\"form-input form-input-sm mt-3 mb-3 custom-input cantidad-input\">
            <a onclick=\"quitarDelCarrito($id, $idcompraitem)\" class=\"btn btn-danger\">Quitar del Carro</a>
            </div>
        </div>
        </div>
    ";
    
}

?>
