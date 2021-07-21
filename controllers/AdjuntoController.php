<?php
class AdjuntoController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idproyectos de las variables instancias del objeto
     * @param array $param
     * @return Adjunto
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idadjunto',$param) and array_key_exists('idproyecto',$param)  and array_key_exists('pathAdjunto',$param) ) {
            $obj = new Adjunto();
            $obj->setear($param['idadjunto'], $param['idproyecto'], $param['pathAdjunto']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los idproyectos de las variables instancias del objeto que son claves
     * @param array $param
     * @return Adjunto
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idadjunto']) ){
            $obj = new Adjunto();
            $obj->setIdadjunto($param['idadjunto']);
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
        if (isset($param['idadjunto']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     * @return object $resp
     */
    public function alta($param){
        $resp = null;
        $param['idadjunto'] = null;
        $adjunto = $this->cargarObjeto($param);
        if ($adjunto!=null and $adjunto->insertar()){
            $resp = $adjunto;
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
            $adjunto = $this->cargarObjetoConClave($param);
            if ($adjunto!=null and $adjunto->eliminar()){
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
            $Adjunto = $this->cargarObjeto($param);
            if($Adjunto!=null and $Adjunto->modificar()){
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
     * @return Adjunto[]
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
        $arreglo = Adjunto::listar($where, $values);  
        return $arreglo;        
    }
   
}