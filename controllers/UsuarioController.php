<?php

class UsuarioController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    public function cargarObjeto($param){
        if(array_key_exists('idusuario',$param) && array_key_exists('usnombre',$param) && array_key_exists('uspass',$param) && array_key_exists('usmail',$param)){
            $obj = new Usuario();
            $obj->setear($param['idusuario'], $param['usnombre'], $param['uspass'], $param['usmail'], null);
            return $obj;
        }
        return null;
    }
    
    /**
     * @param array Donde ['idusuario' => $idusuario]
     * @return object
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idusuario']) ){
            $obj = new Usuario();
            $obj->setear($param['idusuario'],null, null, null, null);
            $obj->cargar();
        }
        return $obj;
    }
    
    
    /**
     * @param array Donde ['idusuario' => $idusuario]
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idusuario']))
            return false;
        
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){
        $usuario = new Usuario();
        $param['idusuario'] = null;
        $usuario = $this->cargarObjeto($param);

        if (!$usuario or !$usuario->insertar()){
            return false;
        }
        
        return $usuario;
    }
    
    /**
     * @param array idusuario a eliminar
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
     * @return array<Usuario>
     */
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario='".$param['idusuario']."'";
            if  (isset($param['usnombre']))
                $where.=" and usnombre ='".$param['usnombre']."'";
            if  (isset($param['uspass']))
                $where.=" and uspass ='".$param['uspass']."'";
            if  (isset($param['usmail']))
                $where.=" and usmail ='".$param['usmail']."'";
            if  (isset($param['usdeshabilitado']))
                $where.=" and usdeshabilitado ='".$param['usdeshabilitado']."'";
        }
        
        $usuario = new Usuario();
        $arreglo = $usuario->listar($where);
        
        return $arreglo;
    }
}
?>