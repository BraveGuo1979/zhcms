<?php
namespace ZHMVC\DB\MANAGER;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(__FILE__)) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);

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
    <div id="breadcrumb"> <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="admin_info.php" class="current">信息设置</a> </div>
  	<h1>信息设置</h1>
  	</div>
  <div class="container-fluid">
    <!--  -->
    <div class="row-fluid">
<?php 

$action=SafeRequest(getPGC('action'),0);

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
    $sql = "select username,`state` from " . $db_pre . "master where id=:id";
    $bind = array(
       ":id" => $_SESSION['id']
    );
    $data = $conn->getOne($sql, $bind);
    $BackName = $data['username'];
    $State = $data['state'];
?>
    <form action="?atcion=save" id="form1" name="form1" method="post" class="form-horizontal">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>管理员管理－－添加管理员</h5>
          </div>
          <div class="widget-content">
          <!-- 表单 -->
          	<div class="control-group">
            	<label class="control-label">后台登陆名称:</label>
                  <div class="controls">
                    <input type="text" name="backname" readonly class="span" placeholder="0" value="<?php echo $BackName; ?>" />
                  </div>
        	</div>
        	<div class="control-group">
              <label class="control-label">登陆状态:</label>
              <div class="controls">
                <label>
                  <input type="radio" name="state" value="1" <?php
					if ($State == "1") { echo "checked";}?> />
                  	是</label>
                <label>
                  <input type="radio" name="state" value="0" <?php
					if ($State == "0") { echo "checked";}?> />
                 	否</label>
              </div>
            </div>
            <div class="control-group">
            	<label class="control-label">姓名:</label>
                  <div class="controls">
                    <input type="text" name="xingming" class="span" placeholder="0" value="<?php echo $xingming; ?>" />
                  </div>
        	</div>
        	
          	<div class="control-group">
            	<label class="control-label">单位:</label>
                  <div class="controls">
                    <input type="text" name="danwei" class="span" placeholder="0" value="<?php echo $danwei; ?>" />
                  </div>
        	</div>
        	
          	<div class="control-group">
            	<label class="control-label">联系电话:</label>
                  <div class="controls">
                    <input type="text" name="dianhua" class="span" placeholder="0" value="<?php echo $dianhua; ?>" />
                  </div>
        	</div>
            <div class="form-actions">
            	<input type="hidden" name="id" value="<?php echo $id; ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="canshu" value="save">
                 <button type="submit" class="btn btn-success">Save</button>
            </div>
          <!-- 表单 -->
          </div>
    	</div>
    </form>
<?php 
}
function save($db_pre)
{
    $postid = $_SESSION['id'];
    $BackName = SafeRequest(getPGC('backname'), 0);
    $State = SafeRequest(getPGC('state'), 0);
    $xingming = SafeRequest(getPGC('xingming'), 0);
    $danwei = SafeRequest(getPGC('danwei'), 0);
    $dianhua = SafeRequest(getPGC('dianhua'), 0);
    If (($BackName == "") || (strlen($BackName) > 50)) {
        $ErrMsg = "登陆名为空或长度超过50个字符！";
        echo $ErrMsg;
    }
    $sql = "update " . $db_pre . "master set username=:username,`state`=:state,xingming=:xingming,danwei=:danwei,dianhua=:dianhua where id=:id";
    // echo $sql;
    $bind = array(
        ":username" => $BackName,
        ":state" => $State,
        ":xingming" => $xingming,
        ":danwei" => $danwei,
        ":dianhua" => $dianhua,
        ":id" => $postid
    );
    $conn->update($sql, $bind);
    echo "<script>alert('更新成功');window.location.href='admin_info.php';</script>";
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