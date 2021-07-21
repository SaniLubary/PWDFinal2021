<?php
class CategoriaController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los tipos de las variables instancias del objeto
     * @param array $param
     * @return Categoria
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idcategoria',$param) and array_key_exists('tipo',$param)) {
            $obj = new Categoria();
            $obj->setear($param['idcategoria'], $param['tipo'], $param['descripcion'], $param['subcategoriade']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los tipos de las variables instancias del objeto que son claves
     * @param array $param
     * @return Categoria
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idcategoria']) ){
            $obj = new Categoria();
            $obj->setidcategoria($param['idcategoria']);
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
        if (isset($param['idcategoria']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    public function alta($param){
        $resp = null;
        $param['idcategoria'] = null;
        $categoria = $this->cargarObjeto($param);
        if ($categoria!=null and $categoria->insertar()){
            $resp = $categoria;
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
            $categoria = $this->cargarObjetoConClave($param);
            if ($categoria!=null and $categoria->eliminar()){
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
            $categoria = $this->cargarObjeto($param);
            if($categoria!=null and $categoria->modificar()){
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
     * @return Categoria[]
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
        $arreglo = Categoria::listar($where, $values);  
        return $arreglo;
    }
   
}