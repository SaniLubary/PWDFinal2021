<?php
class CompraEstadoTipo {
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeoperacion;
    
    public function getIdcompraestadotipo()
    {
        return $this->idcompraestadotipo;
    }

    public function setIdcompraestadotipo($idcompraestadotipo)
    {
        $this->idcompraestadotipo = $idcompraestadotipo;
    }

    public function getCetdescripcion()
    {
        return $this->cetdescripcion;
    }

    public function setCetdescripcion($cetdescripcion)
    {
        $this->cetdescripcion = $cetdescripcion;
    }

    public function setCetdetalle($cetdetalle)
    {
        $this->cetdetalle = $cetdetalle;
    }

    public function getCetdetalle()
    {
        return $this->cetdetalle;
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
         $this->idcompraestadotipo="";
         $this->cetdescripcion="" ;
         $this->cetdetalle="";
         $this->mensajeoperacion ="";
     }

     public function setear($idcompraestadotipo, $cetdescripcion, $cetdetalle) {
        $this->setIdcompraestadotipo($idcompraestadotipo);
        $this->setCetdescripcion($cetdescripcion);
        $this->setCetdetalle($cetdetalle);
    }
    
    
    public function cargar(){
        $base=new BaseDatos();
        $sql="SELECT * FROM compraestadotipo WHERE idcompraestadotipo = ".$this->getIdcompraestadotipo();
        if (!$base->Iniciar()) {
            $this->setMensajeoperacion("CompraItem->cargar: ".$base->getError()[2]);
            return false;
        }
        
        if($base->Ejecutar($sql) > 0){
            $row = $base->Registro();
            $this->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['idcompra'], $row['cetdetalle']); 
        }

        return true;
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO CompraEstadoTipo( cetdescripcion, cetdetalle ) ";
        $sql.="VALUES('".$this->getCetdescripcion()."', '".$this->getCetdetalle()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdcompraestadotipo($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("CompraItem->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("CompraItem->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE CompraEstadoTipo SET cetdescripcion='".$this->getCetdescripcion()."', cetdetalle='".$this->getCetdetalle()."'";
        $sql.= " WHERE idcompraestadotipo = ".$this->getIdcompraestadotipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("CompraItem->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("CompraItem->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM compraestadotipo WHERE idcompraestadotipo =".$this->getIdcompraestadotipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("CompraItem->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Productoo->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM compraestadotipo ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $base->Registro()){
                    $obj = new CompraEstadoTipo();
                    $obj->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['idcompra'], $row['cetdetalle']); 
                    array_push($arreglo, $obj);
                }
            }
        } 
        return $arreglo;
    }
}
?>