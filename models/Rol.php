<?php 

class Rol {
    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;
    
    public function getIdrol(){
        return $this->idrol;
    }
    public function setIdrol($valor){
        $this->idrol = $valor;
    }
    public function getRodescripcion(){
        return $this->rodescripcion;
    }
    public function setRodescripcion($valor){
        $this->rodescripcion = $valor;
    }
    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;
    }


    public function __construct(){
        $this->idrol="";
        $this->rodescripcion="";
        $this->mensajeoperacion="";
    }

    public function setear($idrol,$rodescripcion)    {
        $this->setIdrol($idrol);
        $this->setRodescripcion($rodescripcion);
    }
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM rol WHERE idrol = ".$this->getIdrol();
        if ($base->Iniciar()) {
            $resp = $base->Ejecutar($sql);
            if($resp>-1){
                if($resp>0){
                    $row = $base->Registro();
                    $this->setear($row['idrol'], $row['rodescripcion']) ;
                    
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
        $sql="INSERT INTO rol(rodescripcion)  VALUES('".$this->getRodescripcion()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdrol($elid);
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
        $sql="UPDATE rol SET rodescripcion='".$this->getRodescripcion()."' WHERE idrol=".$this->getIdrol();
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
        $sql="DELETE FROM rol WHERE idrol =".$this->getIdrol();
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
        $sql="SELECT * FROM rol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $resp = $base->Ejecutar($sql);
        if($resp>-1){
            if($resp>0){
                
                while ($row = $base->Registro()){
                    $obj= new Rol();
                    $obj->setear($row['idrol'], $row['rodescripcion']) ;
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