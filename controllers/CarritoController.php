<?php

class CarritoController {
    
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
    
    /**
     * Agrega producto seleccionado al carro, crea nueva compra/carrito si no hay ninguna activa
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
    
            if ($compra && !$compraEstadoController->alta(['idcompra' => $compra->getIdcompra(), 'idcompraestadotipo' => 1])) {
                $compra = null;
            }
            
        } else $compra = $compras_activas[0];
        
        $compraItemController = new CompraItemController();
        if ($compra && $compraItemController->alta(['idcompra' => $compra->getIdcompra(), 'idproducto' => $idproducto, 'cicantidad' => $cicantidad])) {
            $_SESSION['agregado_al_carrito'] = true;
            return true;
        }
        
        // Si ocurrio un problema creando la nueva compra, o agregando el producto al carrito, se informa el error
        $_SESSION['error'] = 'Hubo un problema agregando el producto al carrito';
        // Se des-setea la cookie 'producto' para evitar un loop (si hubo un problema, redirije al index, si el index detecta la cookie 'producto', llama esta funcion)
        setcookie("producto", "", time()-10, "/");
        return false;
    }

}
