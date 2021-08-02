<?php
class CompraEstado {
    private $idcompraestado;
    private $idcompra;
    private $idcompraestadotipo;
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

    public function getIdcompra()
    {
        return $this->idcompra;
    }

    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }

    public function getIdcompraestadotipo()
    {
        return $this->idcompraestadotipo;
    }

    public function setIdcompraestadotipo($idcompraestadotipo)
    {
        $this->idcompraestadotipo = $idcompraestadotipo;
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
         $this->idcompra="" ;
         $this->idcompraestadotipo="";
         $this->cefechaini="";
         $this->cefechafin = null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idcompraestado, $idcompra, $idcompraestadotipo, $cefechaini, $cefechafin)    {
        $this->setIdcompraestado($idcompraestado);
        $this->setIdcompra($idcompra);
        $this->setIdcompraestadotipo($idcompraestadotipo);
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
            $this->setear($row['idcompraestado'], $row['idcompra'],$row['idcompraestadotipo'],$row['cefechaini'],$row['cefechafin']); 
        }
        
        return true;       
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO CompraEstado( idcompra ,  idcompraestadotipo ,  cefechafin) 
            VALUES('".$this->getIdcompra()."','".$this->getIdcompraestadotipo()."', '".$this->getCefechafin()."') ";

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
        $sql="UPDATE CompraEstado SET idcompra='".$this->getIdcompra()."',idcompraestadotipo='".$this->getIdcompraestadotipo()."',
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
            $sql .= 'ORDER BY idcompraestadotipo DESC';
        }
        
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new CompraEstado();
                    $obj->setear($row['idcompraestado'], $row['idcompra'],$row['idcompraestadotipo'], $row['cefechaini'],$row['cefechafin']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
}
?>