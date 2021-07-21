<?php
class GrupoController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombreorganizacions de las variables instancias del objeto
     * @param array $param
     * @return Grupo
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idgrupo',$param) and array_key_exists('nombreorganizacion',$param) and array_key_exists('email', $param) and array_key_exists('telefono', $param)){
            $obj = new Grupo();

            $obj->setear($param['idgrupo'], $param['nombreorganizacion'],$param['email'],$param['telefono']); 
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombreorganizacions de las variables instancias del objeto que son claves
     * @param array $param
     * @return Grupo
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idgrupo']) ){
            $obj = new Grupo();
            $obj->setIdgrupo($param['idgrupo']);
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
        if (isset($param['idgrupo']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $param['idgrupo'] = null;
        $arrPersonas = array();
        $idgrupo = null;
        //* se crea un grupo
        $grupo = $this->cargarObjeto($param);
        if ($grupo != null and $grupo->insertar()) {
            $idgrupo = $grupo->getIdgrupo();
            //* Una vez se tiene el id del grupo dar de alta las
            //*   personas que seran relacionadas en GRUPOPERSON
            $persona_controller = new PersonaController();
            $resp2 = true;
            foreach ($param['personas'] as $value) {
                $persona = $persona_controller->alta($value);
                if (!is_null($persona)) {
                    $arrPersonas[] = $persona;
                } else $resp2 = false;
            }
            //* si hubo un error en carga de personas, dar de baja personas antes cargada
            if ($resp2 == false) {
                foreach ($arrPersonas as $value) {
                    $persona_controller->baja($value);
                }
            } else {
                //* Sino, Relacionar personas con grupo 
                foreach ($arrPersonas as $value) {
                    $data = ['idgrupo' => $idgrupo, 'idpersona' => $value->getIdpersona()];
                    $grupopersona_controller = new GrupopersonaController();
                    if (!$grupopersona_controller->alta($data)) {
                        $resp = false;
                    }
                }
                $resp = $grupo;
            }
        }
        return $resp;
    }

    /**
     * Permite insertar un grupopersona desde model Grupo 
     * ! funcionalidad deprecada 
     * @param int $idgrupo 
     * @param Persona $param
     * @return boolean
     */
    public function altaGrupopersona($idgrupo, $param)
    {
        $resp = false;

        if (isset($idgrupo) && $param->getIdpersona() != null) {
            $grupo = $this->cargarObjetoConClave(array("idgrupo" => $idgrupo));
            if ($grupo != null and $grupo->insertarGrupopersona($param->getIdpersona())) {
                $resp = true;
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
            $grupo = $this->cargarObjetoConClave($param);
            if ($grupo!=null and $grupo->eliminar()){
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
            $grupo = $this->cargarObjeto($param);
            if($grupo!=null and $grupo->modificar()){
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
     * @return Grupo[]
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
        $arreglo = Grupo::listar($where, $values);  
        return $arreglo;
    }
   
}