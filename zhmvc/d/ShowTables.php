<?php
namespace ZHMVC\D;
class ShowTables extends \ZHMVC\D\DataBase
{
    public function getColumns($tables)
    {
        $sql="show columns from ".$tables.";";
        $data=$this->_db->getAll($sql);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
}
