<?php

class CompraEstadoController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return CompraEstado
     */
    public function cargarObjeto($param){
        if(array_key_exists('idcompraestado',$param) && array_key_exists('compra',$param) && array_key_exists('compraestadotipo',$param) && array_key_exists('cefechaini',$param) && array_key_exists('cefechafin',$param)) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], $param['compra'], $param['compraestadotipo'], $param['cefechaini'], $param['cefechafin']);
            return $obj;
        }
        return null;
    }
    
    /**
     * @param array Donde ['idcompraestado' => $idcompraestado]
     * @return object
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idcompraestado']) ){
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'],null, null, null, null);
            $obj->cargar();
        }
        return $obj;
    }
    
    
    /**
     * @param array Donde ['idcompraestado' => $idcompraestado]
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idcompraestado']))
            return false;
        
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){
        $param['idcompraestado'] = null;

        if ( empty($param['compraestadotipo']) ) {
            $compraestadotipoController = new CompraEstadoTipoController();
            $compraestadotipo = $compraestadotipoController->buscar(['idcompraestadotipo' => 1]);
            $param['compraestadotipo'] = $compraestadotipo[0];
        }
        
        $compraController = new CompraController();
        $param['compra'] = $compraController->buscar(['idcompra' => $param['idcompra']])[0];
        
        $param['cefechaini'] = date("Y-m-d H:i:s");
        $param['cefechafin'] = null;
        $compraEstado = $this->cargarObjeto($param);

        if (!$compraEstado or !$compraEstado->insertar()){
            return false;
        }
        
        return $compraEstado;
    }
    
    /**
     * @param array idcompraestado a eliminar
     * @return boolean
     */
    public function baja($param){
        
        $resp = false;
        
        if ($this->seteadosCamposClaves($param)){
            $compraEstado = $this->cargarObjetoConClave($param);
            
            if ($compraEstado !== null && $compraEstado->eliminar()){    
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return boolean
     */
    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            
            $compraEstado = $this->cargarObjeto($param);
            
            if($compraEstado !== null && $compraEstado->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return array<CompraEstado>
     */
    public function buscar($param, $order_by_estado = false){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idcompraestado']))
                $where.=" and idcompraestado='".$param['idcompraestado']."'";
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['idcompraestadotipo']))
                $where.=" and idcompraestadotipo ='".$param['idcompraestadotipo']."'";
            if  (isset($param['cafechaini']))
                $where.=" and cafechaini ='".$param['cafechaini']."'";
            if  (isset($param['cafechafin']))
                $where.=" and cafechafin ='".$param['cafechafin']."'";
        }
        
        $arreglo = CompraEstado::listar($where, $order_by_estado);
        
        return $arreglo;
    }

    /**
     * Aumenta el estado de una compra, creando un estado nuevo y seteando fechafin para el estado anterior
     * @return bool True si se aumento el estado, False si no es posible aumentar estado (compra ya se encuentra en estado 3) 
     */
    public function aumentarEstado($idcompra) {
        // Se busca el ultimo estado, para actualizar cofechafin
        $estado_anterior = $this->buscar(['idcompra' => $idcompra], true);
        if (!empty($estado_anterior[0])) {
            $estado_anterior = $estado_anterior[0];
            // Si el ultimo estado de la compra es 3 (enviada), no se agrega un nuevo estado
            if ($estado_anterior->getCompraestadotipo()->getIdcompraestadotipo() === 3) {
                return false;
            } else {
                $estado_anterior->setCefechafin(date("Y-m-d H:i:s"));
                $estado_anterior->modificar();
            }

            $nuevo_idcompraestadotipo = $estado_anterior->getCompraestadotipo()->getIdcompraestadotipo()+1;
            
            $cetController = new compraEstadoTipoController();
            $nuevo_cet = $cetController->buscar(['idcompraestadotipo' => $nuevo_idcompraestadotipo]);
            $nuevo_cet = !empty($nuevo_cet[0])? $nuevo_cet[0] : $cetController->buscar([])[0];

            // Agregamos nuevo estado con id mayor
            $this->alta(['idcompra' => $idcompra, 'compraestadotipo' => $nuevo_cet]);
            return true;
        } else {
            // Agregamos estado inicial
            $this->alta(['idcompra' => $idcompra]);
            return true;
        }
    }

    /**
     * Crea un Estado de Tipo Cancelado para la Compra, actualiza fechafin del ultimo estado
     * @return bool
     */
    public function estadoCancelado($idcompra) {
        // Se busca el ultimo estado, para actualizar cofechafin
        $estado_anterior = $this->buscar(['idcompra' => $idcompra], true);
        if (!empty($estado_anterior[0])) {
            $estado_anterior = $estado_anterior[0];
            $estado_anterior->setCefechafin(date("Y-m-d H:i:s"));
            $estado_anterior->modificar();
        } 

        $cetController = new compraEstadoTipoController();
        $nuevo_cet = $cetController->buscar(['idcompraestadotipo' => 4]);
        $nuevo_cet = !empty($nuevo_cet[0])? $nuevo_cet[0] : $cetController->buscar([])[0];
        
        // Agregamos nuevo estado con id 4 (compra cancelada)
        if ( $this->alta(['idcompra' => $idcompra, 'compraestadotipo' => $nuevo_cet]) ) {
            return true;
        } else return false;
    }
}

?>
