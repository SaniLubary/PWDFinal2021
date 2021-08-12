<?php
class Compra {
    private $idcompra;
    private $cofecha;
    private $usuario;
    private $mensajeoperacion;
    
    public function getIdcompra()
    {
        return $this->idcompra;
    }

    public function setIdcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }

    public function getCofecha()
    {
        return $this->cofecha;
    }

    public function setCofecha($cofecha)
    {
        $this->cofecha = $cofecha;
    }

    /**
     * @param model<Usuario>
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
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
         $this->idcompra="";
         $this->cofecha="" ;
         $this->usuario="";
         $this->mensajeoperacion ="";
     }

     public function setear($idcompra, $cofecha, $usuario) {
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setUsuario($usuario);
    }
    
    
    public function cargar(){
        $base=new BaseDatos();
        $sql="SELECT * FROM Compra WHERE idcompra = ".$this->getIdcompra();
        if (!$base->Iniciar()) {
            $this->setMensajeoperacion("CompraItem->cargar: ".$base->getError()[2]);
            return false;
        }
        
        if($base->Ejecutar($sql) > 0){
            $row = $base->Registro();

            $usuario = Usuario::listar(' true and idusuario = ' . $row['idusuario']);
            if (!empty($usuario)) $usuario = $usuario[0];

            $this->setear($row['idcompra'], $row['cofecha'], $row['idcompra'], $usuario); 
        }

        return true;
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO Compra( cofecha, idusuario ) ";
        $sql.="VALUES('".$this->getCofecha()."', '".$this->getUsuario()->getIdusuario()."');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdcompra($elid);
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
        $sql="UPDATE Compra SET cofecha='".$this->getCofecha()."', usuario='".$this->getUsuario()->getIdusuario()."'";
        $sql.= " WHERE idcompra = ".$this->getIdcompra();
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
        $sql="DELETE FROM Compra WHERE idcompra =".$this->getIdcompra();
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
    
    public static  function listar($parametro="", $porEstado = false){
        $arreglo = array();
        $base = new BaseDatos();

        $sql_base = "SELECT * FROM Compra ";
        $sql_por_estado = "SELECT * FROM compra c INNER JOIN compraestado ce ON ce.idcompra = c.idcompra ";
        $sql = $porEstado ? $sql_por_estado : $sql_base;

        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        
        // para seleccionar el ultimo carrito que se haya creado, en caso de que se cree uno nuevo por alguna razon (???) seleccione el ultimo que se modifico
        $sql .= ' ORDER BY idcompra DESC';

        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $base->Registro()){
                    $obj = new Compra();

                    $usController = new UsuarioController();
                    $usuario = $usController->buscar(['idusuario' => $row['idusuario']]);
                    if (!empty($usuario)) $usuario = $usuario[0];

                    $obj->setear($row['idcompra'], $row['cofecha'], $usuario); 
                    array_push($arreglo, $obj);
                }
            }
        } 
        return $arreglo;
    }

    public static  function listarSinEstado($idusuario){
        $arreglo = array();
        $base = new BaseDatos();

        $sql = "SELECT c.*, ce.idcompraestado FROM Compra c 
        left JOIN compraestado ce ON ce.idcompra = c.idcompra  
        where ce.idcompraestado is null and idusuario =  $idusuario
        ORDER BY c.idcompra DESC";
       
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $base->Registro()){
                    $obj = new Compra();

                    $usuario = Usuario::listar(' true and idusuario = ' . $row['idusuario']);
                    if (!empty($usuario)) $usuario = $usuario[0];

                    $obj->setear($row['idcompra'], $row['cofecha'], $usuario); 
                    array_push($arreglo, $obj);
                }
            }
        } 
        return $arreglo;
    }
}
?>