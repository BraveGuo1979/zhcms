<?php
namespace ZHMVC\D;

class Menu extends \ZHMVC\D\DataBase
{

    public function add($pid, $menuname, $title, $url)
    {
        $sql = "insert into " . $this->_pre . "admin_menu(pid,menuname,title,url) values (:pid,:menuname,:title,:url)";
        $bind = array(
            ":pid" => $pid,
            ":menuname" => $menuname,
            ":title" => $title,
            ":url" => $url
        );
        $this->_db->update($sql, $bind);
        $this->_lastid = $this->_db->getLastId();
        return 1;
    }

    public function update($id, $pid, $menuname, $title, $url)
    {
        $sql = "update " . $this->_pre . "admin_menu set pid=:pid,menuname=:menuname,title=:title,url=:url where id=:id";
        $bind = array(
            ":pid" => $pid,
            ":menuname" => $menuname,
            ":title" => $title,
            ":url" => $url,
            ":id" => $id
        );
        $this->_db->update($sql, $bind);
        return 1;
    }

    public function delete($id)
    {
        $sql = "delete from " . $this->_pre . "admin_menu  where id=:id";
        $bind = array(
            ":id" => $id
        );
        $this->_db->update($sql, $bind);
        return 1;
    }

    public function getLastId()
    {
        return $this->_lastid;
    }
}
