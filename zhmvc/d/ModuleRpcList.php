<?php
namespace ZHMVC\D;

class ModuleRpcList extends \ZHMVC\D\DataBase
{
    public function getRows()
    {
        return $this->_rows;
    }
    
    public function add($moduleid,$url)
    {
        $sql="insert into ".$this->_pre."modulerpclist (moduleid,url) values (:moduleid,:url)";
        $bind=array(":moduleid"=>$moduleid,":url"=>$url);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function getAll()
    {
        $sql="select * from ".$this->_pre."modulerpclist";
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getAllMid($moduleid)
    {
        $sql="select * from ".$this->_pre."modulerpclist where moduleid=:moduleid";
        $bind=array(":moduleid"=>$moduleid);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
}