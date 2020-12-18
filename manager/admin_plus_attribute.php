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
    <div id="breadcrumb"> <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="admin_plus_attribute.php" class="current">插件属性管理</a> </div>
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
    case "save":
		save($db_pre);
		break;
	case "add":
		add($db_pre);
		break;
	case "del":
		del($db_pre);
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
                    <th>插件属性</th>
                    <th>插件属性描述</th>
                	<th>操作</th>
                  </tr>
              </thead>
              <tbody>
  <?php
	//$sql="select id,name,info from ".$db_pre."plus_attribute order by id desc";
	
	$rs = D("" . $db_pre . "plus_attribute")->getLinkAll("id,name,info", true);
	$datas = $rs['datas'];
	$rows = $rs['rows'];
	for($j=0;$j<$rows;$j++)
	{
		$data = $datas[$j];
	?>
 <tr> 
    <td><?php echo $data['id'];?></td>
    <td><?php echo $data['name'];?></td>
    <td><?php echo $data['info'];?></td>
    <td class="taskOptions"><a href="?atcion=del&id=<?php echo $data['id'];?>" onclick="{if(confirm('确定删除吗?')){return true;}return false;}">删除</a></td>
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
	$postid=SafeRequest(getPGC('id'),0);
	
	if(($postid!="") && ($postid!=0))
	{
	    $bind = array(
	        "id" => $postid
	    );
	    $data = D($db_pre . "plus_attribute")->where($bind)->getLinkOne("id,name,info");
		$name=$data['name'];
		$info=$data['info'];
	}
	else
	{
	    $name="";
	    $info="";
	}
?>
<form action="?atcion=save&id=<?php echo $postid;?>" method="post" id="form1" name="form1" class="form-horizontal">
       <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>插件属性管理</h5>
          </div>
          <div class="widget-content">
          <!-- 表单 -->
          	<div class="control-group">
            	<label class="control-label">插件属性名称:</label>
                  <div class="controls">
                    <input type="text" name="name" class="span" placeholder="" value="<?php echo $name; ?>" />
                  </div>
        	</div>
        	<div class="control-group">
            	<label class="control-label">插件属性描述:</label>
                  <div class="controls">
                    <textarea name="info" class="textarea_editor span12" rows="6" placeholder=""><?php echo $info; ?></textarea>
              	</div>
        	</div>

            <div class="form-actions">
            	<button type="submit" class="btn btn-success">添加</button>
            </div>
          <!-- 表单 -->
          </div>
    	</div>
    </form>
<?php
}

function save($db_pre)
{
    
	$postid=SafeRequest(getPGC('id'),0);
	$name=SafeRequest(getPGC('name'),0);
	$info=SafeRequest(getPGC('info'),0);

	
	if($name=="")
	{
	    //echo $plusattribute;
	    echo "<script>alert('没有添加插件属性');history.back();</script>";
	    exit;
	}
	
	if(($postid!="") && ($postid!="0"))
	{
	    $wheremap = array();
	    $updatedata = array();
	    $wheremap['id'] = $postid;
	    $updatedata["name"] = $name;
	    $updatedata["info"] = $info;
	    D($db_pre . "plus_attribute")->where($wheremap)->LinkUpdate($updatedata);
	    echo "<script>alert('更新成功');window.location.href='admin_plus.php';</script>";
	}
	else
	{
	    $bind=array("name"=>$name,"info"=>$info);
	   	D($db_pre . "plus_attribute")->LinkInsert($bind);
	    //$sql="insert into ".$db_pre."plus_attribute(name,info) values(:name,:info)";
	    //$conn->update($sql,$bind);

	    echo "<script>alert('更新成功');window.location.href='admin_plus_attribute.php';</script>";
	}
	
}

function del($db_pre)
{
	$postid=SafeRequest(getPGC('id'),0);

	if(($postid!="") && ($postid!="0"))
	{
	    $bind = array(
	        "id" => $postid
	    );
	    D($db_pre . "plus_attribute")->LinkDelete($bind);
		echo "插件已经删除。<br>";
	}
}
?>
	</div>
  <!--  -->
  </div>
</div>
include (ZH_PATH . DS . 'manager' . DS . 'foot' . ZH);
</body>
</html>