<?php
namespace ZHMVC\D;
class SystemConfig extends \ZHMVC\D\DataBase
{
    public function getAll()
    {
        //var_dump($this->_pre);
        $sql="select `id` , `name` , `value`  from `".$this->_pre."config`";
       // echo $sql;
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getRows()
    {
        return $this->_rows;
    }
}