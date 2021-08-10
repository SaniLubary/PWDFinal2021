<?php
class Menu {
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $padre;
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

    public function getpadre()
    {
        return $this->padre;
    }

    public function setpadre($padre)
    {
        $this->padre = $padre;
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
         $this->padre="";
         $this->medeshabilitado = null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idmenu, $menombre,$medescripcion, $padre,$medeshabilitado)    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setpadre($padre);
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

            $menucontroller = new MenuController();
            $padre = $menucontroller->buscar(['idmenu' => $row['idmenu']]);
            if (!empty($padre)) $padre = $padre[0];
            
            $this->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$padre,$row['medeshabilitado']); 
        }
        
        return true;       
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO menu( menombre ,  medescripcion ,  padre ,  medeshabilitado) 
            VALUES('".$this->getMenombre()."','".$this->getMedescripcion()."', '".$this->getpadre()."', '".$this->getMedeshabilitado()."') ";

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

        $padre = $this->getpadre();
        if ( $padre !== ('' or null) && is_numeric($padre)) {
            $sql_idpadre = ", padre='$padre'";
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
    public static function listar($condicion=""){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu ";

        if ($condicion!="") {
            $sql .= 'WHERE '.$condicion;
        }

        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Menu();

                    $menucontroller = new MenuController();
                    $padre = $row['idpadre'] == null?:$menucontroller->buscar(['idmenu' => $row['idpadre']]);
                    if (!empty($padre)) $padre = $padre[0];    
                    
                    $obj->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$padre, $row['medeshabilitado']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
    }
?>