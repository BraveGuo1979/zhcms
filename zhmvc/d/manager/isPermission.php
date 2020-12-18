<?php
namespace ZHMVC\D\MANAGER;

class isPermission
{

    private $_curUrl = "";

    private $_getcurUrl = 0;

    public function __construct()
    {
        $this->_curUrl = $_SERVER['PHP_SELF'];
        
    }

    public function getPermission()
    {
        
        if (! isset($_SESSION)) {
            $r=1;
        }
        else
        {
            $r = 0;
            $map = array(
                "url" => $this->_curUrl
            );
            $rs=D("zhmvc_admin_menu")->where($map)->order("id desc")->getLinkOne("",true);
            $map=null;
            $datas = $rs['datas'];
            $rows = $rs['rows'];
            //var_dump($rs);
            if ($rows > 0) {
                if ($rows > 1) {
                    // 有多条,取第一条pid=0的那一条
                    //$sql1 = "select id,menuname from " . $db_pre . "admin_menu where url=:url and pid='0'";
                    
                    $map1 = array(
                        "url" => $this->_curUrl,"pid"=>'0'
                    );
                    $rs1=D("zhmvc_admin_menu")->where($map)->order("id desc")->getLinkOne();
                    $map1=null;
                    $datas1 = $rs1['datas'];
                    $this->_getcurUrl = $datas1['id'];
                } else {
                    $this->_getcurUrl = $datas['pid'];
                }
                
                
                if(isset($_SESSION['Master_Power'])==true)
                {
                    
                    if (substr_count($_SESSION['Master_Power'], $datas['menuname'] . $this->_curUrl . ",") == 0) {
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
