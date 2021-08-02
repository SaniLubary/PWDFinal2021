<?php
class RolController {
    /**
     * @param array Donde ['nombre-columna' => 'valor']
     * @return object
     */
    private function cargarObjeto($param){
        $obj = null;
        
        if( array_key_exists('idrol',$param) and array_key_exists('rodescripcion',$param)){
            $obj = new Rol();
            $obj->setear($param['idrol'], $param['rodescripcion']);
        }
        return $obj;
    }
    
    /**
     * @param array $param
     * @return object
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idrol']) ){
            $obj = new Rol();
            $obj->setear($param['idrol'], null);
        }
        return $obj;
    }
    
    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idrol']))
            $resp = true;
            return $resp;
    }
    
    /**
     * @param array $param
     */
    public function alta($param){
        $resp = false;
        $param['idrol'] =null;
        $rol = $this->cargarObjeto($param);
        if ($rol!=null and $rol->insertar()){
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
            $rol = $this->cargarObjetoConClave($param);
            if ($rol!=null and $rol->eliminar()){
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
            $rol = $this->cargarObjeto($param);
            if($rol!=null and $rol->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * @param array $param
     * @return array<Rol>
     */
    public function buscar($param){
        $rol = new Rol();
        $where = " true ";
        if ($param){
            if  (isset($param['idrol']))
                $where.=" and idrol =".$param['idrol'];
            if  (isset($param['rodescripcion']))
                $where.=" and rodescripcion ='".$param['rodescripcion']."'";
        }
        
        $arreglo = $rol->listar($where);
        return $arreglo;
    }
    
}
?>
