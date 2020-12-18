<?php
namespace ZHMVC\D;

class ModuleInfo extends \ZHMVC\D\DataBase
{
    public function getModuleName($modulename)
    {
        $sql="select * from ".$this->_pre."module where modulename=:modulename";
        $bind=array(":modulename"=>$modulename);
        $data=$this->_db->getOne($sql,$bind);
        return $data;
    }
    
    public function getModuleNameSpace($modulenamespace)
    {
        $sql="select * from ".$this->_pre."module where modulenamespace=:modulenamespace";
        $bind=array(":modulenamespace"=>$modulenamespace);
        $data=$this->_db->getOne($sql,$bind);
        return $data;
    }
    
    public function getAllModule()
    {
        $sql="select * from ".$this->_pre."module";
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getRows()
    {
        return $this->_rows;
    }
}

