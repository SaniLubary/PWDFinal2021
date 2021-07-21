<?php

class UsuarioController {
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Usuario
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idusuario',$param) and array_key_exists('nombre',$param) and array_key_exists('apellido', $param) 
        and array_key_exists('nrodocumento', $param) and array_key_exists('email', $param) 
        and array_key_exists('idusuario_weblogin', $param) and array_key_exists('perfil', $param)){

            $obj = new Usuario();
            $obj->setear($param['idusuario'], $param['nombre'],$param['apellido'],$param['nrodocumento'],$param['email'],$param['idusuario_weblogin'], $param['perfil']); 
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Usuario
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idusuario']) ){
            $obj = new Usuario();
            $obj->setIdusuario($param['idusuario']);
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
        if (isset($param['idusuario']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    public function alta($param){
        $resp = false;
        $param['idusuario'] =null;
        $usuario = $this->cargarObjeto($param);
        if ($usuario!=null and $usuario->insertar()){
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
            $usuario = $this->cargarObjetoConClave($param);
            if ($usuario!=null and $usuario->eliminar()){
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
            $usuario = $this->cargarObjeto($param);
            if($usuario!=null and $usuario->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * Permite realizar la busqueda de un objeto por multiples campos y si se especifica, con operadores
     * específicos.
     * @param array $param arreglo del tipo 'campo' => 'valor buscado'
     * @param array $ops arreglo opcional del tipo 'campo' => 'operador', por defecto el operador es '='
     * @return Usuario[] arreglo de Usuarios que coinciden con la busqueda
     */
    public function buscar($param, $ops = []){
        $where = " 1=1 ";
        $values = Array();
        foreach ($param as $key => $value) {
            $op = "=";
            if (isset($value)) {
                if(isset($ops[$key])){
                    $op = $ops[$key];
                }
                $where .= " AND " . $key . $op . " ?";
                $values[] = $value;
            }
        }
        $arreglo = Usuario::listar($where, $values);  
        return $arreglo;
    }

    /**
     * Devuelve un objeto del usuario cargado
     * 
     * @param int $param
     * @return object $usuario
     */
    public function cargarUsuario($param){
        $usuario = new Usuario();
        $usuario->setIdusuario($param);
        $usuario->cargar();
        return $usuario;
    }

    /**
     * permite al usuario crear un nuevo concurso si el usuario es admin
     * 
     * @param array $param
     * @return boolean
     */
    public function altaConcurso($param){
        $resp = false;
        $usuario = $this->cargarUsuario($param['idusuario']);
        // verif si el usuario es admin
        if ( $usuario->getIdperfil() == 2 ){
            $concursoController = new ConcursoController();
            if ( $concursoController->alta($param) ) {
                $resp = true;
            }

        }
        
        return $resp;
    }

    /**
     * Permite al usuario realizar una inscripcion individual/grupal
     * Cualquier usuario puede realizar esta operacion
     * Devuelve array de objetos/arrays utiles para mostrar datos o realizar otras operaciones (ej resultado del guardado de adjuntos en el servidor)
     * @param array Todos los datos necesarios para realizar el alta de una inscripcion y de sus subsecuentes tablas (inscripto/grupo/proyecto/adjunto/concursoCategoria/concursoGrupoEtario)
     * @return array objetos/arrays asosiativos sobre resultados de altas relacionadas a una inscripcion, o null si fallo el alta de inscripcion
     */
    public function altaInscripcion($param){
        $inscripcionController = new InscripcionController();
        $resp = $inscripcionController->alta($param);

        return $resp;
    }


   
}