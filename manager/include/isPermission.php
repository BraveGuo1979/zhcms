<?php
namespace ZHMVC\DB\MANAGER;

class isPermission
{

    private $_curUrl = "";

    private $_getcurUrl = 0;

    public function __construct()
    {
        $this->_curUrl = $_SERVER['PHP_SELF'];
    }

    public function getPermission($db, $db_pre)
    {
        if (! isset($_SESSION)) {
            $r=1;
        }
        else
        {
            $r = 0;
            $sql = "select id,menuname,pid from " . $db_pre . "admin_menu where url=:url";
            $bind = array(
                ":url" => $this->_curUrl
            );
            $data = $db->getOne($sql, $bind);
            $rows = $db->getRowCount();
            if ($rows > 0) {
                if ($rows > 1) {
                    // 有多条,取第一条pid=0的那一条
                    $sql1 = "select id,menuname from " . $db_pre . "admin_menu where url=:url and pid='0'";
                    $bind1 = array(
                        ":url" => $this->_curUrl
                    );
                    $data1 = $db->getOne($sql1, $bind1);
                    $this->_getcurUrl = $data1['id'];
                } else {
                    $this->_getcurUrl = $data['pid'];
                }
                if(isset($_SESSION['Master_Power'])==true)
                {
                    if (substr_count($_SESSION['Master_Power'], $data['menuname'] . $this->_curUrl . ",") == 0) {
                        $r = 1;
                    } else {
                        $r = 2;
                    }
                }
                else {
                    $r = 1;
                }
                
            } else {
                $this->_getcurUrl = 0;
                $r = 0;
            }
        }
        return $r;
    }

    public function getCUrl()
    {
        return $this->_getcurUrl;
    }
}
