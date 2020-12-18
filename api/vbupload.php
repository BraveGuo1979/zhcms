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

$userid = SafeRequest(getPGC("userid"), 0);
$kctype = SafeRequest(getPGC("kctype"), 0);
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
            $fp=str_replace('..', 'https://gongan.myjob168.com', $fp);
            $rs1=new \KAOSHI\D\Kaoshenglist();
            if($kctype=="zige")
            {
                $sql="update ks_kaoshenglist set zigefile='".$fp."' where id='".$userid."'";
                
                $fpa = fopen("./ccc.txt",'w');
                fwrite($fpa,$sql);
                fclose($fpa);
                
                $rs1->SqlUpdate($sql);
            }
            elseif($kctype=="mianshi")
            {
                $sql="update ks_kaoshenglist set mianshifile='".$fp."' where id='".$userid."'";
                $rs1->SqlUpdate($sql);
            }
            elseif($kctype=="tijian")
            {
                $sql="update ks_kaoshenglist set tijianfile='".$fp."' where id='".$userid."'";
                $rs1->SqlUpdate($sql);
            }
            elseif($kctype=="tineng")
            {
                $sql="update tinengfile set tijianfile='".$fp."' where id='".$userid."'";
                $rs1->SqlUpdate($sql);
            }
            
        }
    }
   
}
