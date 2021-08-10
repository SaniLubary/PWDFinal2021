<?php

class CompraItemController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return CompraItem
     */
    public function cargarObjeto($param){
        if(array_key_exists('idcompraitem',$param) && array_key_exists('producto',$param) && array_key_exists('compra',$param) && array_key_exists('cicantidad',$param)){
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], $param['producto'], $param['compra'], $param['cicantidad']);
            return $obj;
        }
        return null;
    }
    
    /**
     * @param array Donde ['idcompraitem' => $idcompraitem]
     * @return null|object<CompraItem>
     */
    private function cargarObjetoConClave($param){
        if( isset($param['idcompraitem']) ){
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'],null, null, null);
            if ($obj->cargar()) return $obj;
        }
        return null;
    }
    
    
    /**
     * @param array Donde ['idcompraitem' => $idcompraitem]
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idcompraitem']))
            return false;
        
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){
        $param['idcompraitem'] = null;
        $productoController = new ProductoController();
        $compraController = new compraController();
        
        $producto = $productoController->buscar(['idproducto' => $param['idproducto']]);
        $compra = $compraController->buscar(['idcompra' => $param['idcompra']]);
        
        if (!empty($producto[0]) && !empty($compra[0])) {
            $param['producto'] = $producto[0];
            $param['compra'] = $compra[0];
            $compraItem = $this->cargarObjeto($param);

            if (!$compraItem or !$compraItem->insertar()){
                return false;
            }
        }
        
        return $compraItem;
    }
    
    /**
     * @param array idcompraitem a eliminar
     * @return boolean
     */
    public function baja($param){
        $resp = false;
        
        if ($this->seteadosCamposClaves($param)){
            $compraItem = $this->cargarObjetoConClave($param);
            
            if ($compraItem !== null and $compraItem->eliminar()){    
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
            
            $compraItem = $this->cargarObjeto($param);
            
            if($compraItem !== null and $compraItem->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return array<CompraItem>
     */
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idcompraitem']))
                $where.=" and idcompraitem='".$param['idcompraitem']."'";
            if  (isset($param['idproducto']))
                $where.=" and idproducto ='".$param['idproducto']."'";
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['cicantidad']))
                $where.=" and cicantidad ='".$param['cicantidad']."'";
            if  (isset($param['usdeshabilitado']))
                $where.=" and usdeshabilitado ='".$param['usdeshabilitado']."'";
        }
        
        $arreglo = CompraItem::listar($where);
        
        return $arreglo;
    }
}
?>