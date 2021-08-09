<?php

class UsuarioController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return <Usuario>
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
        if( isset($param['idusuario']) ){
            $obj = new Usuario();
            $obj->setear($param['idusuario'],null, null, null, null);
            if ($obj->cargar()) return $obj;
        }
        return null;
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
     * Se da de alta el usuario con rol default 'cliente'
     * Si se desea ingresar como administrador, debera crear un usuario con nombre 'admin' y automaticamente sera admin
     * @param array $param
     */
    public function alta($param){
        $return = false;
        $param['idusuario'] = null;
        $usuario = $this->cargarObjeto($param);

        if (!$usuario or !$usuario->insertar()){
            return false;
        }

        $usuarioRolController = new UsuarioRolController();

        // Se da rol 'cliente' (2) en cada nuevo registro automaticamente
        $rolController = new RolController();
        $rol = $rolController->buscar(['idrol' => 2]);
        
        if (!empty($rol[0]) && !$usuarioRolController->alta(['usuario' => $usuario, 'rol' => $rol[0]])) {
            // Si hay un error otorgando rol, se da de baja el usuario creado
            $this->baja(['idusuario' => $usuario->getIdusuario()]);
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
        $usuario = $this->cargarObjetoConClave($param);
        if ($usuario){

            if (isset($param['usnombre']) && $param['usnombre'] !== '') {
                $usuario->setUsnombre($param['usnombre']);
            }
            if (isset($param['usmail']) && $param['usmail'] !== '') {
                $usuario->setusmail($param['usmail']);
            }
            if (isset($param['uspass']) && $param['uspass'] !== '') {
                $usuario->setUspass($param['uspass']);
            }
            if (isset($param['usdeshabilitado']) && $param['usdeshabilitado'] !== '') {
                $usuario->setDeshabilitado($param['usdeshabilitado']);
            }
            
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