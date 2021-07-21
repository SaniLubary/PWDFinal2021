<?php
class ConcursogrupoetarioController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idgrupoetarios de las variables instancias del objeto
     * @param array $param
     * @return Concursogrupoetario
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idconcurso',$param) and array_key_exists('idgrupoetario',$param)) {
            $obj = new Concursogrupoetario();
            $obj->setear($param['idconcurso'], $param['idgrupoetario']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idgrupoetarios de las variables instancias del objeto que son claves
     * @param array $param
     * @return Concursogrupoetario
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idconcurso']) ){
            $obj = new Concursogrupoetario();
            $obj->setidconcurso($param['idconcurso']);
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
        if ( isset($param['idconcurso']) && isset($param['idgrupoetario']) )
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     * @return object $concursogrupoetario
     */
    public function alta($param){
        $resp = false;
        $concursogrupoetario = $this->cargarObjeto($param);
        if ($concursogrupoetario!=null and $concursogrupoetario->insertar()){
            $resp = $concursogrupoetario;
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
            $concursogrupoetario = $this->cargarObjetoConClave($param);
            if ($concursogrupoetario!=null and $concursogrupoetario->eliminar()){
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
            $concursogrupoetario = $this->cargarObjeto($param);
            if($concursogrupoetario!=null and $concursogrupoetario->modificar()){
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
     * @return Concursogrupoetario[]
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
        $arreglo = Concursogrupoetario::listar($where, $values);  
        return $arreglo;
    }
   
}