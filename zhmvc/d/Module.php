<?php
namespace ZHMVC\D;

class Module extends \ZHMVC\D\DataBase
{
    public function  getOne($postid)
    {
        $sql="select * from " . $this->_pre . "module where id=:id";
        $bind=array(":id"=>$postid);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function add($biaoqianzhui, $mulu, $name, $modulename, $rpctype = '本地', $rpcurl = '', $rpcmainkey = '',$rpchost='',$rpcprivatekey='',$rpcmoduleid=1)
    {
        $sql = "insert into " . $this->_pre . "module(biaoqianzhui,mulu,name,modulename,modulenamespace,rpctype,rpcurl,rpcmainkey,rpchost,rpcprivatekey,rpcmoduleid) values (:biaoqianzhui,:mulu,:name,:modulename,:modulenamespace,:rpctype,:rpcurl,:rpcmainkey,:rpchost,:rpcprivatekey,:rpcmoduleid)";
        //echo $sql;
        $bind = array(
            ":biaoqianzhui" => $biaoqianzhui,
            ":mulu" => $mulu,
            ":name" => $name,
            ":modulename" => $modulename,
            ":modulenamespace" => strtoupper($modulename),
            ":rpctype" => $rpctype,
            ":rpcurl" => $rpcurl,
            ":rpcmainkey" => $rpcmainkey,
            ":rpchost" => $rpchost,
            ":rpcprivatekey" => $rpcprivatekey,
            ":rpcmoduleid" => $rpcmoduleid
        );
        //print_r($bind);
        $this->_db->update($sql, $bind);
        $this->_lastid = $this->_db->getLastId();
        return 1;
    }

    public function update($id, $biaoqianzhui, $mulu, $name, $modulename, $rpctype = '本地', $rpcurl = '', $rpcmainkey = '',$rpchost='',$rpcprivatekey='',$rpcmoduleid=1)
    {
        $sql = "update " . $this->_pre . "module set biaoqianzhui=:biaoqianzhui,mulu=:mulu,name=:name,modulename=:modulename,rpctype=:rpctype,rpcurl=:rpcurl,rpcmainkey=:rpcmainkey,rpchost=:rpchost,rpcprivatekey=:rpcprivatekey,rpcmoduleid=:rpcmoduleid where id=:id";
        $bind = array(
            ":biaoqianzhui" => $biaoqianzhui,
            ":mulu" => $mulu,
            ":name" => $name,
            ":modulename" => $modulename,
            ":rpctype" => $rpctype,
            ":rpcurl" => $rpcurl,
            ":rpcmainkey" => $rpcmainkey,
            ":rpchost" => $rpchost,
            ":rpcprivatekey" => $rpcprivatekey,
            ":rpcmoduleid" => $rpcmoduleid,
            ":id" => $id
        );
        $this->_db->update($sql, $bind);
        return 1;
    }

    public function delete($id)
    {
        $sql = "delete from " . $this->_pre . "module where id=:id";
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