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
    <a href="admin_articlecontent.php" class="current">管理首页</a> </div>
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
            <th>标题</th>
            <th>添加时间</th>
            <th>浏览</th>
            <th>是否审核</th>
            <th>是否置顶</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
<?php
    /*
    $rs=D("art_articlecontent")->getLinkAll("*",true);
    $pages = $rs["rows"];
    */
    $rs1=new \ARTICLE\D\Articlecontent();
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
		$rs=new \ARTICLE\D\Articlecontent();
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
   <td><?php echo $data['columnname']; ?></td>
    <td><?php echo $data['title']; ?></td>
    <td><?php echo $data['addtime']; ?></td>
    <td><?php echo $data['viewnum']; ?></td>
    <td><?php 
    if($data['ispass']==1)
        echo "已审";
    else
        echo "未审";
    ?></td>
    <td>
    <?php 
    if($data['istop']==1)
        echo "置顶";
    else
        echo "未置顶";
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
		$rs=new \ARTICLE\D\Articlecontent();
		$data=$rs->getOne($postid);
		$rows=$rs->getRows();
		If($rows!=0)
		{
            $ilistid=$postid;
		    
                    $mainkey=$data['mainkey'];
                    
                    $columnid=$data['columnid'];
                    
                    $columnname=$data['columnname'];
                    
                    $title=$data['title'];
                    
                    $addtime=$data['addtime'];
                    
                    $content=$data['content'];
                    
                    $keyword=$data['keyword'];
                    
                    $fromname=$data['fromname'];
                    
                    $viewnum=$data['viewnum'];
                    
                    $byte=$data['byte'];
                    
                    $tcolor=$data['tcolor'];
                    
                    $author=$data['author'];
                    
                    $img=$data['img'];
                    
                    $isimg=$data['isimg'];
                    
                    $isdel=$data['isdel'];
                    
                    $ispass=$data['ispass'];
                    
                    $istop=$data['istop'];
                    
                    $contentlistid=$data['contentlistid'];
                    
                    $adminid=$data['adminid'];
                    
                    $cjroot=$data['cjroot'];
                    
		}
		else
		{
            $ilistid="";
		    
                    $mainkey='';
                    
                    $columnid='';
                    
                    $columnname='';
                    
                    $title='';
                    
                    $addtime='';
                    
                    $content='';
                    
                    $keyword='';
                    
                    $fromname='';
                    
                    $viewnum='';
                    
                    $byte='';
                    
                    $tcolor='';
                    
                    $author='';
                    
                    $img='';
                    
                    $isimg='';
                    
                    $isdel='';
                    
                    $ispass='';
                    
                    $istop='';
                    
                    $contentlistid='';
                    
                    $adminid='';
                    
                    $cjroot='';
                    
        }
	}
    else
    {
       $ilistid="";
	   
                    $mainkey='';
                    
                    $columnid='';
                    
                    $columnname='';
                    
                    $title='';
                    
                    $addtime='';
                    
                    $content='';
                    
                    $keyword='';
                    
                    $fromname='';
                    
                    $viewnum='';
                    
                    $byte='';
                    
                    $tcolor='';
                    
                    $author='';
                    
                    $img='';
                    
                    $isimg='';
                    
                    $isdel='';
                    
                    $ispass='';
                    
                    $istop='';
                    
                    $contentlistid='';
                    
                    $adminid='';
                    
                    $cjroot='';
                    
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
                            	<label class="control-label">标题:</label>
                                  <div class="controls">
                                    <input type="text" name="title" class="span" placeholder="" 
                                    value="<?php echo $title; ?>" />
                                  </div>
                        	</div>
                            <div class="control-group">
                            	<label class="control-label">所属栏目:</label>
                                  <div class="controls">
                                    <select name="columnid">
                                    <option value="0">==请选择==</option>
<?php 
//获取所有的栏目
$rs=new \ARTICLE\D\Articlecolumn();
$datas=$rs->getAll();
print_r($datas);
$newsfun=new \ARTICLE\B\ClsFun();
$newsfun->data2arr($datas,0,0,$columnid);
echo $newsfun->getData2();
$newsfun=null;
$rs=null;
?>
									</select>
                                  </div>
                        	</div>
                             
                           <div class="control-group">
                            	<label class="control-label">关键词:</label>
                                  <div class="controls">
                                    <input type="text" name="keyword" class="span" placeholder="" 
                                    value="<?php echo $keyword; ?>" />
                                  </div>
                        	</div>
                             <div class="control-group">
                            	<label class="control-label">来源:</label>
                                  <div class="controls">
                                    <input type="text" name="fromname" class="span" placeholder="" 
                                    value="<?php echo $fromname; ?>" />
                                  </div>
                        	</div>
                             <div class="control-group">
                            	<label class="control-label">浏览数:</label>
                                  <div class="controls">
                                    <input type="text" name="viewnum" class="span" placeholder="" 
                                    value="<?php echo $viewnum; ?>" />
                                  </div>
                        	</div>
                          <div class="control-group">
                            	<label class="control-label">作者:</label>
                                  <div class="controls">
                                    <input type="text" name="author" class="span" placeholder="" 
                                    value="<?php echo $author; ?>" />
                                  </div>
                        	</div>
                             <div class="control-group">
                            	<label class="control-label">是否审核:</label>
                                  <div class="controls">
                                 <label>
                            <input type="radio" name="ispass" value="0" <?php
                            if ($ispass == "0") { echo "checked";
                            }
                            ?>>未审核
                            </label><label>
                            <input type="radio" name="ispass" value="1" <?php
                            if ($ispass == "1") { echo "checked";
                            }
                            ?>>已审核
                            </label>
                            </div>
        	               </div>
        	              
                          <div class="control-group">
                            	<label class="control-label">标题颜色:</label>
                                  <div class="controls">
                           			<input type="text" name="tcolor" class="span" placeholder="" 
                                    value="<?php echo $tcolor; ?>" />
                                  </div>
                        	</div>  
                       
                          <div class="control-group">
                            	<label class="control-label">图片地址:</label>
                                  <div class="controls">
                                    
                                    <input name="img" type="text" class="span"
				value="<?php echo $img; ?>" ><br /> &nbsp;<iframe
					name="up" frameborder="0" width="500" height="60" scrolling="no"
					src="uploadfile.php"></iframe>
      <?php If($img) { ?>
      <a href="<?php echo $img; ?>" target="_blank"> <img
					src="<?php echo $img; ?>" alt="" height="60" width="90" />预览图片
			</a>
      <?php } ?>
                                  </div>
                        	</div>
                             <div class="control-group">
                            	<label class="control-label">是否置顶:</label>
                                  <div class="controls">
                                 <label>
                            <input type="radio" name="istop" value="1" <?php
                            if ($istop == "1") { echo "checked";
                            }
                            ?>>置顶
                            </label><label>
                            <input type="radio" name="istop" value="0" <?php
                            if ($istop == "0") { echo "checked";
                            }
                            ?>>不置顶
                            </label></div>
        	               </div>
        	               <div class="control-group">
                            	<label class="control-label">内容:</label>
                                  <div class="controls">
                                   	<script type="text/javascript" charset="utf-8" src="/common/tool/ueditor/ueditor.config.js"></script>
                                    <script type="text/javascript" charset="utf-8" src="/common/tool/ueditor/ueditor.all.js"> </script>
                                    <script type="text/javascript" charset="utf-8" src="/common/tool/ueditor/lang/zh-cn/zh-cn.js"></script>
                                    <script id="contenttxt" name="contenttxt" type="text/plain" style="width:800px;height:500px;"><?php echo $content; ?></script>
                                    <script type="text/javascript">
                                        var ue = UE.getEditor('contenttxt');
                                        ue.ready(function() {});
                                    </script>
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
                   
                    $columnid=SafeRequest(getPGC("columnid"),0);
                    if($columnid==0)
                    {
                        echo "<script>alert('栏目没有选择，请重试');history.go(-1);</script>";
                        exit;
                    }
                    $rs=new \ARTICLE\D\Articlecolumn();
                    $data=$rs->getOne($columnid);
                    $columnname=$data['name'];
                    $rs=null;
                   
                    $title=SafeRequest(getPGC("title"),0);
                   
                    $addtime=SafeRequest(getPGC("addtime"),0);
                   
                    $content=getPGC("contenttxt");
                   
                    $keyword=SafeRequest(getPGC("keyword"),0);
                   
                    $fromname=SafeRequest(getPGC("fromname"),0);
                   
                    $viewnum=SafeRequest(getPGC("viewnum"),0);
                   
                    $byte=strlen($content);
                   
                    $tcolor=SafeRequest(getPGC("tcolor"),0);
                   
                    $author=SafeRequest(getPGC("author"),0);
                   
                    $img=SafeRequest(getPGC("img"),0);
                    if($img)
                    {
                        $isimg='1';
                    }
                    else
                    {
                        $isimg='0';
                    }
                    $isdel=SafeRequest(getPGC("isdel"),0);
                    if(!$isdel)
                    {
                        $isdel='0';
                    }
                    $ispass=SafeRequest(getPGC("ispass"),0);
                    if(!$ispass)
                    {
                        $ispass='0';
                    }
                    $istop=SafeRequest(getPGC("istop"),0);
                    if(!$istop)
                    {
                        $istop='0';
                    }
                    $contentlistid=SafeRequest(getPGC("contentlistid"),0);
                    if(!$contentlistid)
                    {
                        $contentlistid='0';
                    }
                    $adminid=$_SESSION['adminid'];
                    
                    $cjroot=SafeRequest(getPGC("cjroot"),0);
                    If($title=="")
                    {
                        $ErrMsg="对不起，标题不符！";
                        $ErrMsg=$ErrMsg."<li>长度等于0或大于250";
                        echo $ErrMsg;
                        exit;
                    }
   
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \ARTICLE\D\Articlecontent();
		$data=$rs->update($postid,$mainkey,$columnid,$columnname,$title,$addtime,$content,$keyword,$fromname,$viewnum,$byte,$tcolor,$author,$img,$isimg,$isdel,$ispass,$istop,$contentlistid,$adminid,$cjroot);
        $ilistid=$postid;
	}
	Else
	{
		$rs=new \ARTICLE\D\Articlecontent();
		$data=$rs->add($mainkey,$columnid,$columnname,$title,$addtime,$content,$keyword,$fromname,$viewnum,$byte,$tcolor,$author,$img,$isimg,$isdel,$ispass,$istop,$contentlistid,$adminid,$cjroot);
        $ilistid=$rs->getLastId();
	}
            
	echo "<script>alert('更新成功');window.location.href='admin_articlecontent.php';</script>";
}
            
function del()
{
	$postid=SafeRequest(getPGC("postid"),0);
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \ARTICLE\D\Articlecontent();
		$rs->delete($postid);
		echo "<script>alert('更新成功');window.location.href='admin_articlecontent.php';</script>";
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
