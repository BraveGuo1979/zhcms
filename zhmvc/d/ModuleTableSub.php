<?php
namespace ZHMVC\D;

class ModuleTableSub extends \ZHMVC\D\DataBase
{
    public function getRows()
    {
        return $this->_rows;
    }
    
    public function add($moduleid,$classtablename,$subname,$filename,$mapcontent)
    {
        $sql="insert into ".$this->_pre."moduletablesub (moduleid,classname,subname,filename,mapcontent) values (:moduleid,:classname,:subname,:filename,:mapcontent)";
        $bind=array(":moduleid"=>$moduleid,":classname"=>$classtablename,":subname"=>$subname,":filename"=>$filename,":mapcontent"=>$mapcontent);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function getAllModule()
    {
        $sql="select * from ".$this->_pre."moduletablesub";
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getAllModuleForMid($moduleid)
    {
        $sql="select * from ".$this->_pre."moduletablesub where moduleid=:moduleid";
        $bind=array(":moduleid"=>$moduleid);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
}