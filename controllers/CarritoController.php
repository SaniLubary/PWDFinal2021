<?php

class CarritoController {
    
    /**
     * Devuelve los items del carrito de compras para todas las compras activas de un usuario con sesion iniciada
     * @return array<Compra>(array<CompraItem>) Array de CompraItems por cada compra activa
     */
    function verCarrito() {
        // Sin usuario para buscar, devuelve vacio
        if (!array_key_exists('idusuario', $_SESSION))
            return array();

        // Check pedidos de compra en estado 'iniciada'
        $compraController = new CompraController();
        if ($compras = $compraController->buscar(['idusuario' => $_SESSION['idusuario'], 'estado' => 1], true)) {
            // Check productos agregados al pedido de compra
            $compras_activas = [];
            foreach ($compras as $compra) {
                $compraItemController = new CompraItemController();
                if ($items = $compraItemController->buscar(['idcompra' => $compra->getIdcompra()])) {
                    array_push($compras_activas, [ 'compra' => $compra, 'items' => $items]);
                }
            }
            return $compras_activas;
        }
    
        return array();
    }
    
    /**
     * Agrega producto seleccionado al carro, crea nueva compra/carrito si no hay ninguna activa
     * @param int $idproducto
     * @param int $cicantidad La cantidad que se quiere comprar de un producto
     * @param int? $idcompra Carrito al que se agregara el producto
     * @return bool 
     */
    function agregarAlCarrito($idproducto, $cicantidad) {
        $compras_activas = $this->verCarrito();
        // Si no se encontraron compras activas, crear una
        if (count($compras_activas) === 0) {
            $compraController = new CompraController();
            $compraEstadoController = new CompraEstadoController();
            
            if (!$compra = $compraController->alta(['idusuario' => $_SESSION['idusuario']])) {
                $compra = null;
            }
        } else $compra = $compras_activas[0]['compra'];
        
        // Se agrega el item a la compra seleccionada
        $compraItemController = new CompraItemController();
        if (!$compra) {
            return false;
        } else if (!$compraItemController->alta(['idcompra' => $compra->getIdcompra(), 'idproducto' => $idproducto, 'cicantidad' => $cicantidad])) {
            return false;
        }

        // Todo ok
        return true;
    }

    /**
     * Finaliza la compra del carrito activo del usuario
     * @return bool 
     */
    function comprar() {
        $compras_activas = $this->verCarrito();

        // Si se encuentran pedidos de compra activas, se realiza el cambio de estado del ultimo pedido de compra a 'iniciada'
        if (count($compras_activas) === 0) {
            return false;
        }
        
        $compra = $compras_activas[0]['compra'];
        $compraEstadoController = new CompraEstadoController();
        if ($compra && !$compraEstadoController->alta(['idcompra' => $compra->getIdcompra(), 'idcompraestadotipo' => 1])) {
            return false;
        }

        // Todo ok
        return true;
    }
}
