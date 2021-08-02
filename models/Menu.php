<?php
class Menu {
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;
    private $mensajeoperacion;
    
    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    public function getMenombre()
    {
        return $this->menombre;
    }

    public function setMenombre($menombre)
    {
        $this->menombre = $menombre;
    }

    public function getMedescripcion()
    {
        return $this->medescripcion;
    }

    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;
    }

    public function getIdpadre()
    {
        return $this->idpadre;
    }

    public function setIdpadre($idpadre)
    {
        $this->idpadre = $idpadre;
    }

    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }

    public function setMedeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado = $medeshabilitado;
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
         $this->idmenu="";
         $this->menombre="" ;
         $this->medescripcion="";
         $this->idpadre="";
         $this->medeshabilitado = null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idmenu, $menombre,$medescripcion, $idpadre,$medeshabilitado)    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setIdpadre($idpadre);
        $this->setMedeshabilitado($medeshabilitado);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM menu WHERE idmenu = ".$this->getIdmenu();

        if (!$base->Iniciar()) {
            $this->setmensajeoperacion("Menu->cargar: ".$base->getError()[2]);
            return false;
        }
        
        if($base->Ejecutar($sql) > 0){
            $row = $base->Registro();
            $this->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$row['idpadre'],$row['medeshabilitado']); 
        }
        
        return true;       
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO menu( menombre ,  medescripcion ,  idpadre ,  medeshabilitado) 
            VALUES('".$this->getMenombre()."','".$this->getMedescripcion()."', '".$this->getIdpadre()."', '".$this->getMedeshabilitado()."') ";

        if (!$base->Iniciar()) {
            $this->setmensajeoperacion("Menu->insertar: ".$base->getError()[2]);
        }

        $elid = $base->Ejecutar($sql);
        if (!$elid) {
            $this->setmensajeoperacion("Menu->insertar: ".$base->getError()[2]);
            return false;
        } 

        $this->setIdmenu($elid);
        return true;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();

        $idpadre = $this->getIdpadre();
        if ( $idpadre !== ('' or null) && is_numeric($idpadre)) {
            $sql_idpadre = ", idpadre='$idpadre'";
        } else $sql_idpadre = '';
        
        $medeshabilitado = $this->getMedeshabilitado();
        if ( $medeshabilitado != "" and $medeshabilitado != null and $medeshabilitado != false) {
            $sql_medeshabilitado = ", medeshabilitado='$medeshabilitado'";
        } else $sql_medeshabilitado = ', medeshabilitado= NULL';
        
        $sql="UPDATE menu SET menombre='".$this->getMenombre()."',medescripcion='".$this->getMedescripcion()."'
            $sql_idpadre $sql_medeshabilitado ";
        $sql.= " WHERE idmenu = ".$this->getIdmenu();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setmensajeoperacion("Menu->modificar 1: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Menu->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM menu WHERE idmenu =".$this->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Menu->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Menu->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    /**
     * @param string $condicion condicional 'where'
     * @param int $menus_con_roles Si se desea buscar los menus que corresponden a un cierto rol
     */
    public static function listar($condicion="", $idrol = null){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu ";

        if ($condicion!="") {
            $sql .= 'WHERE '.$condicion;
        }

        if ($idrol != null && $idrol != false && is_numeric($idrol)) { // Se muestran los menus del idrol indicado
            $sql = "SELECT m.* from menu m 
                    inner join menurol m2 on m2.idmenu = m.idmenu 
                    inner join rol r on r.idrol = m2.idrol 
                    where m2.idrol = $idrol";
        }

        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Menu();
                    $obj->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$row['idpadre'], $row['medeshabilitado']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
    }
?>