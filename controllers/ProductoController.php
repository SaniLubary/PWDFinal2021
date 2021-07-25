<?php

class ProductoController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    public function cargarObjeto($param){
        if(array_key_exists('idproducto',$param) && array_key_exists('pronombre',$param) && array_key_exists('prodetalle',$param) && array_key_exists('procantstock',$param)){
            $obj = new Producto();
            $obj->setear($param['idproducto'], $param['pronombre'], $param['prodetalle'], $param['procantstock']);
            return $obj; 
        }
        return null;
    }
    
    /**
     * @param array Donde ['idproducto' => $idproducto]
     * @return object
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idproducto']) ){
            $obj = new Producto();
            $obj->setear($param['idproducto'],null, null, null);
            $obj->cargar();
        }
        return $obj;
    }
    
    
    /**
     * @param array Donde ['idproducto' => $idproducto]
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idproducto']))
            return false;
        
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){
        $producto = new Producto();
        $param['idproducto'] = null;
        $producto = $this->cargarObjeto($param);

        if (!$producto or !$producto->insertar()){
            return false;
        }
        
        return $producto;
    }
    
    /**
     * @param array idproducto a eliminar
     * @return boolean
     */
    public function baja($param){
        
        $resp = false;
        
        if ($this->seteadosCamposClaves($param)){
            $producto = $this->cargarObjetoConClave($param);
            
            if ($producto !== null and $producto->eliminar()){    
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
            
            $producto = $this->cargarObjeto($param);
            
            if($producto !== null and $producto->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return array
     */
    public function buscar($param = null){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idproducto']))
                $where.=" and idproducto='".$param['idproducto']."'";
            if  (isset($param['pronombre']))
                $where.=" and pronombre ='".$param['pronombre']."'";
            if  (isset($param['prodetalle']))
                $where.=" and prodetalle ='".$param['prodetalle']."'";
            if  (isset($param['procantstock']))
                $where.=" and procantstock ='".$param['procantstock']."'";
            if  (isset($param['usdeshabilitado']))
                $where.=" and usdeshabilitado ='".$param['usdeshabilitado']."'";
        }
        
        $producto = new Producto();
        $arreglo = $producto->listar($where);
        
        return $arreglo;
    }
}
?>