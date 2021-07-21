<?php
class Serviciovaloracion {
    private $idserviciovaloracion;
    private $idservicio;
    private $svnumero;
    private $svcomentario;
    private $mensajeoperacion;
    

    /**
     * @return int
     */
    public function getIdserviciovaloracion()
    {
        return $this->idserviciovaloracion;
    }

    /**
     * @param int $idserviciovaloracion
     */
    public function setIdserviciovaloracion($idserviciovaloracion)
    {
        $this->idserviciovaloracion = $idserviciovaloracion;
    }

    /**
     * @return string
     */
    public function getIdservicio()
    {
        return $this->idservicio;
    }

    /**
     * @param string $idservicio
     */
    public function setIdservicio($idservicio)
    {
        $this->idservicio = $idservicio;
    }

    /**
     * @param tinyint $svnumero
     */
    public function setSvnumero($svnumero)
    {
        $this->svnumero = $svnumero;
    }

    /**
     * @return tinyint
     */
    public function getSvnumero()
    {
        return $this->svnumero;
    }

        /**
     * @param tinyint $svnumero
     */
    public function setSvcomentario($svcomentario)
    {
        $this->svcomentario = $svcomentario;
    }

    /**
     * @return tinyint
     */
    public function getSvcomentario()
    {
        return $this->svcomentario;
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
         $this->idserviciovaloracion="";
         $this->idservicio="" ;
         $this->svnumero="" ;
         $this->svcomentario="";
         $this->mensajeoperacion ="";
        
     }

     public function setear($idserviciovaloracion, $idservicio, $svnumero, $svcomentario)    {
        $this->setIdserviciovaloracion($idserviciovaloracion);
        $this->setIdservicio($idservicio);
        $this->setSvnumero($svnumero);
        $this->setSvcomentario($svcomentario);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM serviciovaloracion WHERE idserviciovaloracion = ".$this->getIdserviciovaloracion();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $this->setear($row['idserviciovaloracion'], $row['idservicio'], $row['svnumero'], $row['svcomentario']); 
                }
            }
        } else {
            $this->setMensajeoperacion("Serviciovaloracion->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO serviciovaloracion( idservicio, svnumero, svcomentario )  ";
        $sql.="VALUES('".$this->getIdservicio()."', '".$this->getSvnumero()."', '".$this->getSvcomentario()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdserviciovaloracion($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Serviciovaloracion->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("Serviciovaloracion->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE serviciovaloracion SET idservicio='".$this->getIdservicio()."', svnumero='".$this->getSvnumero()."'";
        $sql.= " WHERE idserviciovaloracion = ".$this->getIdserviciovaloracion();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("Serviciovaloracion->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Serviciovaloracion->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM serviciovaloracion WHERE idserviciovaloracion =".$this->getIdserviciovaloracion();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Serviciovaloracion->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Serviciovaloraciono->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM serviciovaloracion ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Serviciovaloracion();
                    $obj->setear($row['idserviciovaloracion'], $row['idservicio'], $row['svnumero'], $row['svcomentario']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }

    // la funcion te permite realizar la busqueda eligiendo tambien por que columna ordenar el top, que cantidad traer, y si es ascendente o descendentes
    public static function listarTopComentarios($where="", $cantidad="10", $orderby="idserviciovaloracion",$ascDesc="ASC"){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM serviciovaloracion ";
        if ($where!="") {
            $sql.='WHERE '.$where;
        }
        $sql.=" ORDER BY $orderby $ascDesc LIMIT $cantidad;";
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Serviciovaloracion();
                    $obj->setear($row['idserviciovaloracion'], $row['idservicio'], $row['svnumero'], $row['svcomentario']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        }
        return $arreglo;
    }
}
?>