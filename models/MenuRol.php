<?php
class MenuRol {
    private $menu;
    private $rol;
    private $mensajeoperacion;
    

    public function getmenu()
    {
        return $this->menu;
    }

    public function setmenu($menu)
    {
        $this->menu = $menu;
    }

    public function getrol()
    {
        return $this->rol;
    }

    public function setrol($rol)
    {
        $this->rol = $rol;
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
         $this->menu="";
         $this->rol="" ;
         $this->mensajeoperacion ="";
        
     }

     public function setear($menu, $rol)    {
        $this->setmenu($menu);
        $this->setrol($rol);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM menurol WHERE idmenu = '".$this->getmenu()->getIdmenu()."' OR idrol = '".$this->getrol()->getIdrol()."'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();

                    $rol = Rol::listar('true and idrol = '. $row['idrol']);
                    if (!empty($rol)) $rol = $rol[0];

                    $menu = Menu::listar('true and idmenu = ' . $row['idmenu']);
                    if (!empty($menu)) $menu = $menu[0];
                    
                    $this->setear($menu, $row); 
                }
            }
        } else {
            $this->setMensajeoperacion("MenuRol->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO MenuRol( idmenu, idrol )  ";
        $sql.="VALUES('".$this->getmenu()->getIdmenu()."', '".$this->getrol()->getIdmenu()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setmenu($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("MenuRol->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setMensajeoperacion("MenuRol->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;

        $base = new BaseDatos();
        $sql = "UPDATE MenuRol SET idmenu='".$this->getmenu()->getIdmenu()."', idrol='".$this->getrol()->getIdrol()."'";
        $sql .=  " WHERE idmenu = ".$this->getmenu()->getIdmenu()."' AND idrol = '".$this->getrol()->getIdrol()."'";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setMensajeoperacion("MenuRol->modificar 1: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("MenuRol->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $base=new BaseDatos();
        $sql="DELETE FROM menurol WHERE idmenu =".$this->getmenu()->getIdmenu()." AND idrol =".$this->getrol()->getIdrol();
        if (!$base->Iniciar()) {
            $this->setMensajeoperacion("MenuRol->eliminar: ".$base->getError());
            return false;
        }
        if (!$base->Ejecutar($sql)) {
            $this->setMensajeoperacion("MenuRol->eliminar: ".$base->getError());
            return false;
        }
        return true;
    }
    
    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM menurol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new MenuRol();

                    $rol = Rol::listar('true and idrol = ' . $row['idrol']);
                    if (!empty($rol)) $rol = $rol[0];

                    $menu = Menu::listar('true and idmenu = ' . $row['idmenu']);
                    if (!empty($menu)) $menu = $menu[0];
                    
                    $obj->setear($menu, $rol); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        return $arreglo;
    }
}
?>