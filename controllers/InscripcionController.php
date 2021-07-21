<?php
class InscripcionController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Inscripcion
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idinscripcion',$param) || array_key_exists('idconcurso',$param) || array_key_exists('idusuario',$param) || 
        array_key_exists('idcategoria',$param) || array_key_exists('idgrupoetario',$param) || array_key_exists('idproyecto',$param) || 
        array_key_exists('idinscripto',$param) || array_key_exists('idgrupo',$param) ){
            $obj = new Inscripcion();
            
            $obj->setear($param['idinscripcion'], $param['idconcurso'],$param['idusuario'],$param['idcategoria'],$param['idgrupoetario'],$param['idproyecto'], $param['idinscripto'], $param['idgrupo'], $param['autorizacion']); 
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Inscripcion
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idinscripcion']) ){
            $obj = new Inscripcion();
            $obj->setIdinscripcion($param['idinscripcion']);
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
        // ? Suponemos que el concurso puede no tener grupo etario y/o que el proyecto
        if (isset($param['idinscripcion']) && isset($param['idconcurso']) && isset($param['idcategoria']) && isset($param['idusuario']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    public function alta($param){
        $resp = null;
        $param['idinscripcion'] =null;

        //* Se da de alta el proyecto
        $proyecto_controller = new ProyectoController();

        $respuestaProyecto = $proyecto_controller->alta($param);
        if ( $respuestaProyecto != null ){
            $resp = $respuestaProyecto;
            
            //* Se da de alta Inscripto/grupo
            if (isset($param['personas'])) {
                if (count($param['personas']) < 2) {
                    // si es un inscripto, idgrupo debe ser null, viceversa si son mas personas, idinscripto debera quedar null
                    $param['idgrupo'] = null;
                    $inscripto_controller = new InscriptoController();
                    $inscripto = $inscripto_controller->alta(array("personas" => $param['personas']));
                    if ($inscripto) {
                        $param['idinscripto'] = $inscripto->getIdinscripto();
                        $resp['inscripto'] = $inscripto;
                    } else {
                        return null; //! se deberia dar de baja el proyecto y adjuntos dados de alta previamente, si falla alta inscripto
                    }
                } elseif (count($param['personas']) > 1) {
                    $param['idinscripto'] = null;
                    $grupo_controller = new GrupoController();
                    $grupo = $grupo_controller->alta($param);
                    $param['idgrupo'] = $grupo->getIdgrupo();
                    $resp['grupo'] = $grupo;
                }
            } else {
                return null; //! se deberia dar de baja el proyecto y adjuntos dados de alta previamente, si falla alta grupo
            }
            
            //* Se da de alta la inscripcion
            $param['idproyecto'] = $respuestaProyecto['proyecto']->getIdproyecto();
            $inscripcion = $this->cargarObjeto($param);
            if ($inscripcion!=null and $inscripcion->insertar()){
                $resp['inscripcion'] = $inscripcion;
            } else $resp = null; //! se deberia dar de baja el proyecto, adjuntos e inscripto/grupo dados de alta previamente, si falla la inscripcion

        };
        
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
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla!=null and $elObjtTabla->eliminar()){
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
            if($elObjtMenu!=null and $elObjtMenu->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * permite buscar un objeto
     * @param array $param
     * @return array
     */
    /**
     * Permite realizar la busqueda de un objeto por multiples campos y si se especifica, con operadores
     * específicos.
     * @param array $param arreglo del tipo 'campo' => 'valor buscado' o vacio si se necesitan listar todos
     * @param array $ops arreglo opcional del tipo 'campo' => 'operador', por defecto el operador es '='
     * @return Inscripcion
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
        $arreglo = Inscripcion::listar($where, $values);  
        return $arreglo;
    }

    /**
     * permite realizar una inscripcion individual
     * 
     * @param array $param
     * @return boolean
     */
    public function altaInscripto($param){
        $resp = false;
        $usuario = new Usuario();
        // verif si el usuario es admin
        if ( $usuario->getIdPerfil() == 2 ){
            $inscripto = new InscriptoController();
            if ( $inscripto->alta($param) ) {
                $resp = true;
            }

        }
        
        return $resp;
    }

    /**
     * permite realizar una inscripcion grupal
     * @param array $param
     * @return boolean
     */
    public function altaGrupo($param){
        $resp = false;
        // cualquier usuario puede realizar esta operacion        
        $grupo = new GrupoController();
        if ( $grupo->alta($param) ) {
            $resp = true;
        }

        return $resp;
    }
   
}