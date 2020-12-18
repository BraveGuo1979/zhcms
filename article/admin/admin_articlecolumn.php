<?php
namespace ARTICLE\D;
if (! isset($_SESSION)) {
	session_start();
}
include(dirname(dirname(dirname(__FILE__)))."/zhconfig/Config.php");
include(dirname(dirname(__FILE__))."/config.php");
$c = new \ZHCONFIG\ZhConfig();
$db_pre = $c->getDbPre();
            
$isp = new \ZHMVC\D\MANAGER\isPermission();
$isper = $isp->getPermission();
$_curlid = $isp->getCUrl();
            
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
include (ZH_PATH . DS . "manager" . DS . "css" . ZH);
include (ZH_PATH . DS . "manager" . DS . "js" . ZH);
?>
</head>
<body>
<?php
include (ZH_PATH . DS . "manager" . DS . "top" . ZH);
include (ZH_PATH . DS . "manager" . DS . "menu" . ZH);
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="/manager/showphpinfo.php" title="Go to Home" class="tip-bottom">
    <i class="icon-home"></i>主页</a>
    <a href="admin_articlecolumn.php" class="current">管理首页</a> </div>
  	<button class="btn" onClick="location='?atcion=main';" >管理首页</button>
    <button class="btn btn-primary" onClick="location='?atcion=add';" >新增</button>
  </div>
  <div class="container-fluid">
    <!--  -->
    <div class="row-fluid">
<?php
$action=SafeRequest(getPGC("atcion"),0);
switch ($action) {
    case "save":
        save();
        break;
    case "add":
        add();
        break;
    case "del":
        del();
        break;
    default:
        main();
}
            
function main()
{
?>
<div class="widget-box">
<div class="widget-content nopadding">
	<table class="table table-striped table-bordered">
		<thead>
                <tr>
            <th>Id</th>
            <th>栏目名称</th>
            <th>主栏目名称</th>
            <th>类型</th>
    		<th>栏目类型</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
<?php
    /*
    $rs=D("art_articlecolumn")->getLinkAll("*",true);
    $pages = $rs["rows"];
    */
    $rs1=new \ARTICLE\D\Articlecolumn();
    $datas=$rs1->getAllNum();
    $pages = $datas["num"];
	If($pages<=0)
	{
		$OutStr="<tr>";
		$OutStr=$OutStr."<td colspan=10>&nbsp;<font color=\"red\">暂无内容</font></td>";
		$OutStr=$OutStr."</tr></tbody>";
		echo $OutStr;
	}
	Else
	{
        $pa = new \ZHMVC\B\TOOL\ShowPages();
		$pa->pvar="pg";
		$pa->set(20,$pages);
		$rs=new \ARTICLE\D\Articlecolumn();
		$datas=$rs->getPages($pa->limit());
		$rows=$rs->getRows();
		$rs=null;
		//print_r($datas);
	    for($i=0;$i<$rows;$i++)
	    {
	    	$data=$datas[$i];
  ?>
  <tr>
    <td><?php echo $data["id"]; ?></td>
    <td><?php echo $data['name']; ?></td>
    <td><?php
  if($data['fid']==0)
  {
  	  echo "根栏目";
  }
  else
 {
	  $where="id='".$data['fid']."'";
	  $rs1=D("art_articlecolumn")->where($where)->getLinkOne("*");
	  echo $rs1['name'];
	  $rs1=null;
  }
   ?></td>
    <td><?php 
   if($data['type'] == "2")
   {
   	   echo "大分类(不可发内容)";
   }
   elseif($data['type'] == "1")
   {
   	   echo "小栏目";
   }
   ?></td>
   	<td><?php 
   if($data['columntype'] == "2")
   {
   	   echo "单文章对应多文章";
   }
   elseif($data['columntype'] == "1")
   {
   	   echo "文章列表";
   }
   elseif($data['columntype'] == "1")
   {
   	   echo "单文章";
   }
   ?></td>
                        
    <td class="taskOptions"><a href="?atcion=add&postid=<?php echo $data["id"]; ?>">编辑</a> | <a href="?atcion=del&postid=<?php echo $data["id"]; ?>" onclick="{if(confirm('确定删除吗?')){return true;}return false;}">删除</a></td>
  </tr>
<?php
}
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10">&nbsp;<?php $pa -> output(0); ?></td>
        </tr>
    </tfoot>
<?php
}
?>
</table>
<?php
}
            
