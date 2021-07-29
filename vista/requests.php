<?php 
// -------------
// Solo se deberia ingresar/usar este script si fue llamado con $_POST o $_GET
// -------------

include '../configuration.php';
$sessionController = new SessionController();

/**
 * Agrega productos al carro desde una llamada ajax
 */
if ($_GET && isset($_GET['idproducto']) && $_GET['idproducto'] !== (null || "") 
        && isset($_GET['cicantidad']) && $_GET['cicantidad'] !== (null || "") ) {

    $carritoController = new CarritoController();
    if (!$carritoController->agregarAlCarrito($_GET['idproducto'], $_GET['cicantidad'])) {
        // Si ocurrio un problema creando la nueva compra, o agregando el producto al carrito, se informa el error
        $_SESSION['error'] = 'Hubo un problema agregando el producto al carrito';
        setcookie("producto", "", time()-10, "/");
        setcookie("cicantidad", "", time()-10, "/");
        print json_encode(['response' => false]);
        exit();
    }

    $_SESSION['error'] = '';
    $_SESSION['agregado_al_carrito'] = $_GET['idproducto'];
    // Se elimina la cookie de producto, seteando su fecha de vencimiento en el pasado
    setcookie("producto", "", time()-10, "/");
    setcookie("cicantidad", "", time()-10, "/");
    print json_encode(['response' => true]);
    exit();
}

/**
 * Quita productos al del carro desde una llamada ajax
 */
if ($_GET && isset($_GET['quitar']) && $_GET['quitar'] === 'true' 
        && isset($_GET['idcompraitem']) && $_GET['idcompraitem'] !== (null || "") 
        && isset($_GET['cicantidad']) && $_GET['cicantidad'] !== (null || "") ) {

    $carritoController = new CarritoController();
    if (!$carritoController->quitarDelCarrito($_GET['cicantidad'], $_GET['idcompraitem'])) {
        // Si ocurrio un problema creando la nueva compra, o agregando el producto al carrito, se informa el error
        $_SESSION['error'] = 'Hubo un problema agregando el producto al carrito';
        setcookie("producto", "", time()-10, "/");
        print json_encode(['response' => false]);
        exit();
    }

    $_SESSION['error'] = '';
    $_SESSION['quitado_del_carrito'] = $_GET['idproducto'];
    // Se elimina la cookie de producto, seteando su fecha de vencimiento en el pasado
    setcookie("producto", "", time()-10, "/");
    setcookie("cicantidad", "", time()-10, "/");
    print json_encode(['response' => true]);
    exit();
}


/**
 * Finaliza la compra de los productos en el carrito
 */
if ($_GET && isset($_GET['comprar']) && $_GET['comprar'] === 'true') {
    $carritoController = new CarritoController();
    if (!$carritoController->comprar()) {
        $_SESSION['error'] = 'Se produjo un error completando su compra.';
        print json_encode(['response' => false]);
        exit();
    }
    
    $_SESSION['error'] = '';
    print json_encode(['response' => true]);
    exit();
}

/**
 * Valida datos de inicio de sesion, se llama cuando se intenta interactuar con elementos de la pagina que requieran de una sesion iniciada
 */
if ($_POST && isset($_POST['usnombre']) && isset($_POST['uspass']) && ($_POST['usnombre'] && $_POST['uspass']) != '') {
    if (!$sessionController->iniciar($_POST['usnombre'], $_POST['uspass'])) {
        $_SESSION['error'] = "Usuario o Contrase&ntilde;a Incorrectos!";
        header("Location: ./login.php");
        exit();
    }
    
    redireccionarUltimaPagina();
}

/**
 * Manejo de sesion
 */
if (isset($_GET['validar'])) {
    if ($_GET['validar'] === 'true'){
        // Valida session del usuario
        if ($sessionController->validar()) {
            print json_encode(['response' => true]);
            exit();
        }
    } elseif ($_GET['validar'] === 'false') {
        // Cierra session del usuario
        if ($sessionController->cerrar()){
            print json_encode(['response' => true]);
            exit();
        }
    }
        
    print json_encode(['response' => false]);
    exit();
}

print json_encode(['response' => false, 'mensaje' => 'Accion no especificada.']);
exit();
