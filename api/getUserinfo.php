<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
// 制定允许其他域名访问
header("Access-Control-Allow-Origin:*");
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with, content-type');
ini_set('date.timezone', 'Asia/Shanghai');
session_start();
include(dirname(dirname(__FILE__))."/zhconfig/Config.php");
$returnS = array(
    'status' => '0',
    'data' => 0,
    'msg' => 'success',
    'message' => '成功请求',
    'updateDime' => intval(time())
);

$openid = SafeRequest(getPGC("openid"), 0);

$map=array();
$map['openid']=$openid;
$rs1 = new \USER\D\Userinfo();
$datas = $rs1->getOne1($map);
$rows = $rs1->getRows();
if($rows>0)
{
    $id=$datas['id'];
    $returnS['data'] = $id;
    $returnS['datas'] = $datas;
    $returnS['code'] = "0";
    $returnS['msg'] = "ok";
    $returnS['message'] = "成功";
}
else
{
    $returnS['code'] = "1";
    $returnS['msg'] = "error";
    $returnS['message'] = "超时";
}

$result = json_encode($returnS);
echo $result;
exit();