function add()
{
	$postid=SafeRequest(getPGC("postid"),0);
            
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \ARTICLE\D\Articlecolumn();
		$data=$rs->getOne($postid);
		$rows=$rs->getRows();
		If($rows!=0)
		{
            $ilistid=$postid;
		    
                    $mainkey=$data['mainkey'];
                    
                    $name=$data['name'];
                    
                    $fid=$data['fid'];
                    
                    $type=$data['type'];
                    
                    $columntype=$data['columntype'];
                    
                    $info=$data['info'];
                    
		}
		else
		{
            $ilistid="";
		    
                    $mainkey='';
                    
                    $name='';
                    
                    $fid='';
                    
                    $type='';
                    
                    $columntype='';
                    
                    $info='';
                    
        }
	}
    else
    {
       $ilistid="";
	   
                    $mainkey='';
                    
                    $name='';
                    
                    $fid='';
                    
                    $type='';
                    
                    $columntype='';
                    
                    $info='';
                    
    }
?>
<form name="PForm" id="PForm" method="post" action="?atcion=save&postid=<?php echo $postid; ?>" class="form-horizontal">
	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>管理</h5>
        </div>
        <div class="widget-content">
          <!-- 表单 -->
            
                            <div class="control-group">
                            	<label class="control-label">栏目名称:</label>
                                  <div class="controls">
                                    <input type="text" name="name" class="span" placeholder="" 
                                    value="<?php echo $name; ?>" />
                                  </div>
                        	</div>
                             
                            <div class="control-group">
                            	<label class="control-label">所属分类:</label>
                                  <div class="controls">
                                    <select name="fid">
<option value="0">==根栏目==</option>
<?php 
//获取所有的栏目
$rs1=new \ARTICLE\D\Articlecolumn();
$datas=$rs1->getAll();
$newsfun=new \ARTICLE\B\ClsFun();
$newsfun->data2arr($datas,0,0,$fid);
echo $newsfun->getData2();
$newsfun=null;
$rs1=null;
?>								
									</select>
                                  </div>
                        	</div>
                             
                           <div class="control-group">
                            	<label class="control-label">类型:</label>
                                  <div class="controls">
                                  <label>
                                    <input type="radio" name="type" value="2" <?php
                            if ($type == "2") { echo "checked";
                            }
                            ?>>大分类(不可发内容)
                            </label><label>
                            <input type="radio" name="type" value="1" <?php
                            if ($type == "1") { echo "checked";
                            }
                            ?>>小栏目 
                            </label>
                                  </div>
                        	</div>
                             <div class="control-group">
                            	<label class="control-label">栏目类别:</label>
                                  <div class="controls">
                                 <label>
                            <input type="radio" name="columntype" value="2" <?php
                            if ($columntype == "2") { echo "checked";
                            }
                            ?>>单文章对应多文章
                            </label><label>
                            <input type="radio" name="columntype" value="1" <?php
                            if ($columntype == "1") { echo "checked";
                            }
                            ?>>文章列表
                            </label><label>
                            <input type="radio" name="columntype" value="0" <?php
                            if ($columntype == "0") { echo "checked";
                            }
                            ?>>单文章
                            </label></div>
        	               </div>
                          <div class="control-group">
                            	<label class="control-label">描述:</label>
                                  <div class="controls">
                                    <input type="text" name="info" class="span" placeholder="" 
                                    value="<?php echo $info; ?>" />
                                  </div>
                        	</div>
                             
            <div class="form-actions">
            	<button type="submit" class="btn btn-success">保存</button>
            </div>
          <!-- 表单 -->
        </div>
    </div>
</form>
<?php
}
            
function save()
{
	$postid=SafeRequest(getPGC("postid"),0);
            
    
                    $mainkey=SafeRequest(getPGC("mainkey"),0);
                   
                    $name=SafeRequest(getPGC("name"),0);
                   
                    $fid=SafeRequest(getPGC("fid"),0);
                   
                    $type=SafeRequest(getPGC("type"),0);
                   
                    $columntype=SafeRequest(getPGC("columntype"),0);
                   
                    $info=SafeRequest(getPGC("info"),0);
                   
   
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \ARTICLE\D\Articlecolumn();
		$data=$rs->update($postid,$mainkey,$name,$fid,$type,$columntype,$info);
        $ilistid=$postid;
	}
	Else
	{
		$rs=new \ARTICLE\D\Articlecolumn();
		$data=$rs->add($mainkey,$name,$fid,$type,$columntype,$info);
        $ilistid=$rs->getLastId();
	}
            
	echo "<script>alert('更新成功');window.location.href='admin_articlecolumn.php';</script>";
}
            
function del()
{
	$postid=SafeRequest(getPGC("postid"),0);
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \ARTICLE\D\Articlecolumn();
		$rs->delete($postid);
		echo "<script>alert('更新成功');window.location.href='admin_articlecolumn.php';</script>";
	}
}
?>
	</div>
  <!--  -->
  </div>
</div>
<?php include (ZH_PATH . DS . "manager" . DS . "foot" . ZH);?>
</body>
</html>
