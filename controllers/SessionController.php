<?php
class SessionController {
    
    public function __construct(){
        if(!$this->activa())
            session_start();
    }

    /**
     * Si usuario y pass son correctos setea variables 
     *  de sesion para que el usuario quede validado
     */
    public function iniciar($usnombre, $uspass){
        $usuarioController = new UsuarioController();
        
        $where =['usnombre'=>$usnombre,'uspass'=>$uspass];

        if (!$usuario = $usuarioController->buscar($where)[0])
            return false;
            
        if(!$this->activa())
            return false;

        $_SESSION['idusuario']= $usuario->getIdUsuario();
        $_SESSION['usnombre']= $usuario->getUsNombre();
        $_SESSION['uspass']= $uspass;

        return true;
    }
    
    /**
     * Chequea si un usuario existe y tiene las credenciales correctas en la base
     */
    public function validar(){
        if(!isset($_SESSION['idusuario']) && !isset($_SESSION['usnombre']) && !isset($_SESSION['uspass']))
           return false;

        $usuarioController = new UsuarioController();

        if ($usuario_arr = $usuarioController->buscar($_SESSION))
            if (count($usuario_arr) < 1) return false;

        return true;
    }

    
    public function activa(){
        if(session_status() == PHP_SESSION_NONE){
            return false;
        }
        return true;
    }
    
    /**
     * Obtiene el usuario de la sesion activa
     */
    public function getUsuario(){
        if (!$this->validar()) return false;
        
        $usuarioController=new UsuarioController();
        
        $where = ['usnombre'=>$_SESSION['usnombre'],'idusuario'=>$_SESSION['idusuario']];
        $usuario_arr =$usuarioController->buscar($where);
        if (count($usuario_arr) < 1) return false;
        
        return $usuario_arr[0];
    }
    
    /**
     * Obtiene el rol del usuario activo
     */
    public function getRol(){
        $usuarioRolController =new UsuarioRolController();
        
        if ($usuario = $this->getUsuario()) {
            $usuarioRol_arr = $usuarioRolController->buscar(['idusuario' => $usuario->getIdusuario()]);
            if (count($usuarioRol_arr) > 0) 
                return $usuarioRol_arr[0]->getIdRol(); 
            }
        
        return false;
    }
    
    public function cerrar(){
        if(!session_destroy()){
            return false;
        }
        return true;
    }
} 