<?php
class MenuRol {
    private $idmenu;
    private $idrol;
    private $mensajeoperacion;
    

    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    public function getIdrol()
    {
        return $this->idrol;
    }

    public function setIdrol($idrol)
    {
        $this->idrol = $idrol;
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
         $this->idmenu="";
         $this->idrol="" ;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idmenu, $idrol)    {
        $this->setIdmenu($idmenu);
        $this->setIdrol($idrol);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM menurol WHERE idmenu = ".$this->getIdmenu();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $this->setear($row['idmenu'], $row['idrol']); 
                }
            }
        } else {
            $this->setMensajeoperacion("MenuRol->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO MenuRol( idmenu, idrol )  ";
        $sql.="VALUES('".$this->getIdmenu()."', '".$this->getIdrol()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdmenu($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("MenuRol->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("MenuRol->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE MenuRol SET idmenu='".$this->getIdmenu()."', idrol='".$this->getIdrol()."'";
        $sql.= " WHERE idmenu = ".$this->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("MenuRol->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("MenuRol->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $base=new BaseDatos();
        $sql="DELETE FROM menurol WHERE idmenu =".$this->getIdmenu()." AND idrol =".$this->getIdrol();
        if (!$base->Iniciar()) {
            $this->setMensajeoperacion("MenuRol->eliminar: ".$base->getError());
            return false;
        }
        if (!$base->Ejecutar($sql)) {
            $this->setMensajeoperacion("MenuRol->eliminar: ".$base->getError());
            return false;
        }
        return true;
    }
    
    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM menurol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new MenuRol();
                    $obj->setear($row['idmenu'], $row['idrol']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        return $arreglo;
    }
}
?>