<?php
class CompraEstadoTipoController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    private function cargarObjeto($param){
        $compra = null;
        if ( isset($param['idcompraestadotipo']) && isset($param['catdescripcion']) && isset($param['catdetalle'])) {
            $compra = new CompraEstadoTipo();
            $compra->setear($param['idcompraestadotipo'], $param['catdescripcion'], $param['catdetalle']);
        }
        
        return $compra;
    }

    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        if (!isset($param['idcompraestadotipo']));
            return false;
        return true;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){        
        $resp = false;
        $param['idcompraestadotipo'] = null;
        $compra = $this->cargarObjeto($param);
        if ($compra!=null and $compra->insertar()){
            $resp = true;
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return boolean
     */
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $compraEstadoTipo = $this->cargarObjeto($param);
            
            if ($compraEstadoTipo !=null and $compraEstadoTipo->eliminar()){
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
            
            $compraEstadoTipo = $this->cargarObjeto($param);
            
            if($compraEstadoTipo !=null and $compraEstadoTipo->modificar()){
                $resp = true;
                
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return array<CompraEstadoTipo>
     */
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['catdetalle']))
                $where.=" and catdetalle='".$param['catdetalle']."'";
            if  (isset($param['idcompraestadotipo']))
                $where.=" and idcompraestadotipo ='".$param['idcompraestadotipo']."'";
            if  (isset($param['catdescripcion']))
                $where.=" and catdescripcion ='".$param['catdescripcion']."'";
        }
        
        $compraEstadoTipo = new CompraEstadoTipo();
        $arr = $compraEstadoTipo->listar($where, "");
        return $arr;
        
    }
}
?>