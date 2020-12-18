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
    <div id="breadcrumb"> <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="admin_plus.php" class="current">插件管理</a> </div>
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
    <th>插件名称</th>
    <th>插件属性</th>
    <th>插件类型</th>
    <th>插件路径</th>
	<th>操作</th>
  </tr>
              </thead>
              <tbody>
  <?php
        $rs = D("" . $db_pre . "plus_common")->order('id desc')->getLinkAll("id,plusname,plusattribute,plustype,pluspath", true);
        $datas = $rs['datas'];
        $rows = $rs['rows'];
	for($j=0;$j<$rows;$j++)
	{
		$data = $datas[$j];
	?>
  <tr> 
    <td><?php echo $data['id'];?></td>
    <td><?php echo $data['plusname'];?></td>
    <td><?php echo $data['plusattribute'];?></td>
    <td><?php echo $data['plustype'];?></td>
    <td><?php echo $data['pluspath'];?></td>
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
	$conn = D("" . $db_pre . "plus_common");
	if(($postid!="") && ($postid!=0))
	{
            $bind=array("id"=>$postid);
            $data = $conn->where($bind)->getLinkOne("id,plusname,plusattribute,plustype,pluspath,plusrootpath");
            $plusname=$data['plusname'];
            $plusattribute=$data['plusattribute'];
            $plustype=$data['plustype'];
            $pluspath=$data['pluspath'];
            $plusrootpath=$data['plusrootpath'];
	}
	else
	{
	    $plusname="";
	    $plusattribute="";
	    $plustype="";
	    $pluspath="";
	    $plusrootpath="";
	}
?>
    <form action="?atcion=save&id=<?php echo $postid;?>" id="form1" name="form1" method="post" class="form-horizontal">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>插件管理</h5>
          </div>
          <div class="widget-content">
          <!-- 表单 -->
          	<div class="control-group">
            	<label class="control-label">插件名称:</label>
                  <div class="controls">
                    <input type="text" name="plusname" class="span" placeholder="" value="<?php echo $plusname; ?>" />
                  </div>
        	</div>
<?php
$sql2 = "SELECT c1.`id` , c1.`name` as `interfacename`, c1.`attributeid`, c2.`name` as `attributename` FROM `".$db_pre."plus_interface` as c1 left join `".$db_pre."plus_attribute` as c2 on c1.attributeid=c2.id order by `id`";

$datas2=$conn->getSqlAll($sql2);
$rows2=$conn->getRowCount();
?>
<script language="JavaScript">
var onecount2;
onecount2=0;
subcat2 = new Array();
<?php
$count2 = 0;
for($i2=0;$i2<$rows2;$i2++)
{
    $data2 = $datas2[$i2];
?>
subcat2[<?php echo $count2;?>] = new Array("<?php echo $data2['interfacename'];?>","<?php echo $data2['attributename'];?>","<?php echo $data2['interfacename'];?>");
<?php
$count2 = $count2 + 1;
}
?>
onecount2=<?php echo $count2;?>;
function changelocation2(locationid2)
{
 document.form1.plustype.length = 0; 
 var locationid2=locationid2;
 var i2;
 for (i2=0;i2 < onecount2; i2++)
	{
	 if (subcat2[i2][1] == locationid2)
		{
		document.form1.plustype.options[document.form1.plustype.length] = new Option(subcat2[i2][0], subcat2[i2][2]);
		}
	}
}    
</script>
        	<div class="control-group">
            	<label class="control-label">插件属性:</label>
                  <div class="controls">
                    <select name="plusattribute" onChange="changelocation2(document.form1.plusattribute.options[document.form1.plusattribute.selectedIndex].value)" size="1">
      <option value=0>==插件属性==</option>
<?php
$sql="select `id`,`name` from `".$db_pre."plus_attribute` order by `id`";
$datas=$conn->getSqlAll($sql);
$rows=$conn->getRowCount();
for($i=0;$i<$rows;$i++)
{
    $data = $datas[$i];
?>
<option value="<?php echo $data['name'];?>"><?php echo $data['name'];?></option>
<?php
}
?>
      </select>
				  </div>
        	</div>
        	<div class="control-group">
            	<label class="control-label">插件接口类型:</label>
                  <div class="controls">
                    <select name="plustype"><option value=0>==插件接口类型==</option></select>
                  </div>
        	</div>
        	<div class="control-group">
            	<label class="control-label">插件类路径:</label>
                  <div class="controls">
                    <input type="text" name="pluspath" class="span" placeholder="" value="<?php echo $pluspath; ?>" />
                  </div>
        	</div>
        	<div class="control-group">
            	<label class="control-label">插件根路径:</label>
                  <div class="controls">
                    <input type="text" name="plusrootpath" class="span" placeholder="" value="<?php echo $plusrootpath; ?>" />
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

function save()
{
	$postid=SafeRequest(getPGC('id'),0);
	$plusname=SafeRequest(getPGC('plusname'),0);
	$plusattribute=SafeRequest(getPGC('plusattribute'),0);
	$plustype=SafeRequest(getPGC('plustype'),0);
	$pluspath=SafeRequest(getPGC('pluspath'),0);
	$plusrootpath=SafeRequest(getPGC('plusrootpath'),0);
	
	if($plusattribute=="0")
	{
	    //echo $plusattribute;
	    echo "<script>alert('没有选择插件属性');history.back();</script>";
	    exit;
	}
	
	if($plustype=="0")
	{
	    echo "<script>alert('没有选择插件接口类型');history.back();</script>";
	    exit;
	}
	
	if(($postid!="") && ($postid!="0"))
	{
            $wheremap = array("id" => $postid);
            $updatedata = array("plusname"=>$plusname,"plusattribute"=>$plusattribute,"plustype"=>$plustype,"pluspath"=>$pluspath,"plusrootpath"=>$plusrootpath);
            D("".$db_pre."plus_common")->where($wheremap)->LinkUpdate($updatedata);
	    echo "<script>alert('更新成功');window.location.href='admin_plus.php';</script>";
	}
	else
	{
	    if(is_file(ZH_PATH.$plusrootpath.DIRECTORY_SEPARATOR."flock.php")==true)
	    {
	        echo "<script>alert('".$plusname."已经安装过了');history.back();</script>";
	        exit;
	    }
	    
            $updatedata = array("plusname"=>$plusname,"plusattribute"=>$plusattribute,"plustype"=>$plustype,"pluspath"=>$pluspath,"plusrootpath"=>$plusrootpath);
            D($db_pre."plus_common")->LinkInsert($updatedata);
	    
	    //建立文件
	    $c="1";
	    $filepath=ZH_PATH.$plusrootpath.DIRECTORY_SEPARATOR."flock.php";
	    //echo $filepath;
	    $fp = fopen($filepath,'w');
	    fwrite($fp,$c);
	    fclose($fp);
	    
	    echo "<script>alert('更新成功');window.location.href='admin_plus.php';</script>";
	}
	
	
	//进行安装插件
	
	//执行安装文件
	//echo "<script>window.location.href='/".$mulu."/install/install.php?qianzui=".$biaoqianzhui."&mulu=".$mulu."&name=".$name."&modulename=".$modulename."';</script>";
	exit;
	
}

function del()
{
	$postid=SafeRequest(getPGC('id'),0);

	if(($postid!="") && ($postid!="0"))
	{
                $wheremap = array("id" => $postid);
                D($db_pre."plus_common")->where($wheremap)->LinkDelete();
		echo "插件已经删除。<br>";
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