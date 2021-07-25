<?php
include "../configuration.php";

// Valida session del user
$sessionController = new SessionController();
$user_validado = false;
if ($sessionController->validar())
    $user_validado = true;   

// Setear esta pag como la ultima visitada para las redirecciones con redireccionarUltimaPagina() en utils/funciones.php 
$_SESSION['url'] = "$PROYECTO/vista/index.php"; 

// Buscar Productos a mostrar
$productoController = new ProductoController();
$productos = $productoController->buscar();

$carritoController = new CarritoController();
$carrito = $carritoController->verCarrito();
verEstructura($carrito);
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
                        if ($user_validado) {
                            echo "Bienvenido ".$_SESSION['usnombre']."!<br>";
                            echo "<button onclick='cerrarSession();'>Cerrar Sesion</button>";
                            echo "<button onclick='window.location.replace(`./register.php`);'>Ver Carrito</button>";
                        } else {
                            echo "
                            <button onclick='window.location.replace(`./login.php`);'>Iniciar Sesion</button>
                            <button onclick='window.location.replace(`./register.php`);'>Crear Cuenta</button>
                            ";
                        }
                    ?>
                </div>
            </div>
        </header>
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
        <div style="display: inline-block; position: relative; width: 100%;">
            <div style="display: flex;">
            <?php 
                // Productos disponibles
                foreach ($productos as $key => $producto) {
                    $id = $producto->getIdproducto();
                    $nombre = $producto->getPronombre();
                    $detalle = $producto->getProdetalle();
                    $stock = $producto->getProcantstock();
                    echo "
                        <div style='border: 3px solid black; margin: 3px; width: 20%'>
                        <p>$detalle</p>
                        <p>Stock: $stock<p>
                        <button onclick='comprar(\"$id\", \"$user_validado\")'>Comprar</button>
                        <button onclick='agregarAlCarrito(\"$id\", \"$user_validado\")'>Agregar al Carrito</button>
                        </div>
                    ";
                }
            ?>
            </div>
        </div>
        <script src="./js/index.js" async defer></script>
    </body>
</html>