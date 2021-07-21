<?php
class Compra {
    private $idcompra;
    private $cofecha;
    private $idusuario;
    private $mensajeoperacion;
    
    public function getIdcompra()
    {
        return $this->idcompra;
    }

    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }

    public function getCofecha()
    {
        return $this->cofecha;
    }

    public function setCofecha($cofecha)
    {
        $this->cofecha = $cofecha;
    }

    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    public function getIdusuario()
    {
        return $this->idusuario;
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
         $this->idcompra="";
         $this->cofecha="" ;
         $this->idusuario="";
         $this->mensajeoperacion ="";
     }

     public function setear($idcompra, $cofecha, $idusuario) {
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setIdusuario($idusuario);
    }
    
    
    public function cargar(){
        $base=new BaseDatos();
        $sql="SELECT * FROM Compra WHERE idcompra = ".$this->getIdcompra();
        if (!$base->Iniciar()) {
            $this->setMensajeoperacion("CompraItem->cargar: ".$base->getError()[2]);
            return false;
        }
        
        if($base->Ejecutar($sql) > 0){
            $row = $base->Registro();
            $this->setear($row['idcompra'], $row['cofecha'], $row['idcompra'], $row['idusuario']); 
        }

        return true;
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO Compra( cofecha, idusuario ) ";
        $sql.="VALUES('".$this->getCofecha()."', '".$this->getIdusuario()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdcompra($elid);
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
        $sql="UPDATE Compra SET cofecha='".$this->getCofecha()."', idusuario='".$this->getIdusuario()."'";
        $sql.= " WHERE idcompra = ".$this->getIdcompra();
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
        $sql="DELETE FROM Compra WHERE idcompra =".$this->getIdcompra();
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
        $sql="SELECT * FROM Compra ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $base->Registro()){
                    $obj = new Compra();
                    $obj->setear($row['idcompra'], $row['cofecha'], $row['idcompra'], $row['idusuario']); 
                    array_push($arreglo, $obj);
                }
            }
        } 
        return $arreglo;
    }
}
?>