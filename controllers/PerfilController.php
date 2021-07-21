<?php
class PerfilController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los tipos de las variables instancias del objeto
     * @param array $param
     * @return Perfil
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idperfil',$param) and array_key_exists('tipo',$param)) {
            $obj = new Perfil();
            $obj->setear($param['idperfil'], $param['tipo']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los tipos de las variables instancias del objeto que son claves
     * @param array $param
     * @return Perfil
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idperfil']) ){
            $obj = new Perfil();
            $obj->setIdperfil($param['idperfil']);
        }
        return $obj;
    }
    
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idperfil']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    public function alta($param){
        $resp = false;
        $param['idperfil'] = null;
        $perfil = $this->cargarObjeto($param);
        if ($perfil!=null and $perfil->insertar()){
            $resp = true;
        }
        return $resp;
        
    }
    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param){
        $resp = false;
      
        if ($this->seteadosCamposClaves($param)){
            $perfil = $this->cargarObjetoConClave($param);
            if ($perfil!=null and $perfil->eliminar()){
                $resp = true;
            }
        }
        
        return $resp;
    }
    
    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param){
       
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $perfil = $this->cargarObjeto($param);
            if($perfil!=null and $perfil->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite realizar la busqueda de un objeto por multiples campos y si se especifica, con operadores
     * específicos.
     * @param array $param arreglo del tipo 'campo' => 'valor buscado' o vacio si se necesitan listar todos
     * @param array $ops arreglo opcional del tipo 'campo' => 'operador', por defecto el operador es '='
     * @return Perfil[]
     */
    public function buscar($param = [], $ops = [])
    {
        $where = " 1=1 ";
        $values = array();
        foreach ($param as $key => $value) {
            $op = "=";
            if (isset($value)) {
                if (isset($ops[$key])) {
                    $op = $ops[$key];
                }
                $where .= " AND " . $key . $op . " ?";
                $values[] = $value;
            }
        }
        $arreglo = Perfil::listar($where, $values);  
        return $arreglo;
    }
   
}