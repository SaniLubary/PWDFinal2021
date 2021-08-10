<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class CarritoController {
    
    /**
     * Devuelve los items del carrito de compras para todas las compras activas sin un estado (iniciada, cancelada, etc) de un usuario con sesion iniciada
     * @return array<Compra>(array<CompraItem>) Array de CompraItems por cada compra activa
     */
    function verCarrito() {
        // Sin usuario para buscar, devuelve vacio
        if (!array_key_exists('idusuario', $_SESSION))
            return array();

        // Check pedidos de compra en estado 'iniciada'
        $compraController = new CompraController();
        $compras = $compraController->buscarSinEstado($_SESSION['idusuario']);
        if (!empty($compras)) {
            // Check productos agregados al pedido de compra
            $carrito = [];
            foreach ($compras as $compra) {
                if ( $productos = $compraController->listarProductosDeCompra($compra->getIdcompra()) ) {
                    array_push($carrito, [ 'compra' => $compra, 'productos' => $productos]);
                }
            }
            return $carrito;
        }
    
        // No se encontraron compras activas
        return array();
    }
    
    /**
     * Agrega producto seleccionado al carro, crea nueva compra/carrito si no hay ninguna activa
     * @param int $idproducto
     * @param int $cicantidad La cantidad que se quiere comprar de un producto
     * @return bool 
     */
    function agregarAlCarrito($idproducto, $cicantidad) {
        $carrito = $this->verCarrito();
        // Si no se encontraron compras activas, crear una
        if (empty($carrito)) {
            $compraController = new CompraController();
            
            $sessionController = new SessionController();
            $usuario = $sessionController->getUsuario();
            
            if (!$compra = $compraController->alta(['usuario' => $usuario])) {
                $compra = null;
            }
        } else $compra = $carrito[0]['compra'];
        
        $compraItemController = new CompraItemController();

        if (!$compra)
            return false;

        // Buscar si ya hay un compraitem del producto, si hay, modificar cicantidad sumando la nueva cantidad
        if ( $compraitem_arr = $compraItemController->buscar(['idcompra' => $compra->getIdcompra(), 'idproducto' => $idproducto]) ) {
            $compraitem = $compraitem_arr[0];
            // Se setean los valores para modificar el compraitem
            // Se suman cicantidades
            $param = [];
            $param['idcompraitem'] = $compraitem->getIdcompraitem();
            $param['producto'] = $compraitem->getproducto()->getIdproducto();
            $param['compra'] = $compraitem->getcompra()->getIdcompra();
            $param['cicantidad'] = $compraitem->getCicantidad() + $cicantidad;
            
            if ( !$compraItemController->modificacion($param) ) {
                return false;
            }
            
        } else {
            // Si no hay compraitem del producto se lo agrega
            if ( !$compraItemController->alta(['idcompra' => $compra->getIdcompra(), 'idproducto' => $idproducto, 'cicantidad' => $cicantidad]) ) {
                return false;
            }
        }
        


        // Se agrego el producto
        return true;
    }

    /**
     * Quita producto seleccionado del carro
     * @param int $idproducto
     * @param int $cicantidad La cantidad que se quiere quitar de un producto
     * @param int $idcompra compra de la que se desea quitar del carro
     * @return bool 
     */
    function quitarDelCarrito($cicantidad, $idcompraitem) {
        $compras_activas = $this->verCarrito();

        // Se quita el item indicado
        $compraItemController = new CompraItemController();
        if (!$compraItemController->baja(['idcompraitem' => $idcompraitem, 'cicantidad' => $cicantidad])) {
            return false;
        }

        // Se quito correctamente
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

        $cetController = new CompraEstadoTipoController();
        $compraestadotipo = $cetController->buscar(['idcompraestadotipo' => 1]);
        
        if ($compra && !$compraEstadoController->alta(['idcompra' => $compra->getIdcompra(), 'compraestadotipo' => $compraestadotipo[0]])) {
            return false;
        }

        // Se efectuo la compra
        return true;
    }
}
