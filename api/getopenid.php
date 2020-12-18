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

$code = SafeRequest(getPGC("code"), 0);
$weixin =  file_get_contents('https://api.weixin.qq.com/sns/jscode2session?appid=wxf70ea14822131aa0&secret=b68ea8b2167cfbe42e3ad992c40a0ca6&js_code='.$code.'&grant_type=authorization_code');//通过code换取网页授权access_token
$jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
$returnS['data']=$jsondecode;
$result = json_encode($returnS);
echo $result;
exit();