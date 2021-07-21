<?php
class Servicioestado {
    private $idservicioestado;
    private $idservicio ;
    private $idservicioestadotipo;
    private $sefechainicio;
    private $sefechafin;
    private $secomentario;
    private $mensajeoperacion;
    

    /**
     * @return mixed
     */
    public function getIdservicioestado()
    {
        return $this->idservicioestado;
    }

    /**
     * @param mixed $idservicioestado
     */
    public function setIdservicioestado($idservicioestado)
    {
        $this->idservicioestado = $idservicioestado;
    }

    /**
     * @return mixed
     */
    public function getIdservicio()
    {
        return $this->idservicio;
    }

    /**
     * @param mixed $idservicio
     */
    public function setIdservicio($idservicio)
    {
        $this->idservicio = $idservicio;
    }

    /**
     * @return mixed
     */
    public function getIdservicioestadotipo()
    {
        return $this->idservicioestadotipo;
    }

    /**
     * @param mixed $idservicioestadotipo
     */
    public function setIdservicioestadotipo($idservicioestadotipo)
    {
        $this->idservicioestadotipo = $idservicioestadotipo;
    }

    /**
     * @return mixed
     */
    public function getSefechainicio()
    {
        return $this->sefechainicio;
    }

    /**
     * @param mixed $idservicioestadotipo
     */
    public function setSefechainicio($sefechainicio)
    {
        $this->sefechainicio = $sefechainicio;
    }

    /**
     * @return mixed
     */
    public function getSefechafin()
    {
        return $this->sefechafin;
    }

    /**
     * @param mixed $sefechafin
     */
    public function setSefechafin($sefechafin)
    {
        $this->sefechafin = $sefechafin;
    }

    /**
     * @return mixed
     */
    public function getSecomentario()
    {
        return $this->secomentario;
    }

    /**
     * @param mixed $sefechafin
     */
    public function setSecomentario($secomentario)
    {
        $this->secomentario = $secomentario;
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
         $this->idservicioestado="";
         $this->idservicio="" ;
         $this->idservicioestadotipo="";
         $this->sefechainicio="";
         $this->sefechafin = null;
         $this->secomentario = "";
         $this->mensajeoperacion ="";
        
     }

     public function setear($idservicioestado, $idservicio,$idservicioestadotipo,$sefechainicio,$sefechafin,$secomentario)    {
        $this->setIdservicioestado($idservicioestado);
        $this->setIdservicio($idservicio);
        $this->setIdservicioestadotipo($idservicioestadotipo);
        $this->setSefechainicio($sefechainicio);
        $this->setSecomentario($secomentario);
        $this->setSefechafin($sefechafin);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM servicioestado WHERE idservicioestado = ".$this->getIdservicioestado();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $this->setear($row['idservicioestado'], $row['idservicio'],$row['idservicioestadotipo'],$row['sefechainicio'],$row['sefechafin'],$row['secomentario']); 
                    
                }
            }
        } else {
            $this->setMensajeoperacion("Sercicioestado->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO servicioestado( idservicio ,  idservicioestadotipo ,  sefechainicio ,  sefechafin, secomentario)  ";
        $sql.="VALUES('".$this->getIdservicio()."','".$this->getIdservicioestadotipo()."','".$this->getSefechainicio()."','".$this->getSefechafin()."','".$this->getSecomentario()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdservicioestado($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Servicioestado->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("Servicioestado->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE servicioestado SET idservicio='".$this->getIdservicio()."',idservicioestadotipo='".
            $this->getIdservicioestadotipo()."',sefechainicio='".$this->getSefechainicio()."', sefechafin='".$this->getSefechafin."','".
            $this->getSecomentario()."'";
        $sql.= " WHERE idservicioestado = ".$this->getIdservicioestado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("Servicio->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Servicio->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM servicio WHERE idservicioestado =".$this->getIdservicioestado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Servicio->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Servicio->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM servicioestado ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Servicioestado();
                    $obj->setear($row['idservicioestado'], $row['idservicio'],$row['idservicioestadotipo'],$row['sefechainicio'],$row['sefechafin'],$row['secomentario']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    } 
    }
?>