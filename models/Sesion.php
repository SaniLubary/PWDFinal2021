<?php
class Sesion{
    private $objUsuario;
    private $listaRoles;
    private $mensajeoperacion;
    
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    public function getListaRoles()
    {
        return $this->listaRoles;
    }

    public function setListaRoles($listaRoles)
    {
        $this->listaRoles = $listaRoles;
    }

    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __construct(){
        if(session_start()){
            $this->objUsuario = null;
            $this->listaRoles = [];
            $this->mensajeoperacion = "";
        }
    }

    public function setear( $objUsuario, $listaRoles )    {
        $this->setObjUsuario($objUsuario);
        $this->setListaRoles($listaRoles);
    }
    
    public function validar(){
        $inicia = false;
        if( isset($_SESSION['idusuario']) ){
           $inicia = true;
        }
        return $inicia;
    }
    public function iniciar($usnombre, $uspass){
        $abmUsuario = new AbmUsuario();
        
        $where = [ 'usnombre' => $usnombre ];
        $arr_usuario = $abmUsuario->buscar($where);
        if ( count($arr_usuario) >= 1 ) {
            $obj = $arr_usuario[0];
            if (password_verify( $uspass, $obj->getUspass())){
                $_SESSION['idusuario'] = $obj->getIdusuario();  
                $_SESSION['usnombre']  = $obj->getUsnombre();
            }
        }  
        return $_SESSION;
    }
    
    public function activa(){
        $activa=false;
        if(session_start()){
            $activa=true;
        }
        return $activa;
    }
    
    public function getUsuario(){
        $usuario = null;
        $abmUsuario = new AbmUsuario();
        $where = ['usnombre'=>$_SESSION['usnombre'], 'idusuario'=>$_SESSION['idusuario']];
        $listaUsuarios = $abmUsuario->buscar($where);
        if($listaUsuarios >= 1){
            $usuario = $listaUsuarios[0];
        }
        return $usuario;
    }
    
    public function getRol(){
        $rol = null;
        $abmUsuarioRol = new AbmUsuarioRol();
        
        $usuario = $this->getUsuario();
        $idUsuario = $usuario->getIdusuario();
        $param = ['idusuario' => $idUsuario];

        $arr_RolesUsuario = $abmUsuarioRol->buscar($param);
        if( $arr_RolesUsuario>1 ){
            $rol = $arr_RolesUsuario;
        }else{
            $rol = $arr_RolesUsuario[0];
        }
        return $rol; 
    }
    
    public function cerrar(){
        $cerrar = false;
        if( session_destroy() ){
            $cerrar = true;
        }
        return $cerrar;
    }
} 