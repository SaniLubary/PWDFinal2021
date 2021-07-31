<?php
class Producto {
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $cicantidad; // se setea cuando se realiza la busqueda en listarProductosDeCompra() para saber cuantos de cada producto agrego una persona a su carro
    private $idcompraitem; // se setea cuando se realiza la busqueda en listarProductosDeCompra() para saber que idcompraitem dar de baja cunado se quiere quitar un producto del carrito
    private $mensajeoperacion;
    
    public function getIdproducto()
    {
        return $this->idproducto;
    }

    public function setIdproducto($idproducto)
    {
        $this->idproducto = $idproducto;
    }

    public function getPronombre()
    {
        return $this->pronombre;
    }

    public function setPronombre($pronombre)
    {
        $this->pronombre = $pronombre;
    }

    public function setProdetalle($prodetalle)
    {
        $this->prodetalle = $prodetalle;
    }

    public function getProdetalle()
    {
        return $this->prodetalle;
    }

    public function setProcantstock($procantstock)
    {
        $this->procantstock = $procantstock;
    }

    public function getProcantstock()
    {
        return $this->procantstock;
    }

    public function setCicantidad($cicantidad)
    {
        $this->cicantidad = $cicantidad;
    }

    public function getCicantidad()
    {
        return $this->cicantidad;
    }

    public function setIdcompraitem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }

    public function getIdcompraitem()
    {
        return $this->idcompraitem;
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
         $this->idproducto="";
         $this->pronombre="" ;
         $this->prodetalle="" ;
         $this->procantstock="";
         $this->mensajeoperacion ="";
     }

     public function setear($idproducto, $pronombre, $prodetalle, $procantstock, $cicantidad=null, $idcompraitem=null)    {
        $this->setIdproducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
        $this->setCicantidad($cicantidad);
        $this->setIdcompraitem($idcompraitem);
    }
    
    
    public function cargar(){
        $base=new BaseDatos();
        $sql="SELECT * FROM producto WHERE idproducto = ".$this->getIdproducto();
        if (!$base->Iniciar()) {
            $this->setMensajeoperacion("Producto->cargar: ".$base->getError()[2]);
            return false;
        }
        
        if($base->Ejecutar($sql) > 0){
            $row = $base->Registro();
            $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']); 
        }

        return true;
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO Producto( pronombre, prodetalle, procantstock )  ";
        $sql.="VALUES('".$this->getPronombre()."', '".$this->getProdetalle()."', '".$this->getProcantstock()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdproducto($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Producto->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("Producto->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE Producto SET pronombre='".$this->getPronombre()."', prodetalle='".$this->getProdetalle()."', procantstock='".$this->getProcantstock()."'";
        $sql.= " WHERE idproducto = ".$this->getIdproducto();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("Producto->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Producto->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM producto WHERE idproducto =".$this->getIdproducto();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Producto->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Productoo->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM producto ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Producto();
                    $obj->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }

    // Sql que busca los productos para un carrito especifico, uniendo productos y compra, a compraitem, con columna de cantidad de productos seleccionados
    public static  function listarProductosDeCompra($idcompra){
        $arreglo = array();
        $base = new BaseDatos();

        // No se puede realizar una busqueda sin un id especifico
        if ($idcompra !== null) {
            $sql="SELECT c2.cicantidad, c2.idcompraitem , p.*
                from producto p 
                inner join compraitem c2  on c2.idproducto = p.idproducto 
                inner join compra c on c.idcompra = c2.idcompra 
                where c.idcompra = $idcompra
                group by p.idproducto";
    
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    
                    while ($row = $base->Registro()){
                        $obj = new Producto();
                        $obj->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['cicantidad'], $row['idcompraitem']); 
                        array_push($arreglo, $obj);
                    }
                    
                }
                
            } 
        } 
        
        return $arreglo;
    }
    
}
?>