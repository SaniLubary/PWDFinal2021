<?php

class CompraEstadoController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    public function cargarObjeto($param){
        if(array_key_exists('idcompraestado',$param) && array_key_exists('idcompra',$param) && array_key_exists('idcompraestadotipo',$param) && array_key_exists('cefechaini',$param) && array_key_exists('cefechafin',$param)) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], $param['idcompra'], $param['idcompraestadotipo'], $param['cefechaini'], $param['cefechafin']);
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
        $param['idcompraestadotipo'] = isset($param['idcompraestadotipo'])?$param['idcompraestadotipo']:1;
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
        if (!empty($estado_anterior)) {
            $estado_anterior = $estado_anterior[0];
            // Si el ultimo estado de la compra es 3 (enviada), no se agrega un nuevo estado
            if ($estado_anterior->getIdcompraestadotipo() === 3) {
                return false;
            } else {
                $estado_anterior->setCefechafin(date("Y-m-d H:i:s"));
                $estado_anterior->modificar();
            }

            // Agregamos nuevo estado con id mayor
            $this->alta(['idcompra' => $idcompra, 'idcompraestadotipo' => $estado_anterior->getIdcompraestadotipo()+1]);
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
        if (!empty($estado_anterior)) {
            $estado_anterior = $estado_anterior[0];
            $estado_anterior->setCefechafin(date("Y-m-d H:i:s"));
            $estado_anterior->modificar();
        } 

        // Agregamos nuevo estado con id 4 (compra cancelada)
        if ( $this->alta(['idcompra' => $idcompra, 'idcompraestadotipo' => 4]) ) {
            return true;
        } else return false;
    }
}

?>
