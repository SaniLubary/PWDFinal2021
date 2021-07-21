<?php
class GrupopersonaController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idpersonas de las variables instancias del objeto
     * @param array $param
     * @return Grupopersona
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idgrupo',$param) and array_key_exists('idpersona',$param)) {
            $obj = new Grupopersona();
            $obj->setear($param['idgrupo'], $param['idpersona']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idpersonas de las variables instancias del objeto que son claves
     * @param array $param
     * @return Grupopersona
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idgrupo']) ){
            $obj = new Grupopersona();
            $obj->setidgrupo($param['idgrupo']);
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
        if ( isset($param['idgrupo']) && isset($param['idpersona']) )
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     * @return object $grupopersona
     */
    public function alta($param){
        $resp = false;
        $param['idgrupo'] = null;
        $grupopersona = $this->cargarObjeto($param);
        if ($grupopersona!=null and $grupopersona->insertar()){
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
            $grupopersona = $this->cargarObjetoConClave($param);
            if ($grupopersona!=null and $grupopersona->eliminar()){
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
            $grupopersona = $this->cargarObjeto($param);
            if($grupopersona!=null and $grupopersona->modificar()){
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
     * @return Grupopersona[]
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
        $arreglo = Grupopersona::listar($where, $values);  
        return $arreglo;
    }
   
}