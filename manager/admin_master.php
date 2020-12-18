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
    <div id="breadcrumb"> <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="admin_master.php" class="current">管理员管理</a> </div>
  	<button class="btn" onClick="location='?atcion=main';" >管理首页</button>
    <button class="btn btn-primary" onClick="location='?atcion=add';" >新增</button>
  </div>
  <div class="container-fluid">
    <!--  -->
    <div class="row-fluid">
<?php 
$action=SafeRequest(getPGC('atcion'),0);
switch ($action)
{
    case "save_admin":
        save_admin($db_pre);
        break;
    case "add":
        add($db_pre);
        break;
    case "del":
        del($db_pre);
        break;
    case "power":
        savepower($db_pre);
        break;
    case "edit":
        editpower($db_pre);
        break;
    default:
        main($db_pre);
}

function main($db_pre)
{
?>
    <div class="widget-box">
          <div class="widget-content nopadding">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
        			<th>ID</th>
        			<th>登陆名</th>
        			<th>上次登陆时间</th>
        			<th>上次登陆IP</th>
        			<th>状态</th>
        			<th>操作</th>
        		</tr>
              </thead>
              <tbody>
	<?php
	$rs=D("" . $db_pre . "master")->getLinkAll("id,username,lastime,lastip,`state`",true);
    $datas = $rs['datas'];
    $rows = $rs['rows'];
    for ($j = 0; $j < $rows; $j ++) {
        $data = $datas[$j];
        if ($data['state'] == 1) {
            $M_state = "正常";
        } else {
            $M_state = "禁止";
        }
        ?>
  		<tr>
			<td><?php echo $data['id'];?></td>
			<td><a href="?atcion=add&id=<?php echo $data['id'];?>"
				title="点击编辑"><?php echo $data['username'];?></a></td>
			<td><?php echo $data['lastime'];?></td>
			<td><?php echo $data['lastip'];?></td>
			<td><?php echo $M_state;?></td>
			<td class="taskOptions"><a href="?atcion=edit&id=<?php echo $data['id'];?>">编辑权限</a>
				| <a href="?atcion=del&id=<?php echo $data['id'];?>"
				onclick="{if(confirm('确定删除吗?')){return true;}return false;}">删除</a></td>
		</tr>
        <?php
        }
        ?>
              </tbody>
            </table>
          </div>
        </div>
  
<?php 
}

function add($db_pre)
{
    $postid = SafeRequest(getPGC('id'), 0);

    if (($postid != "") && ($postid != 0)) {
        $bind = array("id" => $postid);
        $rs=D("" . $db_pre . "master")->where($bind)->getLinkOne("username,`state`",true);
        $datas = $rs['datas'];
        $rows = $rs['rows'];
        $BackName = $datas['username'];
        $State = $datas['state'];
    } else {
        $BackName = "";
        $State = "";
        $xingming = "";
        $danwei = "";
        $dianhua = "";
    }
?>
    <form action="?atcion=save_admin&id=<?php echo $postid;?>" id="form1" name="form1" method="post" class="form-horizontal">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>管理员管理－－添加管理员</h5>
          </div>
          <div class="widget-content">
          <!-- 表单 -->
          	<div class="control-group">
            	<label class="control-label">后台登陆名称:</label>
                  <div class="controls">
                    <input type="text" name="backname" class="span" placeholder="0" value="<?php echo $BackName; ?>" />
                  </div>
        	</div>
        	<div class="control-group">
            	<label class="control-label">后台登陆密码<br />(<font color="red">如只修改用户登陆名而不修改密码请留空</font>):</label>
                  <div class="controls">
                    <input type="password" name="password" class="span" placeholder="" />
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
            
          	
            <div class="form-actions">
            	<input type="hidden" name="id" value="<?php echo $postid; ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="canshu" value="save">
                 <button type="submit" class="btn btn-success">Save</button>
            </div>
          <!-- 表单 -->
          </div>
    	</div>
    </form>
<?php
}

