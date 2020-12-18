<?php
header('content-type:text/html;charset=utf-8');
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


$id = SafeRequest(getPGC("jqid"), 0);  //景区id
$fenxiaoshangid = SafeRequest(getPGC("userid"), 0);  //用户id
$nickname = SafeRequest(getPGC("nickname"), 0);  //用户id

$APPID = "wx56d1834546ee0426";
$APPSECRET = "bd22cad285711b0278a10098cbb53687";
// 获取access_token
$access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
// 缓存access_token

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
$basedir="/erweima/";
$turedir=ZH_PATH."/erweima/";

if(!file_exists($turedir))
{
    $old=umask(0);
    mkdir($turedir,0775);
    umask($old);
}
$basedir.=$id."/";
$turedir.=$id."/";

if(!file_exists($turedir))
{
    $old=umask(0);
    mkdir($turedir,0775);
    umask($old);
}
$path_0 = $turedir.$fenxiaoshangid.'-qrcode.jpg';
$path_2 = $turedir.$fenxiaoshangid.'-qrcode.png';
$path_3 = $turedir.$fenxiaoshangid.'-qrcode1.png';
$path_4 = $turedir.$fenxiaoshangid.'-qrcode2.png';
$path_6 = $turedir.$fenxiaoshangid.'-source.png';
$path_5 = $turedir.$fenxiaoshangid.'-haibao.png';
$path_2_base = $basedir.$fenxiaoshangid.'-qrcode.png';
$path_5_base = $basedir.$fenxiaoshangid.'-haibao.png';

// 构建请求二维码参数
// path是扫描二维码跳转的小程序路径，可以带参数?id=xxx
// width是二维码宽度
//$qcode = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$ACCESS_TOKEN";
$qcode = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$ACCESS_TOKEN;
$param = json_encode(array(
    "scene" => "id=".$id."#".$fenxiaoshangid,
    "page" => "pages/lvyou/mingxi"
));
// POST参数
$result = httpRequesterweima($qcode, $param, "POST");
// 生成二维码
file_put_contents($path_0, $result);

//get_qrcode($ACCESS_TOKEN,"id=".$id."#".$fenxiaoshangid,"pages/lvyou/mingxi1",$path_2);
//$image_size=getimagesize($path_0);
//var_dump($image_size);


$returnS = array(
    'status' => '0',
    'data' => '',
    'msg' => 'success',
    'message' => '成功请求',
    'updateDime' => intval(time())
);

//生成二维码
//获取景区id分享的图片
$rs1 = new \LVYOU\D\Attractions();
$rs1info=$rs1->getOne($id);
$haibao=$rs1info['haibao'];

$rs2=new \LVYOU\D\Haibao();
$rs2info=$rs2->getOne($haibao);
$haibaourl=$rs2info['img'];
$dst_x=$rs2info['dst_x'];
$dst_y=$rs2info['dst_y'];
$width=$rs2info['width'];

//获取昵称
$userrs=new \USER\D\Userinfo();
$userinfo=$userrs->getOne($fenxiaoshangid);

$path_1 = ZH_PATH.$haibaourl;
//图片二
//
webpToPic($path_0,'png',$path_2);
transform_image($path_2, 'png', $path_3);
transform_image($path_1, 'png', $path_6);

scaleImg($path_3,$path_4,$width,$width);

$array = getimagesize($path_4);

//创建图片对象
$image_1 = imagecreatefrompng($path_6);
$image_2 = imagecreatefrompng($path_4);

$fontfile = '/data/www/lvyou/api/simhei.ttf';
$nickname=to_entities($nickname);
imagealphablending($image_1, true);
$black = imagecolorallocate($image_1, 0x00, 0x00, 0x00);
//$nickname
// imagefttext("Image", "字体大小", "旋转", "左边距","上边距", "字体颜色", "字体文件名称", "插入文本内容");
imagefttext($image_1, 12, 0, 10, 25, $black, $fontfile, $nickname);
//合成图片
//imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )---拷贝并合并图像的一部分
//将 src_im 图像中坐标从 src_x，src_y 开始，宽度为 src_w，高度为 src_h 的一部分拷贝到 dst_im 图像中坐标为 dst_x 和 dst_y 的位置上。两图像将根据 pct 来决定合并程度，其值范围从 0 到 100。当 pct = 0 时，实际上什么也没做，当为 100 时对于调色板图像本函数和 imagecopy() 完全一样，它对真彩色图像实现了 alpha 透明。
imagecopymerge($image_1, $image_2, $dst_x, $dst_y, 0,0, imagesx($image_2), imagesy($image_2), 100);
// 输出合成图片
$merge = $path_5;
imagepng($image_1,$merge);

$returnS['data'] = $path_5_base;
$returnS['code'] = "0";
$returnS['msg'] = "ok";
$returnS['message'] = "成功";
$result = json_encode($returnS);
echo $result;
exit();