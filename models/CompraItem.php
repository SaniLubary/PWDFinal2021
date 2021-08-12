<?php

class CompraItem {
    private $idcompraitem;
    private $producto;
    private $compra;
    private $cicantidad;
    private $mensajeoperacion;
    
    public function getidcompraitem()
    {
        return $this->idcompraitem;
    }

    public function setidcompraitem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }

    public function getproducto()
    {
        return $this->producto;
    }

    public function setproducto($producto)
    {
        $this->producto = $producto;
    }

    public function setcompra($compra)
    {
        $this->compra = $compra;
    }

    public function getcompra()
    {
        return $this->compra;
    }

    public function setCicantidad($cicantidad)
    {
        $this->cicantidad = $cicantidad;
    }

    public function getCicantidad()
    {
        return $this->cicantidad;
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
         $this->idcompraitem="";
         $this->producto="" ;
         $this->compra="" ;
         $this->cicantidad="";
         $this->mensajeoperacion ="";
     }

     public function setear($idcompraitem, $producto, $compra, $cicantidad) {
        $this->setidcompraitem($idcompraitem);
        $this->setproducto($producto);
        $this->setcompra($compra);
        $this->setCicantidad($cicantidad);
    }
    
    
    public function cargar() {
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem WHERE idcompraitem = ".$this->getidcompraitem();
        
        if (!$base->Iniciar() or $base->Ejecutar($sql) < 1) {
            return false;
        }
        
        if ($row = $base->Registro()) {
            $compra = ( $row['idcompra'] == null ) ?: Compra::listar(' true and idcompra = ' . $row['idcompra']);
            if (!empty($compra)) $compra = $compra[0];  
            
            $producto = ( $row['idproducto'] == null ) ?: Producto::listar(' true and idproducto = ' . $row['idproducto']);
            if (!empty($producto)) $producto = $producto[0];  
            
            $this->setear($row['idcompraitem'], $producto, $compra, $row['cicantidad']); 

            return true;
        } else return false;        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO compraitem( idproducto, idcompra, cicantidad ) ";
        $sql.="VALUES('".$this->getproducto()->getidproducto()."', '".$this->getcompra()->getidcompra()."', '".$this->getCicantidad()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setidcompraitem($elid);
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
        $sql="UPDATE compraitem SET idproducto='".$this->getproducto()->getidproducto()."', 
            compra='".$this->getcompra()->getidcompra()."', cicantidad='".$this->getCicantidad()."'";
        $sql.= " WHERE idcompraitem = ".$this->getidcompraitem();
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
        $sql="DELETE FROM compraitem WHERE idcompraitem =".$this->getidcompraitem();
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
        $sql="SELECT * FROM compraitem ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $base->Registro()){
                    $obj = new compraItem();

                    $compra = ( $row['idcompra'] == null ) ?: Compra::listar(' true and idcompra = ' . $row['idcompra']);
                    if (!empty($compra)) $compra = $compra[0];  
                    
                    $producto = ( $row['idproducto'] == null ) ?: Producto::listar(' true and idproducto = ' . $row['idproducto']);
                    if (!empty($producto)) $producto = $producto[0];  
                    
                    $obj->setear($row['idcompraitem'], $producto, $compra, $row['cicantidad']); 
                    array_push($arreglo, $obj);
                }
            }
        } 
        return $arreglo;
    }
}
?>