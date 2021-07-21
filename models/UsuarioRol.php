<?php 


class UsuarioRol {
    private $idusuario;
    private $idrol;
    private $mensajeoperacion;
 
    public function __construct(){
         $this->idusuario="";
         $this->usnombre="";
         $this->mensajeoperacion="";
    }

    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
        
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;
        
    }
   
    /** --------------- */
    public function getIdusuario(){
        return $this->idusuario;
    }
    public function setIdusuario($valor){
        $this->idusuario = $valor;
    }
   
    /** --------------- */
    public function getIdRol(){
        return $this->idrol;
    }
    public function setIdRol($valor){
        $this->idrol = $valor;
    }

    

    public function setear($idusuario ,$idrol)    {
        $this->setIdusuario($idusuario);
        $this->setIdRol($idrol);
    
    }
    
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM usuariorol WHERE idusuario = ".$this->getIdusuario();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $this->setear($row['idusuario'], $row['idrol']) ;
                    
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
        $sql="INSERT INTO usuariorol(idusuario,idrol)  VALUES('".$this->getIdusuario()."','".$this->getIdRol()."');";
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
        $sql="UPDATE usuariorol SET idusuario='".$this->getIdusuario()."',idrol=".$this->getIdRol()."' WHERE id=".$this->getIdusuario();
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
        $sql="DELETE FROM usuariorol WHERE idusuario =".$this->getIdusuario();
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
    
    public  function listar($parametro=""){
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
                    $obj->setear($row['idusuario'], $row['idrol']) ;
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