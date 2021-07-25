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
        $param['cefechaini'] = null;
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
     * @return boolean
     */
    public function buscar($param){
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
        
        $arreglo = compraEstado::listar($where);
        
        return $arreglo;
    }
}
?>