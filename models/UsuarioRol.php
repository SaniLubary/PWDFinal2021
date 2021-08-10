<?php 


class UsuarioRol {
    private $usuario;
    private $rol;
    private $mensajeoperacion;
 
    public function __construct(){
         $this->usuario="";
         $this->rol="";
         $this->mensajeoperacion="";
    }

    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;
    }
   
    /**
     * @return model<Usuario>
    */
    public function getUsuario(){
        return $this->usuario;
    }
    public function setUsuario($valor){
        $this->usuario = $valor;
    }
   
    /**
     * @return model<Usuario>
    */
    public function getRol(){
        return $this->rol;
    }
    public function setRol($valor){
        $this->rol = $valor;
    }

    
    public function setear($usuario ,$rol)    {
        $this->setUsuario($usuario);
        $this->setRol($rol);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM usuariorol WHERE idusuario = ".$this->getUsuario()->getIdusuario();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();

                    $usController = new UsuarioController();
                    $usuario = $usController->buscar(['idusuario' => $row['idusuario']]);
                    if (!empty($usuario)) $usuario = $usuario[0];

                    $rolController = new RolController();
                    $rol = $rolController->buscar(['idrol' => $row['idrol']]);
                    if (!empty($rol)) $rol = $rol[0];
                    
                    $this->setear($usuario, $rol);
                }
            }
        } else {
            $this->setmensajeoperacion("Tabla->listar: ".$base->getError());
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO usuariorol(idusuario,idrol)  VALUES('".$this->getUsuario()->getIdusuario()."','".$this->getRol()->getIdrol()."');";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setmensajeoperacion("Tabla->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE usuariorol SET idusuario='".$this->getUsuario()->getIdusuario()."',idrol='".$this->getRol()->getIdrol()."' WHERE idusuario=".$this->getUsuario()->getIdusuario();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setmensajeoperacion("Tabla->modificar 1: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM usuariorol WHERE idusuario =".$this->getUsuario()->getIdusuario();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM usuariorol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj= new UsuarioRol();

                    $usController = new UsuarioController();
                    $usuario = $usController->buscar(['idusuario' => $row['idusuario']]);
                    if (!empty($usuario)) $usuario = $usuario[0];

                    $rolController = new RolController();
                    $rol = $rolController->buscar(['idrol' => $row['idrol']]);
                    if (!empty($rol)) $rol = $rol[0];
                    
                    $obj->setear($usuario, $rol);
                    array_push($arreglo, $obj);
                }
                
            }
            
        } else {
            $this->setmensajeoperacion("Tabla->listar: ".$base->getError());
        }
        
        return $arreglo;
    }
    
    
}

?>