<?php
namespace ZHMVC\D;
class ModuleCommon extends \ZHMVC\D\DataBase
{
    
    public function getModuleCommon($moduletype)
    {
        $sql="select `id` , `modulename` , `moduletype` , `modulepath`  from `".$this->_pre."module_common` where `moduletype`=:moduletype";
        $bind=array(":moduletype"=>$moduletype);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getRows()
    {
        return $this->_rows;
    }
    
    public function add($modulename,$moduletype,$modulepath)
    {
        $sql="insert into ".$this->_pre."module_common (modulename,moduletype,modulepath) values (:modulename,:moduletype,:modulepath)";
        $bind=array(":modulename"=>$modulename,":moduletype"=>$moduletype,":modulepath"=>$modulepath);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
}
