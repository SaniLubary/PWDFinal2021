<?php
class CompraEstado {
    private $idcompraestado;
    private $compra;
    private $compraestadotipo;
    private $cefechaini;
    private $cefechafin;
    private $mensajeoperacion;
    
    public function getIdcompraestado()
    {
        return $this->idcompraestado;
    }

    public function setIdcompraestado($idcompraestado)
    {
        $this->idcompraestado = $idcompraestado;
    }

    /**
     * @return model<Compra>
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * @param model<Compra>
     */
    public function setCompra($compra)
    {
        $this->compra = $compra;
    }

    /**
     * @return model<Compraestadotipo>
     */
    public function getCompraestadotipo()
    {
        return $this->compraestadotipo;
    }

    /**
     * @param model<Compraestadotipo>
     */
    public function setCompraestadotipo($compraestadotipo)
    {
        $this->compraestadotipo = $compraestadotipo;
    }

    public function getCefechaini()
    {
        return $this->cefechaini;
    }

    public function setCefechaini($cefechaini)
    {
        $this->cefechaini = $cefechaini;
    }

    public function getCefechafin()
    {
        return $this->cefechafin;
    }

    public function setCefechafin($cefechafin)
    {
        $this->cefechafin = $cefechafin;
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
         $this->idcompraestado="";
         $this->compra="" ;
         $this->compraestadotipo="";
         $this->cefechaini="";
         $this->cefechafin = null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idcompraestado, $compra, $compraestadotipo, $cefechaini, $cefechafin)    {
        $this->setIdcompraestado($idcompraestado);
        $this->setCompra($compra);
        $this->setCompraestadotipo($compraestadotipo);
        $this->setCefechaini($cefechaini);
        $this->setCefechafin($cefechafin);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM compraestado WHERE idcompraestado = ".$this->getIdcompraestado();

        if (!$base->Iniciar()) {
            $this->setmensajeoperacion("CompraEstado->cargar: ".$base->getError()[2]);
            return false;
        }
        
        if($base->Ejecutar($sql) > 0){
            $row = $base->Registro();
            
            $compra = Compra::listar(' true and idcompra = ' . $row['idcompra']);
            if (!empty($compra)) $compra = $compra[0];
            
            $compraestadotipo = CompraEstadoTipo::listar(' true and idcompraestadotipo = ' . $row['idcompraestadotipo']);
            if (!empty($compraestadotipo)) $compraestadotipo = $compraestadotipo[0];
            
            $this->setear($row['idcompraestado'],$compra,$compraestadotipo,$row['cefechaini'],$row['cefechafin']); 
        }
        
        return true;       
    }
    
    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql="INSERT INTO CompraEstado( idcompra ,  idcompraestadotipo ,  cefechafin) 
            VALUES('".$this->getCompra()->getIdcompra()."','".$this->getCompraestadotipo()->getIdcompraestadotipo()."', '".$this->getCefechafin()."') ";

        if (!$base->Iniciar()) {
            $this->setmensajeoperacion("CompraEstado->insertar: ".$base->getError()[2]);
        }

        $elid = $base->Ejecutar($sql);
        if (!$elid) {
            $this->setmensajeoperacion("CompraEstado->insertar: ".$base->getError()[2]);
            return false;
        } 

        $this->setIdcompraestado($elid);
        return true;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE CompraEstado SET idcompra='".$this->getCompra()->getIdcompra()."',idcompraestadotipo='".$this->getCompraestadotipo()->getIdcompraestadotipo()."',
            cefechaini='".$this->getCefechaini()."', cefechafin='".$this->getCefechafin()."' ";
        $sql.= " WHERE idcompraestado = ".$this->getIdcompraestado();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setmensajeoperacion("CompraEstado->modificar 1: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("CompraEstado->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM compraestado WHERE idcompraestado =".$this->getIdcompraestado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("CompraEstado->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("CompraEstado->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static function listar($condicion="", $order_by_estado = false){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM compraestado ";

        if ($condicion!="") {
            $sql.='WHERE '.$condicion;
        }

        if ($order_by_estado) {
            $sql .= ' ORDER BY idcompraestadotipo DESC';
        }
        
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new CompraEstado();

                    $compra = Compra::listar(' true and idcompra = ' . $row['idcompra']);
                    if (!empty($compra)) $compra = $compra[0];
                    
                    $compraestadotipo = CompraEstadoTipo::listar(' true and idcompraestadotipo = ' . $row['idcompraestadotipo']);
                    if (!empty($compraestadotipo)) $compraestadotipo = $compraestadotipo[0];
                   
                    $obj->setear($row['idcompraestado'],$compra,$compraestadotipo, $row['cefechaini'],$row['cefechafin']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
}
?>