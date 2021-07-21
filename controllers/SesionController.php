<?php
class AbmSesion{
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return object
     */
    public function cargarObjeto($param){
        $obj = null;
        
        if( array_key_exists('objUsuario',$param) and array_key_exists('listaRoles',$param)){
            $obj = new Sesion();
            $obj->setear($param['objUsuario'], $param['listaRoles']);
        }
        return $obj;
    }
    
    /**
     * Corrobora que esten seteados usnombre y uspass
     * @param array $param
     * @return boolean
     */
    
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['usnombre']) && isset($param['uspass']))
            $resp = true;
        
        return $resp;
    }
    
    /**
     * permite iniciar sesion con usnombre y uspass 
     * @param array $param
     */
    public function iniciar($param){
        $resp = false;

        if ( $this->seteadosCamposClaves($param) ) {
            $obj = new Sesion();
            $obj->iniciar($param['usnombre'], $param['uspass']);
            if ( $obj->validar() )
                $resp = true;
        }
        return $resp;
    }
    /**
     * permite cerrar sesion
     * @param array $param
     * @return boolean
     */
    public function cerrar(){
        $resp = false;
        $obj = new Sesion();
        if ( $obj->cerrar() )
            $resp = true;
        
        return $resp;
    }

    
}
?>
