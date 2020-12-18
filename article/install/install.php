<?php
namespace ARTICLE;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(dirname(__FILE__))) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
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
// 插入插件表
$addmodule = new \ZHMVC\D\Module();
$addmodule->add($qianzui, $mulu, $name, $modulename, $rpctype, $rpcurl, $rpcmainkey, $rpchost, $rpcprivatekey, $rpcmoduleid);
$moduleid = $addmodule->getLastId();

// 执行数据表
$addtables = new \ARTICLE\D\Admin_Tables();
$addtables->add($qianzui, "/" . $mulu . "/d/", "/" . $mulu . "/admin/", $modulename, $moduleid,$mulu, "/" . $mulu . "/rpc/", $rpctype, $rpcurl, $rpcmainkey, $rpchost, $rpcprivatekey, $rpcmoduleid);
echo "建立锁文件<br />";

//插入后台菜单
$addmenu = new \ZHMVC\D\Menu();
$addmenu->add('0', $qianzui, $name, "/" . $mulu . "/" . "admin/admin_articlecolumn.php");

// 获取最后的一条id
$lastid = $addmenu->getLastId();
$addmenu->add($lastid,$qianzui,"栏目管理","/".$mulu."/"."admin/admin_articlecolumn.php");
$addmenu->add($lastid,$qianzui,"文章管理","/".$mulu."/"."admin/admin_articlecontent.php");

echo "执行sql语句<br />";
// 建立安装文件
$addfile = new \ARTICLE\B\Admin_File();
$addfile->add($qianzui, $mulu, $name, $modulename,$rpctype, $rpcurl, $rpcmainkey, $rpchost, $rpcprivatekey, $rpcmoduleid);
//print_r($datatype);
if($rpctype=="远程")
{
    $c = new \ZHCONFIG\ZhConfig();
    $db_pre = $c->getDbPre();
    $db_database = $c->getDatabase();
    //print_r($db_database);
    $biaoqianzhui = $qianzui;
    //print_r($biaoqianzhui);
    $rs1 = D('information_schema.tables')->where("table_name LIKE '" . $biaoqianzhui . "_%' and TABLE_SCHEMA='" . $db_database . "'")->getLinkAll("CONCAT( 'drop table ', table_name, ';' ) as deltables", true);
    $datas1 = $rs1['datas'];
    $rows1 = $rs1['rows'];
    //print_r($datas1);
    for ($i = 0; $i < $rows1; $i ++) {
        $data = $datas1[$i];
        // print_r($data);
        D('information_schema.tables')->SqlUpdate($data['deltables']);
    }
}

echo "安装配置文件成功";
echo "安装成功<br />";
?>
</body>
</html>