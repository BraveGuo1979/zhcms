<?php
ini_set('memory_limit','512M');    // 临时设置最大内存占用为3G
set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
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
include(dirname(__FILE__)."/baidusdk/AipOcr.php");
$key = SafeRequest(getPGC("key"), 0);
$openid = SafeRequest(getPGC("openid"), 0);
const APP_ID = '17072875';
const API_KEY = '3p05N7KjYMc3hA7HsEZAZXHj';
const SECRET_KEY = 'XPo8PUGm8fchPUG6VyyI94jZ2fdSQ975';
$returnS = array(
    'code' => '0',
    'gesi' => '0',
    'data' => '',
    'msg' => 'success',
    'message' => '成功请求',
    'updateDime' => intval(time())
);
$tmpP = $_FILES["file"]["tmp_name"];
if($tmpP){
    $type = mime_content_type($tmpP);//获得文件类型
    $p = null;
    $returnS['gesi']=$type;
    if($type=="image/png"){
        $p.=".png";
    }
    elseif($type=="image/gif"){
        $p.=".gif";
    }
    elseif($type=="image/jpeg"){
        $p.=".jpeg";
    }
    elseif($type=="image/bmp"){
        $p.=".bmp";
    }
    elseif($type=="image/x-icon"){
        $p.=".icon";
    }
    elseif($type=="video/mp4"){
        $p.=".mp4";
    }
    elseif($type=="video/3gpp"){
        $p.=".3gp";
    }
    elseif($type=="video/quicktime"){
        $p.=".mov";
    }
    elseif($type=="video/ogg"){
        $p.=".ogv";
    }
    elseif($type=="video/webm"){
        $p.=".webm";
    }
    elseif($type=="video/x-msvideo"){
        $p.=".avi";
    }
    elseif($type=="video/x-ms-wmv"){
        $p.=".wmv";
    }
    $basedir=dirname(dirname(__FILE__))."/upload";
    $truedir="../upload";
    if(!file_exists($basedir))
    {
        $old=umask(0);
        mkdir($basedir,0775);
        umask($old);
    }
    
    $basedir.="/".date('Y');
    $truedir.="/".date('Y');
    if(!file_exists($basedir))
    {
        $old=umask(0);
        mkdir($basedir,0775);
        umask($old);
    }
    
    $basedir.="/".date('m');
    $truedir.="/".date('m');
    if(!file_exists($basedir))
    {
        $old=umask(0);
        mkdir($basedir,0775);
        umask($old);
    }
    
    $basedir.="/".date('d');
    $truedir.="/".date('d');
    if(!file_exists($basedir))
    {
        $old=umask(0);
        mkdir($basedir,0775);
        umask($old);
    }
    
    $random =rand(1,4000);
    $filename=date("YmdGis").$random;
    if($p){
        $fp =$truedir."/".$filename.$p ;
        $fp1 =$basedir."/".$filename.$p ;
        //$tmp_file111 = ZH_PATH.'/upload/'.$filename.$p;
        if (move_uploaded_file ($tmpP, $fp1 )) {//保存文件
            //对￥fp进行替换
            $fp=str_replace('..', 'https://gongan.myjob168.com', $fp);
            $client = new AipOcr(APP_ID, API_KEY, SECRET_KEY);
            $image = file_get_contents($fp1);
            $idCardSide = "front";
            // 调用身份证识别
            $options = array();
            $options["detect_direction"] = "true";
            $options["detect_risk"] = "true";
            $sfzdata=$client->idcard($image, $idCardSide,$options);
            $returnS['data']=$fp;
            $returnS['sfz']=$sfzdata;
            
            //保存身份证证照片到数据库中,附件类型，1是政审表材料，2是考察表，3是面试表，4是身份证正面，5是身份证反面，6是户口本，7是头像
            $rs1=new \KAOSHI\D\Kaoshengfile();
            $map=array();
            $map['kaoshengid']=$openid;
            $map['filetype']="4";
            $data1=$rs1->getAll($map);
            $row=$rs1->getRows();
            if($row>0)
            {
                $rs1->updateOpenid($openid, '4', $fp);
            }
            else
            {
                $rs1->add('', $openid, '4', '身份证', $fp);
            }
        }
        else {
            $returnS['code']='1';
            $returnS['msg']=$_FILES["imgfile"]["error"];
        }
    }
    else {
      $returnS['code']='1';
    }
}
else {
  $returnS['code']='1';
}

$result = json_encode($returnS);
echo $result;
exit();