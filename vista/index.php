<?php
include "../configuration.php";
include "./requests/carrito.php";

// Valida session del usuario
$sessionController = new SessionController();
$user_validado = false;
if ($sessionController->validar())
    $user_validado = true;   

// Setear esta pag como la ultima visitada
$_SESSION['url'] = "$PROYECTO/vista/index.php"; // La funcion redireccionarUltimaPagina() en utils/funciones.php realiza el redirect a la ultima pag seteada

// Buscar Productos a mostrar
$productoController = new ProductoController();
$productos = $productoController->buscar();

if ( $user_validado ) {
    $compra_items = verCarrito();
}

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
            <?php 
                if ($user_validado) {
                    echo "Bienvenido ".$_SESSION['usnombre']."!<br>";
                    echo "<button onclick='cerrarSession();'>Cerrar Sesion</button>";
                } else {
                    echo "
                    <button onclick='window.location.replace(`./login.php`);'>Iniciar Sesion</button>
                    <button onclick='window.location.replace(`./register.php`);'>Crear Cuenta</button>
                    ";
                }
            ?>
        </header>

        <?php 
            if (isset($_SESSION['error'])) {
                echo "<p style='color: red;'>".$_SESSION['error']."</p>";
            }

            if (isset($_SESSION['agregado_al_carrito']) && $_SESSION['agregado_al_carrito']) {
                echo "<p style='color: green;'>Producto agregado al carrito!</p>";
            }

            foreach ($productos as $key => $producto) {
                $nombre = $producto->getPronombre();
                $detalle = $producto->getProdetalle();
                $stock = $producto->getProcantstock();
                echo "
                    <div style='border: 3px solid black; margin: 3px;'>
                    <p>$detalle</p>
                    <p>Stock: $stock<p>
                    <button onclick='agregarAlCarrito(\"$key\", \"$user_validado\")'>Comprar</button>
                    </div>
                ";
            }
        ?>
        <script src="./js/index.js" async defer></script>
    </body>
</html>