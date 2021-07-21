<?php
class Serviciotipo {
    private $idserviciotipo;
    private $stdescripcion;
    private $stactivo;
    private $mensajeoperacion;
    

    /**
     * @return int
     */
    public function getIdserviciotipo()
    {
        return $this->idserviciotipo;
    }

    /**
     * @param int $idserviciotipo
     */
    public function setIdserviciotipo($idserviciotipo)
    {
        $this->idserviciotipo = $idserviciotipo;
    }

    /**
     * @return string
     */
    public function getStdescripcion()
    {
        return $this->stdescripcion;
    }

    /**
     * @param string $stdescripcion
     */
    public function setStdescripcion($stdescripcion)
    {
        $this->stdescripcion = $stdescripcion;
    }

    /**
     * @param tinyint $stactivo
     */
    public function setStactivo($stactivo)
    {
        $this->stactivo = $stactivo;
    }

    /**
     * @return tinyint
     */
    public function getStactivo()
    {
        return $this->stactivo;
    }


    /**
     * @return string
     */
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * @param string $mensajeoperacion
     */
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __construct(){
         $this->idserviciotipo="";
         $this->stdescripcion="" ;
         $this->stactivo="" ;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idserviciotipo, $stdescripcion, $stactivo)    {
        $this->setIdserviciotipo($idserviciotipo);
        $this->setStdescripcion($stdescripcion);
        $this->setStactivo($stactivo);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM serviciotipo WHERE idserviciotipo = ".$this->getIdserviciotipo();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $this->setear($row['idserviciotipo'], $row['stdescripcion'], $row['stactivo']); 
                }
            }
        } else {
            $this->setMensajeoperacion("Serviciotipo->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO serviciotipo( stdescripcion, stactivo )  ";
        $sql.="VALUES('".$this->getStdescripcion()."', '".$this->getStactivo()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdserviciotipo($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Serviciotipo->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("Serviciotipo->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE serviciotipo SET stdescripcion='".$this->getStdescripcion()."', stactivo='".$this->getStactivo()."'";
        $sql.= " WHERE idserviciotipo = ".$this->getIdserviciotipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("Serviciotipo->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Serviciotipo->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM serviciotipo WHERE idserviciotipo =".$this->getIdserviciotipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Serviciotipo->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Serviciotipoo->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM serviciotipo ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Serviciotipo();
                    $obj->setear($row['idserviciotipo'], $row['stdescripcion'], $row['stactivo']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
}
?>