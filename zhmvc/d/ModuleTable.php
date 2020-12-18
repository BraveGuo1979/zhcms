<?php
namespace ZHMVC\D;

class ModuleTable extends \ZHMVC\D\DataBase
{
    public function getRows()
    {
        return $this->_rows;
    }
    
    public function add($moduleid,$classtablename)
    {
        $sql="insert into ".$this->_pre."moduletable (moduleid,classname) values (:moduleid,:classname)";
        $bind=array(":moduleid"=>$moduleid,":classname"=>$classtablename);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function getAllModule()
    {
        $sql="select * from ".$this->_pre."moduletable";
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getAllModuleForMid($moduleid)
    {
        $sql="select * from ".$this->_pre."moduletable where moduleid=:moduleid";
        $bind=array(":moduleid"=>$moduleid);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
}