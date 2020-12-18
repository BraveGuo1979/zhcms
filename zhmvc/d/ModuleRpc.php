<?php
namespace ZHMVC\D;

class ModuleRpc extends \ZHMVC\D\DataBase
{
    public function getRows()
    {
        return $this->_rows;
    }
    
    public function add($moduleid,$name,$mainkey,$mainurl)
    {
        $sql="insert into ".$this->_pre."modulerpc (moduleid,name,mainkey,mainurl) values (:moduleid,:name,:mainkey,:mainurl)";
        $bind=array(":moduleid"=>$moduleid,":name"=>$name,":mainkey"=>$mainkey,":mainurl"=>$mainurl);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function getAll()
    {
        $sql="select * from ".$this->_pre."modulerpc";
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getAllMid($moduleid)
    {
        $sql="select * from ".$this->_pre."modulerpc where moduleid=:moduleid";
        $bind=array(":moduleid"=>$moduleid);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getAllMainKey($mainkey)
    {
        $sql="select * from ".$this->_pre."modulerpc where mainkey=:mainkey";
        $bind=array(":mainkey"=>$mainkey);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
}