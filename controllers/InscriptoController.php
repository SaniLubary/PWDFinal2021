<?php
class InscriptoController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idpersonas de las variables instancias del objeto
     * @param array $param
     * @return Inscripto
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idinscripto',$param) and array_key_exists('idpersona',$param)) {
            $obj = new Inscripto();
            $obj->setear($param['idinscripto'], $param['idpersona']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idpersonas de las variables instancias del objeto que son claves
     * @param array $param
     * @return Inscripto
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idinscripto']) ){
            $obj = new Inscripto();
            $obj->setIdinscripto($param['idinscripto']);
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
        if (isset($param['idinscripto']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     * @return Inscripto|bool inscripto|false
     */
    public function alta($param){
        $resp = false;
        $persona = $param["personas"];
        $persona_controller = new PersonaController();
        $p = $persona_controller->alta($persona);
        if(isset($p)){
            // si es duplicado, se usa la persona existente para el alta del inscripto
            // si $p es int, es porqu ese le paso el id referencia de WL
            if ( is_int($p) ){
                $p = $persona_controller->buscar(['idreferencia' => $p])[0];
            }
            $param = Array();
            $param['idinscripto'] = null;
            $param['idpersona'] = $p->getIdpersona();
            $inscripto = $this->cargarObjeto($param);
            if ($inscripto!=null and $inscripto->insertar()){
                $resp = $inscripto;
            }
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
            $inscripto = $this->cargarObjetoConClave($param);
            if ($inscripto!=null and $inscripto->eliminar()){
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
            $inscripto = $this->cargarObjeto($param);
            if($inscripto!=null and $inscripto->modificar()){
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
     * @return Inscripto[]
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
        $arreglo = Inscripto::listar($where, $values);  
        return $arreglo;        
    }
   
}