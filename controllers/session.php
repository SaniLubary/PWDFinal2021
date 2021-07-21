<?php
class Session {
    
    public function __construct(){
        session_start();
    }

    /**
     * Si usuario y pass son correctos setea variables 
     *  de sesion para que el usuario quede validado
     */
    public function iniciar($usnombre, $uspass){
        $usuarioController = new UsuarioController();
        
        $where =['usnombre'=>$usnombre,'uspass'=>$uspass];
        if ($usuario = $usuarioController->buscar($where)[0]) {
            if($this->activa()){
                $_SESSION['idusuario']= $usuario->getIdUsuario();
                $_SESSION['usnombre']= $usuario->getUsNombre();
                $_SESSION['uspass']= $usuario->getUsNombre();
            }
        }

        return $_SESSION;
    }
    
    /**
     * Chequea si un usuario existe y tiene las credenciales correctas en la base
     */
    public function validar(){
        if(!isset($_SESSION['idusuario']) && !isset($_SESSION['usnombre']) && !isset($_SESSION['uspass']))
           return false;

        $usuarioController = new UsuarioController();

        if (!$usuarioController->buscar($_SESSION)[0])
            return false;

        return true;
    }

    
    public function activa(){
        if(!session_start()){
            return false;
        }
        return true;
    }
    
    /**
     * Obtiene el usuario de la sesion activa
     */
    public function getUsuario(){
        $usuarioController=new UsuarioController();
        
        $where =['usnombre'=>$_SESSION['usnombre'],'idusuario'=>$_SESSION['idusuario']];
        $usuario=$usuarioController->buscar($where)[0];

        return $usuario;
    }
    
    /**
     * Obtiene el rol del usuario activo
     */
    public function getRol(){
        $usuarioRolController =new UsuarioRolController();
        
        return $usuarioRolController->buscar(['idusuario' => $this->getUsuario()->getIdusuario])[0]; 
    }
    
    public function cerrar(){
        if(!session_destroy()){
            return false;
        }
        return true;
    }
} 