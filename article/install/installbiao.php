<?php
namespace ARTICLE;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(dirname(__FILE__))) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
$moduleid = SafeRequest(getPGC('moduleid'), 0);
$qianzui = SafeRequest(getPGC('qianzui'), 0);
$mulu = SafeRequest(getPGC('mulu'), 0);
$name = SafeRequest(getPGC('name'), 0);
$rpctype = SafeRequest(getPGC('rpctype'), 0);
$rpcurl = SafeRequest(getPGC('rpcurl'), 0);
$rpcmainkey = SafeRequest(getPGC('rpcmainkey'), 0);
$rpchost = SafeRequest(getPGC('rpchost'), 0);
$rpcprivatekey = SafeRequest(getPGC('rpcprivatekey'), 0);
$rpcmoduleid = SafeRequest(getPGC('rpcmoduleid'), 0);
$modulename = "article";
?>
<!DOCTYPE html>
<html>
<head>
<title>ZHCMS后台管理</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php
include (ZH_PATH . DS . "manager" . DS . "css" . ZH);
?>
</head>
<body>
<?php 
// 执行数据表
$addtables = new \ARTICLE\D\Admin_TablesB();
$addtables->add($qianzui, "/" . $mulu . "/d/", "/" . $mulu . "/admin/", $modulename, $moduleid,$mulu, "/" . $mulu . "/rpc/", $rpctype, $rpcurl, $rpcmainkey, $rpchost, $rpcprivatekey, $rpcmoduleid);
echo "安装成功<br />";
?>
</body>
</html>