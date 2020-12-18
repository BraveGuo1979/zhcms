<?php
header('content-type:text/html;charset=utf-8');
// 配置APPID、APPSECRET
$APPID = "wx56d1834546ee0426";
$APPSECRET = "bd22cad285711b0278a10098cbb53687";
// 获取access_token
$access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
// 缓存access_token
session_start();
$_SESSION['access_token'] = "";
$_SESSION['expires_in'] = 0;
$ACCESS_TOKEN = "";
if (! isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in'])) {
    $json = httpRequest($access_token);
    $json = json_decode($json, true);
    // var_dump($json);
    $_SESSION['access_token'] = $json['access_token'];
    $_SESSION['expires_in'] = time() + 7200;
    $ACCESS_TOKEN = $json["access_token"];
} else {
    $ACCESS_TOKEN = $_SESSION["access_token"];
}

// 构建请求二维码参数
// path是扫描二维码跳转的小程序路径，可以带参数?id=xxx
// width是二维码宽度
$qcode = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$ACCESS_TOKEN";
$param = json_encode(array(
    "path" => "/pages/lvyou/mingxi.js?id=1&fenxiaoshangid=4",
    "width" => 150
));
// POST参数
$result = httpRequest($qcode, $param, "POST");
// 生成二维码
file_put_contents("./qrcode.png", $result);

echo "<img src='qrcode.png'/>";
// 把请求发送到微信服务器换取二维码


