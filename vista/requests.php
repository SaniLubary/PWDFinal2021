<?php 
// -------------
// Solo se deberia ingresar/usar este script si fue llamado con $_POST o $_GET
// -------------

include '../configuration.php';
$sessionController = new SessionController();

/**
 * Aumenta el estado de una compra creando un nuevo estado con idestado mayor, y seteando fechafin en el estado anterior
 */
if ($_GET && isset($_GET['aumentar-estado']) && $_GET['aumentar-estado'] == "true" 
        && isset($_GET['idcompra']) && $_GET['idcompra'] != (null || "") ) {
    
    $compraEstadoController = new CompraEstadoController();
    if (!$compraEstadoController->aumentarEstado($_GET['idcompra'])) {
        print json_encode(['response' => false]);
        exit();
    }
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Cancela una compra creando un estado 'cancelado'
 */
if ($_GET && isset($_GET['cancelar-compra']) && $_GET['cancelar-compra'] == "true" 
        && isset($_GET['idcompra']) && $_GET['idcompra'] != (null || "") ) {
    
    $compraEstadoController = new CompraEstadoController();
    if (!$compraEstadoController->estadoCancelado($_GET['idcompra'])) {
        print json_encode(['response' => false]);
        exit();
    }
            
    print json_encode(['response' => true]);
    exit();
}


/**
 * Actualiza un elemento Menu
 */
if ($_GET && isset($_GET['update']) && $_GET['update'] == "true" 
        && isset($_GET['tabla']) && $_GET['tabla'] == "menu"
        && isset($_GET['idmenu']) && $_GET['idmenu'] != (null || "") 
        && isset($_GET['menombre']) && $_GET['menombre'] != (null || "") 
        && isset($_GET['medescripcion']) && $_GET['medescripcion'] != (null || "") 
        && isset($_GET['idpadre'])
        && isset($_GET['medeshabilitado']) ) {

    $menuController = new MenuController();
    $menuController->modificacion($_GET);
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Actualiza un elemento Menu
 */
if ($_GET && isset($_GET['update']) && $_GET['update'] == "true" 
        && isset($_GET['tabla']) && $_GET['tabla'] == "producto"
        && isset($_GET['idproducto']) && $_GET['idproducto'] != (null || "") 
        && isset($_GET['pronombre']) && $_GET['pronombre'] != (null || "") 
        && isset($_GET['prodetalle']) && $_GET['prodetalle'] != (null || "") 
        && isset($_GET['procantstock']) && $_GET['procantstock'] != (null || "") ) {

    $menuController = new ProductoController();
    if (!$menuController->modificacion($_GET)) {
        print json_encode(['response' => false]);
        exit();

    }
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Crea un elemento Menu
 */
if ($_GET && isset($_GET['create']) && $_GET['create'] == "true" 
        && isset($_GET['tabla']) && $_GET['tabla'] == "menu"
        && isset($_GET['menombre']) && $_GET['menombre'] != (null || "") 
        && isset($_GET['medescripcion']) && $_GET['medescripcion'] != (null || "") 
        && isset($_GET['idpadre'])
        && isset($_GET['medeshabilitado']) ) {

    $menuController = new MenuController();
    if (!$menuController->alta($_GET)) {
        print json_encode(['response' => false]);
        exit();
    }
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Crea un elemento Producto
 */
if ($_GET && isset($_GET['create']) && $_GET['create'] == "true" 
        && isset($_GET['tabla']) && $_GET['tabla'] == "producto"
        && isset($_GET['pronombre']) && $_GET['pronombre'] != (null || "") 
        && isset($_GET['procantstock']) && $_GET['procantstock'] != (null || "") && is_numeric($_GET['procantstock'])
        && isset($_GET['prodetalle']) && $_GET['prodetalle'] != (null || "") ) {

    $productoController = new ProductoController();
    if (!$productoController->alta($_GET)) {
        print json_encode(['response' => false]);
        exit();
    }
            
    print json_encode(['response' => true]);
    exit();
}


/**
 * Elimina un elemento Menu
 */
if ($_GET && isset($_GET['delete']) && $_GET['delete'] == "true" 
        && isset($_GET['idmenu']) && $_GET['idmenu'] != (null || "") ) {

    $menuController = new MenuController();
    $menuController->baja($_GET);
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Modifica el rol de un usuario
 */
if ($_GET && isset($_GET['setrol']) && $_GET['setrol'] == "true" 
        && isset($_GET['idusuario']) && $_GET['idusuario'] != (null || "")
        && isset($_GET['idrol']) && $_GET['idrol'] != (null || "") ) {

    $menuController = new UsuarioRolController();
    if (!$menuController->modificacion($_GET)) {
        print json_encode(['response' => false]);
        exit();
    }
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Elimina un elemento Producto
 */
if ($_GET && isset($_GET['delete']) && $_GET['delete'] == "true" 
        && isset($_GET['idproducto']) && $_GET['idproducto'] != (null || "") ) {

    try {
        $menuController = new ProductoController();
        $menuController->baja($_GET);
    } catch (Exception $e) {
        if ($e->errorInfo[1] === 1451) {
            print json_encode(['response' => '1451']);
            exit();
        }
    }
            
    print json_encode(['response' => true]);
    exit();
}

/**
 * Agrega productos al carro
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
 * Quita productos al del carro
 */
if ($_GET && isset($_GET['quitar']) && $_GET['quitar'] === 'true' 
        && isset($_GET['idcompraitem']) && $_GET['idcompraitem'] !== (null || "") && is_numeric($_GET['idcompraitem']) 
        && isset($_GET['cicantidad']) && $_GET['cicantidad'] !== (null || "")  && is_numeric($_GET['cicantidad']) ) {

    $carritoController = new CarritoController();
    if (!$carritoController->quitarDelCarrito($_GET['cicantidad'], $_GET['idcompraitem'])) {
        // Si ocurrio un problema creando la nueva compra, o agregando el producto al carrito, se informa el error
        $_SESSION['error'] = 'Hubo un problema quitando el producto del carrito';
        setcookie("producto", "", time()-10, "/");
        print json_encode(['response' => false]);
        exit();
    }

    $_SESSION['error'] = '';
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
