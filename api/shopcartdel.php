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
    'data' => array(),
    'msg' => 'success',
    'message' => '成功请求',
    'updateDime' => intval(time())
);
$userid = getPGC("userid");
$rs = new \SHOP\D\Shoppingcart();
$rs->deleteUser($userid);
$returnS['data'] = '';
$returnS['code'] = "0";
$returnS['msg'] = "ok";
$returnS['message'] = "成功";
$result = json_encode($returnS);
echo $result;
exit();