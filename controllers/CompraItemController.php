<?php

class CompraItemController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    public function cargarObjeto($param){
        if(array_key_exists('idcompraitem',$param) && array_key_exists('idproducto',$param) && array_key_exists('idcompra',$param) && array_key_exists('cicantidad',$param)){
            $obj = new CompraItem();
            return $obj->setear($param['idcompraitem'], $param['idproducto'], $param['idcompra'], $param['cicantidad'], null);
        }
        return null;
    }
    
    /**
     * @param array Donde ['idcompraitem' => $idcompraitem]
     * @return object
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idcompraitem']) ){
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'],null, null, null, null);
            $obj->cargar();
        }
        return $obj;
    }
    
    
    /**
     * @param array Donde ['idcompraitem' => $idcompraitem]
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idcompraitem']))
            return false;
        
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){
        $usuario = new CompraItem();
        $param['idcompraitem'] = null;
        $usuario = $this->cargarObjeto($param);

        if (!$usuario or !$usuario->insertar()){
            return false;
        }
        
        return $usuario;
    }
    
    /**
     * @param array idcompraitem a eliminar
     * @return boolean
     */
    public function baja($param){
        
        $resp = false;
        
        if ($this->seteadosCamposClaves($param)){
            $usuario = $this->cargarObjetoConClave($param);
            
            if ($usuario !== null and $usuario->eliminar()){    
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
            
            $usuario = $this->cargarObjeto($param);
            
            if($usuario !== null and $usuario->modificar()){
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
            if  (isset($param['idcompraitem']))
                $where.=" and idcompraitem='".$param['idcompraitem']."'";
            if  (isset($param['idproducto']))
                $where.=" and idproducto ='".$param['idproducto']."'";
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['cicantidad']))
                $where.=" and cicantidad ='".$param['cicantidad']."'";
            if  (isset($param['usdeshabilitado']))
                $where.=" and usdeshabilitado ='".$param['usdeshabilitado']."'";
        }
        
        $arreglo = Usuario::listar($where);
        
        return $arreglo;
    }
}
?>