<?php
class UsuarioRolController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    private function cargarObjeto($param){
        $usuarioRol = null;
        if ( array_key_exists('idrol',$param) && array_key_exists('idusuario',$param) ) {
            $usuarioRol = new UsuarioRol();
            $usuarioRol->setear($param['idusuario'], $param['idrol']);
        }
        
        return $usuarioRol;
    }

    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idusuario']) or !isset($param['idrol']));
            return false;
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){        
        $resp = false;
        $usuarioRol = $this->cargarObjeto($param);
        if ($usuarioRol!=null and $usuarioRol->insertar()){
            $resp = true;
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return boolean
     */
    
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $usuarioRol = $this->cargarObjeto($param);
            
            if ($usuarioRol !=null and $usuarioRol->eliminar()){
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
            
            $usuarioRol = $this->cargarObjeto($param);
            
            if($usuarioRol !=null and $usuarioRol->modificar()){
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
            if  (isset($param['idusuario']))
                $where.=" and idusuario='".$param['idusuario']."'";
            if  (isset($param['idrol']))
                $where.=" and idrol ='".$param['idrol']."'";
        }
        
        $usuarioRol= new  UsuarioRol();
        $arr = $usuarioRol->listar($where, "");
        return $arr;
        
    }
}
?>