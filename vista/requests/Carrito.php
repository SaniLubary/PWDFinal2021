<?php
/**
 * Devuelve los items del carrito de compras para todas las compras activas de un usuario con sesion iniciada
 * @return array<Compra>(array<CompraItem>) Array de CompraItems por cada compra activa
 */
function verCarrito() {
    // Check pedidos de compra en estado 'iniciada'
    $compraController = new CompraController();
    if ($compras = $compraController->buscar(['idusuario' => $_SESSION['idusuario'], 'estado' => 1], true)) {
        // Check productos agregados al pedido de compra
        $compras_activas = [];
        foreach ($compras as $compra) {
            $compraitemController = new CompraItemController();
            if ($compra_items = $compraitemController->buscar(['idcompra' => $compra->getIdcompra()])) {
                array_push($compras_activas, [ $compra->getIdcompra() => $compra_items]);
            }
        }
        return $compras_activas;
    }

    return array();
}

// Valida datos de inicio de sesion cuando se interactua con botones como 'comprar' o 'agregar al carrito'
// Agrega producto seleccionado al carro, crea nueva compra/carrito si no hay ninguna activa
if ($_POST && isset($_POST['producto']) && $_POST['producto'] !== (null || "") && is_numeric($_POST['producto'])) {
    $sessionController = new SessionController();
    if (!$sessionController->validar()) {
        redireccionarUltimaPagina();
        exit();
    }

    $compras_activas = verCarrito();
    $cicantidad = array_key_exists('cicantidad',$_POST) ? $_POST['cicantidad'] : 1;
    
    // Si no se encontraron compras activas, crear una
    if (count($compras_activas) === 0) {
        $compraController = new CompraController();
        $compraEstadoController = new CompraEstadoController();
        
        if (!$compra = $compraController->alta(['idusuario' => $_SESSION['idusuario']])) {
            $compra = null;
        }

        if ($compra && !$compraEstadoController->alta(['idcompra' => $compra->getIdcompra(), 'idcompraestadotipo' => 1])) {
            $compra = null;
        }
        
    } else $compra = $compras_activas[0];
    
    $compraItemController = new CompraItemController();
    if ($compraItemController->alta(['idcompra' => $compra->getIdcompra(), 'idproducto' => $_POST['producto'], 'cicantidad' => $_POST['cicantidad']])) {
        $_SESSION['agregado_al_carrito'] = true;
        redireccionarUltimaPagina();
        exit();
    }
    
    $_SESSION['error'] = 'Hubo un problema agregando el producto al carrito';
    redireccionarUltimaPagina();
    exit();
}
