<?php
namespace ZHMVC\DB\MANAGER;
if (! isset($_SESSION)) {
    session_start();
}
include(dirname(dirname(__FILE__))."/zhconfig/Config.php");
$action = SafeRequest(getPGC('action'), 0);
//$action = trim($_POST['action']);

if ($action != "login1") {
	echo "<script>alert('登录错误');window.location.href='/manager/index.html';</script>";
}
/*
$Verification=SafeRequest(getPGC("Verification"),0);
if($Verification!=$_SESSION['Verification'])
{
    echo "<script>alert('验证码错误');window.location.href='index.php';</script>";
    exit;
}
*/
//$adminname = trim($_POST['adminname']);
//$adminpws = trim($_POST['adminpws']);
$adminname = SafeRequest(getPGC('adminname'), 0);
$adminpws = SafeRequest(getPGC('adminpws'), 0);

$map['username']=$adminname;
$rs=D("zhmvc_master")->where($map)->order("id desc")->getLinkAll("",true);
$datas = $rs['datas'];
$rows = $rs['rows'];
if ($rows == 0) {
	echo "<script>alert('用户名不存在');window.location.href='/manager/index.html';</script>";
} else {
	$adminpws = md5($adminpws);
	$data = $datas[0];
	if ($data['userpassword'] != $adminpws) {
		echo "<script>alert('密码错误');window.location.href='/manager/index.html';</script>";
	} elseif ($data['state'] == 0) {
		echo "<script>alert('该用户名不允许登录');window.location.href='/manager/index.html';</script>";
	} else {	$seedarray = microtime();
		$random = rand(1, 1000);
		$ip=new \ZHMVC\B\TOOL\Ip(); // 实例化未引用的类
		$ipaddress=$ip->getIp();
		$_SESSION['adminid'] = $data['id'];
		$_SESSION['adminname'] = $adminname;
		$_SESSION['Master_Power'] = $data['seting'];//后台权限
		$AddTime = time();
		$Master_Id = $data['id'];
		$wheremap=array();
		$updatedata=array();
		$wheremap['id']=$Master_Id;
		$updatedata["cookiess"]=$random;
		$updatedata["lastip"]=$ipaddress;
		$updatedata["lastime"]=date("Y-m-d   H:i:s", $AddTime);
		$rs=D("zhmvc_master")->where($wheremap)->LinkUpdate($updatedata);
		Header("Location: /manager/main.php");
		//登录成功
	}
}