function editpower($db_pre)
{
    $postid = SafeRequest(getPGC('id'), 0);
    $bind = array("id" => $postid);
    $rs=D("" . $db_pre . "master")->where($bind)->getLinkOne("seting,column_setting",true);
    $datas = $rs['datas'];
    $rows = $rs['rows'];
    $Power = $datas['seting'];
    $Admin_Power = $datas['column_setting'];
?>

<form action="?atcion=power&id=<?php echo $postid;?>" id="form1" name="form1" method="post" class="form-horizontal">
<script type="text/javascript">
function selectAll(){
	var checklist = document.getElementsByName ("admin_power[]");

	if(document.getElementById("controlAll").checked)
	{
	   for(var i=0;i<checklist.length;i++)
	   {
	      checklist[i].checked = true;
	      checklist[i].parentNode.setAttribute("class", "checked");
	   }
	}
	else
	{
	  for(var j=0;j<checklist.length;j++)
	  {
		 checklist[j].parentNode.setAttribute("class", "");
		 checklist[j].checked = false;
	  }
	}
}
</script>
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
        <h5>后台管理权限设定</h5>
      </div>
      <div class="widget-content">
      <?php
    $map['pid']='0';
    $rs=D("" . $db_pre . "admin_menu")->where($map)->getLinkAll("id,title,url",true);
    $datas = $rs['datas'];
    $rows = $rs['rows'];
    //print_r($rs);
    $map=null;
    $m=0;
    for ($i = 0; $i < $rows; $i ++) {
        $data = $datas[$i];
        ?>
      	<div class="control-group">
           <label class="control-label"><?php echo $data['title']; ?></label>
           <div class="controls">
           <?php
        $map['pid']=$data['id'];
        $rs1=D("" . $db_pre . "admin_menu")->where($map)->getLinkAll("id,title,url,menuname",true);
        $datas1 = $rs1['datas'];
        $rows1 = $rs1['rows'];
        $map=null;
        if ($rows1 > 0) {
            for ($j = 0; $j < $rows1; $j ++) {
                $data1 = $datas1[$j];
                echo "<label><input type=\"checkbox\" id=\"selected".$m."\" name=\"admin_power[]\" value=\"" . $data1['menuname'] . $data1['url'] . "\"";
                if ((substr_count($Power, $data1['menuname'] . $data1['url'] . ",")) > 0) {
                    echo " checked";
                }
                echo ">";
                echo $i . ($j + 1);
                echo "." . $data1['title']."</label>";
                $m=$m+1;
            }
        }
    ?>
            </div>
           <?php }?>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-success">保存</button>
            <input onclick="selectAll()" type="checkbox" name="controlAll" style="controlAll" id="controlAll"/>全选
        </div>
      </div>
    </div>
</form>

<?php
}

function savepower($db_pre)
{
    $Admin_Power = "";
    foreach (getPGC("admin_power") as $j => $b) {
        $Admin_Power = $Admin_Power . $b . ",";
    }
    $Admin_Power = str_replace(" ", "", $Admin_Power);
    $Admin_Power = str_replace(",,", ",", $Admin_Power);
    $postid = SafeRequest(getPGC('id'), 0);
    if (($postid != "") && ($postid != "0")) {
        $wheremap = array("id" => $postid);
        $updatedata = array("seting" => $Admin_Power);
        D("" . $db_pre . "master")->where($wheremap)->LinkUpdate($updatedata);
 
        echo "<script>alert('更新成功');window.location.href='admin_master.php';</script>";
    }
}

function save_admin($db_pre)
{
    $postid = SafeRequest(getPGC('id'), 0);
    $BackName = SafeRequest(getPGC('backname'), 0);
    $State = SafeRequest(getPGC('state'), 0);
    $PassWord = SafeRequest(getPGC('password'), 0);
    $PSQL = "";
    If ($PassWord != "") {
        $PassWord = Md5($PassWord);
    }
    If (($BackName == "") || (strlen($BackName) > 50)) {
        $ErrMsg = "登陆名为空或长度超过50个字符！";
        echo $ErrMsg;
    }

    if (($postid != "") && ($postid != "0")) {
        if ($PSQL == "") {
            $wheremap = array("id" => $postid);
            $updatedata = array("state" => $State,"username"=>$BackName);
            D("" . $db_pre . "master")->where($wheremap)->LinkUpdate($updatedata);
        } else {
            $wheremap = array("id" => $postid);
            $updatedata = array("state" => $State,"username"=>$BackName,"userpassword"=>$PassWord);
            D("" . $db_pre . "master")->where($wheremap)->LinkUpdate($updatedata);
        }
        echo "<script>alert('更新成功');window.location.href='admin_master.php';</script>";
    } else {
        $bind = array(
            "username" => $BackName
        );
        $rs=D("" . $db_pre . "master")->where($bind)->getLinkAll("id",true);
        $datas = $rs['datas'];
        $rows = $rs['rows'];
        // echo $sql;
        If ($rows != 0) {
            $FoundErr = True;
            $ErrMsg = "对不起，后台登陆名重复！";
        } ElseIf ($PassWord == "") {
            $FoundErr = True;
            $ErrMsg = "对不起，请填写登陆密码！";
        } else {
            $wheremap = array("id" => $postid);
            $updatedata = array("state" => $State,"username"=>$BackName,"userpassword"=>$PassWord);
            D("" . $db_pre . "master")->LinkInsert($updatedata);
            echo "<script>alert('更新成功');window.location.href='admin_master.php';</script>";
        }
        If ($FoundErr) {
            echo $ErrMsg;
        }
    }
}

function del($db_pre)
{
    $postid = SafeRequest(getPGC('id'), 0);
    if (($postid != "") && ($postid != "0")) {
        $wheremap = array("id" => $postid);
        D("" . $db_pre . "master")->where($wheremap)->LinkDelete();
        echo "<script>alert('更新成功');window.location.href='admin_master.php';</script>";
    }
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