<?php
namespace ZHMVC\D;

class PlusCommon extends \ZHMVC\D\DataBase
{
    public function getPlusCommon($plustype)
    {
        $sql="select `id` , `plusname` , `plustype` , `pluspath` , `plusrootpath`  from `".$this->_pre."plus_common` where `plustype`=:plustype";
        $bind=array(":plustype"=>$plustype);
        $data=$this->_db->getAll($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getPlusName($plusname)
    {
        $sql="select `id` , `plusname` , `plustype` , `pluspath` , `plusrootpath`  from `".$this->_pre."plus_common` where `plusname`=:plusname";
        $bind=array(":plusname"=>$plusname);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        //print_r($data);
        return $data;
    }
    
    public function getRows()
    {
        return $this->_rows;
    }
}
