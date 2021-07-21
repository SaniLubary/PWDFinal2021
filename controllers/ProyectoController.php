<?php
class ProyectoController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los tipos de las variables instancias del objeto
     * @param array $param
     * @return Proyecto
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idproyecto',$param) and array_key_exists('descripcion',$param) and array_key_exists('titulo', $param)) {
            $obj = new Proyecto();
            $obj->setear($param['idproyecto'], $param['titulo'], $param['descripcion']); 

        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los tipos de las variables instancias del objeto que son claves
     * @param array $param
     * @return Proyecto
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idproyecto']) ){
            $obj = new Proyecto();
            $obj->setIdproyecto($param['idproyecto']);
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
        if (isset($param['idproyecto']))
            $resp = true;
        return $resp;
    }
    
    /**
     * Da de alta un proyecto y los adjuntos relacionados al mismo
     * @param array $param
     * @return array Si se crea exitosamente, devuelve el objeto del proyecto y los de los adjuntos dados de alta
     */
    public function alta($param){
        $resp = null;
        $data['idproyecto'] = null;
        $data['descripcion'] = $param['descripcionProyecto'];
        $data['titulo'] = $param['tituloProyecto'];
        $proyecto = $this->cargarObjeto($data);
        if ($proyecto!=null and $proyecto->insertar()){
            // * Se inserta los adjuntos del proyecto
            $idproyecto = $proyecto->getIdproyecto();
            // Puede que los adjuntos no sean obligatorios
            if ($param['archivos'] == null) {
                return $proyecto;
            } else {
                //* Setear array con las direcciones que tendran los archivos en el servidor
                $arrPaths = array();

                // $target_path = $_SERVER['DOCUMENT_ROOT'] . "/projects/muni/_php_concursos/archivos/". $_SESSION['idconcurso'] ."/" . $idproyecto . "/";
                $target_path = $_SERVER['DOCUMENT_ROOT'] . "\\apps\\Concursos\\archivos\\" . $_SESSION['idconcurso'] ."\\" . $idproyecto . "\\";
                if (!file_exists($target_path)){
                    mkdir($target_path);
                };

                foreach ($param['archivos']['archivoProyecto']['name'] as $key => $value) {
                    if (!empty($value) && $value != "") {
                        $new_path = $target_path . basename($value);
                        $arrPaths[$key] = $new_path;
                    }
                }

                $adjunto_controller = new AdjuntoController();
                foreach ($arrPaths as $value) {
                    $data = ['idproyecto' => $idproyecto, 'pathAdjunto' => $value];
                    $adjunto = $adjunto_controller->alta($data);
                    if ($adjunto != null) {
                        $resp['adjuntos'][] = $adjunto;
                    } else return null;
                }
                $resp['proyecto'] = $proyecto;
                $resp['arrPaths'] = $arrPaths;
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
            $proyecto = $this->cargarObjetoConClave($param);
            if ($proyecto!=null and $proyecto->eliminar()){
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
            $proyecto = $this->cargarObjeto($param);
            if($proyecto!=null and $proyecto->modificar()){
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
     * @return Proyecto[]
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
        $arreglo = Proyecto::listar($where, $values);  
        return $arreglo;
    }
   
}