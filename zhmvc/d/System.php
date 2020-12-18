<?php
namespace ZHMVC\D;

class System extends \ZHMVC\D\DataBase
{
    public function getOne()
    {
        $sql="select `id` , `info` , `editor`  from `".$this->_pre."system`";
        
        $data=$this->_db->getOne($sql);
        return $data;
    }
}