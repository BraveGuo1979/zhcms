<?php
namespace ZHMVC\DB\MANAGER;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(__FILE__)) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);

$isp = new \ZHMVC\D\MANAGER\isPermission();
$isper = $isp->getPermission();
$_curlid = $isp->getCUrl();
$c = new \ZHCONFIG\ZhConfig();
$db_pre = $c->getDbPre();
if($isper==1)
{
	$ErrMsg="对不起，你没有访问该页面的权限";
	echo $ErrMsg;
	exit;
}
elseif($isper==0)
{
	$ErrMsg="对不起，地址错误";
	echo $ErrMsg;
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ZHCMS后台管理</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php
include (ZH_PATH . DS . 'manager' . DS . 'css' . ZH);
include (ZH_PATH . DS . 'manager' . DS . 'js' . ZH);
?>
</head>
<body>
<?php 
include (ZH_PATH . DS . 'manager' . DS . 'top' . ZH);
include (ZH_PATH . DS . 'manager' . DS . 'menu' . ZH);
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="admin_config.php" class="current">系统设置</a> </div>
  	<h1>修改密码</h1>
  </div>
  <div class="container-fluid">
    <!--  -->
    <div class="row-fluid">
<?php 

$action=SafeRequest(getPGC('atcion'),0);

switch ($action)
{
    case "save":
        save($db_pre);
        break;
    default:
        main($db_pre);
}

function main($db_pre)
{
    $data = D($db_pre . "master")->where("id='".$_SESSION['id']."' and username='".$_SESSION['adminname']."'")->getLinkOne("id,username,userpassword");
    $id=$data['id'];
    $username=$data['username'];
    $userpassword=$data['userpassword'];
?>
<form action="?atcion=save" id="form1" name="form1" method="post" class="form-horizontal">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
        <h5>修改密码</h5>
      </div>
      <div class="widget-content">
          	<div class="control-group">
              <label class="control-label">用户名:</label>
              <div class="controls">
                <input type="text" name="username" class="span" placeholder="" value="<?php echo $username; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">旧密码:</label>
              <div class="controls">
                <input type="text" id="olduserpassword" name="olduserpassword" class="span"
                 placeholder="请输入旧密码" value="" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">新密码:</label>
              <div class="controls">
                <input type="text" name="pwd" id="pwd" class="span"
                 placeholder="请输入旧密码" value="" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">再次输入新密码:</label>
              <div class="controls">
                <input type="text" name="pwd2" id="pwd2" class="span"
                 placeholder="请输入旧密码" value="" />
              </div>
            </div>
            <div class="form-actions">
				<button type="submit" class="btn btn-success">保存</button>
        	</div>
      </div>
   </div>
</form>
<?php 
}
function save($db_pre)
{
    $id=$_SESSION['id'];
    $adminname=$_SESSION['adminname'];
    $olduserpassword=SafeRequest(getPGC('olduserpassword'),0);
    $pwd=SafeRequest(getPGC('pwd'),0);
    $pwd2=SafeRequest(getPGC('pwd2'),0);
    
    if($pwd!=$pwd2)
    {
       echo "<script>alert('对不起，两次输入的密码不一致');history.back();</script>";
       exit;
    }
    
    $sql = "select id from ".$db_pre."master where username=:username and userpassword=:userpassword and id=:id";
    //	echo $sql;
    $bind = array(":username" => $adminname,":userpassword"=>md5($olduserpassword),":id"=>$id);
    $datas = $conn -> getAll($sql, $bind);
    $rows = $conn -> getRowCount();
    if($rows==0)
    {
        echo "<script>alert('对不起，旧密码不正确');history.back();</script>";
        exit;
    }
    $sql="update ".$db_pre."master set userpassword=:userpassword where id=:id";
    //echo $sql;
    $bind = array(":userpassword" => md5($pwd),":id" => $id);
    $conn -> update($sql, $bind);
    echo "<br />更新成功!";
}
?>
  </div>
  <!--  -->
  </div>
</div>
<?php 
include (ZH_PATH . DS . 'manager' . DS . 'foot' . ZH);
?>
</body>
</html>