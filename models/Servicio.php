<?php
class Servicio {
    private $idservicio;
    private $idserviciotipo ;
    private $serfechaingreso;
    private $serdescripcion;
    private $idusuario;
    private $mensajeoperacion;
    

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
    public function getIdserviciotipo()
    {
        return $this->idserviciotipo;
    }

    /**
     * @param mixed $idserviciotipo
     */
    public function setIdserviciotipo($idserviciotipo)
    {
        $this->idserviciotipo = $idserviciotipo;
    }

    /**
     * @return mixed
     */
    public function getSerfechaingreso()
    {
        return $this->serfechaingreso;
    }

    /**
     * @param mixed $serfechaingreso
     */
    public function setSerfechaingreso($serfechaingreso)
    {
        $this->serfechaingreso = $serfechaingreso;
    }

    /**
     * @return mixed
     */
    public function getSerdescripcion()
    {
        return $this->serdescripcion;
    }

    /**
     * @param mixed $serfechaingreso
     */
    public function setSerdescripcion($serdescripcion)
    {
        $this->serdescripcion = $serdescripcion;
    }

    /**
     * @return mixed
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * @param mixed $idusuario
     */
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
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
         $this->idservicio="";
         $this->idserviciotipo="" ;
         $this->serfechaingreso="";
         $this->serdescripcion="";
         $this->idusuario = null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idservicio, $idserviciotipo,$serfechaingreso,$serdescripcion,$idusuario)    {
        $this->setIdservicio($idservicio);
        $this->setIdserviciotipo($idserviciotipo);
        $this->setSerfechaingreso($serfechaingreso);
        $this->setSerdescripcion($serdescripcion);
        $this->setIdusuario($idusuario);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM servicio WHERE idservicio = ".$this->getIdservicio();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $this->setear($row['idservicio'], $row['idserviciotipo'],$row['serfechaingreso'],$row['serdescripcion'],$row['idusuario']); 
                    
                }
            }
        } else {
            $this->setMensajeoperacion("Menu->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO servicio( idserviciotipo ,  serfechaingreso ,  serdescripcion ,  idusuario)  ";
        $sql.="VALUES('".$this->getIdserviciotipo()."','".$this->getSerfechaingreso()."','".$this->getSerdescripcion()."','".$this->getIdusuario()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdservicio($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Servicio->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("Servicio->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE servicio SET idserviciotipo='".$this->getIdserviciotipo()."',serfechaingreso='".
            $this->getSerfechaingreso()."',serdescripcion='".$this->getSerdescripcion()."', idusuario='".$this->getIdusuario."'";
        $sql.= " WHERE idservicio = ".$this->getIdservicio();
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
        $sql="DELETE FROM servicio WHERE idservicio =".$this->getIdservicio();
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
        $sql="SELECT * FROM servicio ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Servicio();
                    $obj->setear($row['idservicio'], $row['idserviciotipo'],$row['serfechaingreso'],$row['serdescripcion'],$row['idusuario']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }    
    }
?>