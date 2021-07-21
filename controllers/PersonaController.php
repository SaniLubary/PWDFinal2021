<?php
class PersonaController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Persona
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idpersona',$param) and array_key_exists('dni',$param)
        and array_key_exists('nombre', $param) and array_key_exists('apellido', $param) and array_key_exists('fechanacimiento', $param) 
        ){
            $obj = new Persona();
            $obj->setear($param['idpersona'], $param['nombre'], $param['apellido'], $param['dni'], $param['fechanacimiento'], $param['email'], $param['telefono'], $param['seudonimo'], $param['observaciones'], $param['urlpersonal'], $param['idreferencia'], $param['biografia']); 
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Persona
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idpersona']) ){
            $obj = new Persona();
            $obj->setIdpersona($param['idpersona']);
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
        if (isset($param['idpersona']) && isset($param['dni']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     * @return string 'existe' si la persona ya esta cargada
     * @return object Objeto persona que fue cargada
     * @return null Si hubo un error cargando el objeto antes de insertarlo
     */
    public function alta($param){
        $resp = null;
        if (isset($param[0])) {
            $param = $param[0];
        }
        $param['idpersona'] =null;
        $elObjtTabla = $this->cargarObjeto($param);
        if ($elObjtTabla!=null) {
            $insertar = $elObjtTabla->insertar();
            if ( $insertar === true ) {
                $resp = $elObjtTabla;
            } elseif ( is_int($insertar) ) {
                $resp = $insertar;
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
        if (isset($param[0])) {
            $param = $param[0];
        }
        if ($this->seteadosCamposClaves($param)){
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla!=null && $elObjtTabla->eliminar()){
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
            $elObjtMenu = $this->cargarObjeto($param);
            if($elObjtMenu!=null && $elObjtMenu->modificar()){
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
     * @return Persona[]
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
        $arreglo = Persona::listar($where, $values);  
        return $arreglo;
            
            
      
        
    }
   
}