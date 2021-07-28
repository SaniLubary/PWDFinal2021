<?php
include "../configuration.php";

$_SESSION['error'] = '';

// Si el usuario No tiene sesion activa, se redirecciona
$sessionController = new SessionController();
if (!$sessionController->validar())
    redireccionarUltimaPagina();
    
$user_validado = true;

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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/index.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <header>
            <div style="text-align: center; font-size: large;">Tienda Online</div>
            <div style="display: inline-block;">
                <div>
                    <?php 
                        echo "Bienvenido ".$_SESSION['usnombre']."!<br>";
                        echo "<button onclick='cerrarSession();'>Cerrar Sesion</button>";
                        echo "<button onclick='window.location.replace(`./`);'>Volver al Inicio</button>";
                    ?>
                </div>
            </div>
        </header>
        <?php 
            // Avisos de operaciones realizadas por el usuario
            if (isset($_SESSION['error']) && $_SESSION['error'] != '') {
                echo "<p style='color: red;'>".$_SESSION['error']."</p>";
            }
        ?>
        <div style="display: inline-block; position: relative; width: 100%;">
            <div style="display: flex;">
            <?php 
                if (count($carrito) > 0) {
                    foreach ($carrito[0]['items'] as $compraitem) {
                        $idcompraitem = $compraitem->getIdcompraitem();
                        $idcompra = $compraitem->getIdcompra();
                        $idproducto = $compraitem->getIdproducto();
                        $cantidad = $compraitem->getCicantidad();
                        echo "
                            <div style='border: 3px solid black; margin: 3px; width: 20%'>
                            <p>Producto: $idproducto<p>
                            <p>Cantidad seleccionada: $cantidad<p>
                            </div>
                        ";
                    }
                }
            ?>
            </div>
            <button onclick='pagar()'>Pagar Carrito</button>
        </div>
        <script src="./js/index.js" async defer></script>
    </body>
</html>