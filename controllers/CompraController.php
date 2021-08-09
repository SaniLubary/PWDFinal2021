<?php
class CompraController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object<Compra>
     */
    private function cargarObjeto($param){
        $compra = null;
        if ( array_key_exists('idcompra',$param) and array_key_exists('cofecha', $param) and array_key_exists('usuario',$param) ) {
            $compra = new Compra();
            $param['cofecha'] = date("Y-m-d H:i:s");
            $compra->setear($param['idcompra'], $param['cofecha'], $param['usuario']);
        }
        
        return $compra;
    }

    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idcompra']));
            return false;
        return true;
    }
    
    /**
     * @param array $param
     * @return object<Compra> $compra
     */
    public function alta($param){        
        $param['idcompra'] = null;
        $param['cofecha'] = null;
        $compra = $this->cargarObjeto($param);
        
        if (!$compra!=null or !$compra->insertar()){
            return false;
        }
        
        return $compra;
    }
    
    /**
     * @param array $param
     * @return boolean
     */
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $compra = $this->cargarObjeto($param);
            
            if ($compra !=null and $compra->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return boolean
     */
    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            
            $compra = $this->cargarObjeto($param);
            
            if($compra !=null and $compra->modificar()){
                $resp = true;
                
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @param boolean $porEstado True si se quiere buscar por estado de tabla compraEstado
     * @return array<Compra>  
     */
    public function buscar($param, $porEstado = false){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario = '".$param['idusuario']."'";
            if  (isset($param['idcompra']))
                $where.=" and idcompra = '".$param['idcompra']."'";
            if  (isset($param['cofecha']))
                $where.=" and cofecha = '".$param['cofecha']."'";
            if  (isset($param['estado']))
                $where.=" and ce.idcompraestadotipo = '".$param['estado']."'";
        }
        
        $compra= new Compra();
        $compras = $compra->listar($where, $porEstado);
        
        return $compras;
    }

    /**
     * @param int $param
     * @return array<Producto>
     */
    public function listarProductosDeCompra($idcompra){
        
        $productos = Producto::listarProductosDeCompra($idcompra);
        
        return $productos;
    }
    
    /**
     * @param int $param
     * @return array<Compra>
     */
    public function buscarSinEstado($idusuario){
        
        $compra= new Compra();
        $compras = $compra->listarSinEstado($idusuario); 

        return $compras;
    }

    
}
?>