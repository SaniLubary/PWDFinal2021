<?php
class CompraController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    private function cargarObjeto($param){
        $compra = null;
        if ( isset($param['idcompraestadotipo']) && isset($param['catdescripcion']) && isset($param['catdetalle'])) {
            $compra = new Compra();
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
     * @return boolean
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
        
        $compra= new Compra();
        $arr = $compra->listar($where, "");
        return $arr;
        
    }
}
?>