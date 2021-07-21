<?php
class ConcursoController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Concurso
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idconcurso',$param) and array_key_exists('nombre',$param) and array_key_exists('anio', $param) 
                and array_key_exists('idusuario_creado_por', $param) and array_key_exists('idarea', $param) 
                and array_key_exists('vigente', $param) ){
            $obj = new Concurso();

            if(!isset($param['idusuario_modif_por'])){
                $param['idusuario_modif_por']=null;
            }

            $obj->setear($param['idconcurso'], $param['nombre'],$param['anio'],$param['idusuario_creado_por'],$param['idusuario_modif_por'],$param['idarea'],$param['vigente']); 
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Concurso
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idconcurso']) ){
            $obj = new Concurso();
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
        if (isset($param['idconcurso']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     * @return object $concurso
     */
    public function alta($param){
        $resp = false;
        $param['idconcurso'] =null;
        $concurso = $this->cargarObjeto($param);
        if ($concurso!=null and $concurso->insertar()){
            $resp = $concurso;
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
            $concurso = $this->cargarObjetoConClave($param);
            if ($concurso!=null and $concurso->eliminar()){
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
            $concurso = $this->cargarObjeto($param);
            if($concurso!=null and $concurso->modificar()){
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
     * @return Concurso[]
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
        $arreglo = Concurso::listar($where, $values);  
        return $arreglo;
    }
   
}