<?php
namespace ZHMVC\B\TOOL;

/*
 * 根据表信息生成文件Dao文件
 */
class TableToFile
{
    private $_table;
    private $_file1;
    private $_file2;
    private $_path1;
    private $_path2;
    private $_pre;
    private $_content1;
    // 数据管理类
    private $_content2;
    // 后台文件
    private $_content4;
    // api接口文件
    private $_content3;
    // rpc数据服务类
    // 数据类型，本地还是远程
    private $_rpctype;
    // 如果是远程的话远程地址
    private $__rpcurl;
    // rpc服务站点唯一key
    private $_rpcmainkey;
    // rpc服务站点模块id         
    private $_rpcmoduleid;
    // rpc对接私钥
    private $_rpcprivatekey;

    public function __construct($pre, $tablename, $pathname1, $pathname2, $filename1, $filename2, $modulename, $rpctype = '本地', $rpcurl = '', $rpcmainkey = '',$rpchost='',$rpcprivatekey='',$rpcmoduleid=1)
    {
        $this->_table = $pre . $tablename;
        $this->_file1 = $filename1;
        $this->_file2 = $filename2;
        $this->_path1 = $pathname1;
        $this->_path2 = $pathname2;
        $this->_rpctype = $rpctype;
        $this->_rpcurl = $rpcurl;
        $this->_rpcmainkey = $rpcmainkey;
        $this->_rpcprivatekey = $rpcprivatekey;
        $this->_rpcmoduleid = $rpcmoduleid;
        
        $this->_pre = '';
        
        if ($rpctype == "本地") {
            // 本地数据
            $this->_content3='<?php
namespace ' . strtoupper($modulename) . '\\D;
class ' . $this->_file1 . 'rpc extends \\ZHMVC\\D\\DataBase
{
    public function getAllNum($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            //var_dump($tokenR);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                if(is_array($parameter)==true)
                {
                    $bind=array();
                    $s="";
                    $tempS=parent::parseSql($parameter);
                    $bind=$tempS["bind"];
                    $s=$tempS["s"];
                    if($s=="")
                    {
                        $sql="select count(*) as num from ' . $this->_table . ' where `mainkey`=\'".$mainkey."\'";
                        $data=$this->_db->getOne($sql);
                    }
                    else
                    {
                            //去掉第一个逻辑符号
                            $s_a=explode(" ", $s);
                            $tempS="";
                            for($j=1;$j<count($s_a);$j++)
                            {
                                $tempS.=$s_a[$j]." ";
                            }
    
                            $sql="select count(*) as num from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' and ".$tempS;
                            $data=$this->_db->getOne($sql,$bind);
                    }
                }
                elseif($parameter!="")
                {
                    $sql="select count(*) as num from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' and ".$parameter;
                    $data=$this->_db->getOne($sql);
                }
                else
                {
                    $sql="select count(*) as num from ' . $this->_table . '  where  `mainkey`=\'".$mainkey."\'";
                    $data=$this->_db->getOne($sql);
                }
                //echo $sql;
                if(empty($data)==true)
                {
                    $data["num"]=0;
                }
                $this->_rows=$this->_db->getRowCount();
                return $data;
            }
        } else {
            return "error";
        }
    }
            
    public function getAll($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                if(is_array($parameter)==true)
                {
                    $bind=array();
                    $s="";
                    $tempS=parent::parseSql($parameter);
                    $bind=$tempS["bind"];
                    $s=$tempS["s"];
    
                    if($s=="")
                    {
                        $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\'";
                        $datas=$this->_db->getAll($sql);
                    }
                    else
                    {
                        $s_a=explode(" ", $s);
                        $tempS="";
                        for($j=1;$j<count($s_a);$j++)
                        {
                            $tempS.=$s_a[$j]." ";
                        }
                        $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' and ".$tempS;
                        $datas=$this->_db->getAll($sql,$bind);
                    }
                }
                elseif($parameter!="")
                {
                    $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' ".$parameter."";
                    $datas=$this->_db->getAll($sql);
                }
                else
                {
                    $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\'";
                    $datas=$this->_db->getAll($sql);
                }
    
                $this->_rows=$this->_db->getRowCount();
                return $datas;
            }
        } else {
            return "error";
        }
    }
    
    public function getPages($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
    
            if($tokenR==false)
            {
                return "error";
            }
            else
            {

                if(count($map)==7)
                {
                    $limit=$map["parameter"];
                    $parameter="";
                }
                else 
                {

                    $parameter=$map["parameter"];
                    $limit=$map["limit"];
                }
                if(is_array($parameter)==true)
                {
                    $bind=array();
                    $s="";
                    $tempS=parent::parseSql($parameter);
                    $bind=$tempS["bind"];
                    $s=$tempS["s"];
                    if($s=="")
                    {
                        $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' order by id desc LIMIT ".$limit."";
                        $datas=$this->_db->getAll($sql);
                    }
                    else
                    {
                        $s_a=explode(" ", $s);
                        $tempS="";
                        for($j=1;$j<count($s_a);$j++)
                        {
                            $tempS.=$s_a[$j]." ";
                        }
                        $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' and ".$tempS." order by id desc LIMIT ".$limit."";
                        $datas=$this->_db->getAll($sql,$bind);
                    }
                }
                elseif($parameter!="")
                {
                    $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' and ".$parameter." order by id desc LIMIT ".$limit."";
                    $datas=$this->_db->getAll($sql);
                }
                else
                {
                    $sql="select * from ' . $this->_table . ' where  `mainkey`=\'".$mainkey."\' order by id desc LIMIT ".$limit."";
                    $datas=$this->_db->getAll($sql);
                }
                $this->_rows=$this->_db->getRowCount();
                return $datas;
            }
        } else {
            return "error";
        }
    }
            
    public function getOne($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
    
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $postid=$mapnew["parameter"];
                $sql="select * from ' . $this->_table . ' where `mainkey`=\'".$mainkey."\' and id=:id";
                $bind=array(":id"=>$postid);
                $data=$this->_db->getOne($sql,$bind);
                $this->_rows=$this->_db->getRowCount();
                return $data;
            }
        } else {
            return "error";
        }
    }
            
    public function add($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
    
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                $sql="insert into ' . $this->_table . ' ({@InsertPRPC@}) values ({@InsertVRPC@})";
                $bind=array({@InsertARPC@});
                $this->_db->update($sql,$bind);
                $this->_lastid=$this->_db->getLastId();
                return $this->_lastid;
            }
        } else {
            return "error";
        }
    }
            
    public function update($map)
    {
        if (is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                $sql="update ' . $this->_table . ' set {@UpdateVRPC@} where `mainkey`=\'".$mainkey."\' and id=:id";
                $bind=array({@UpdateARPC@});
                $this->_db->update($sql,$bind);
                return 1;
            }
        } else {
            return "error";
        }
    }
            
    public function delete($map)
    {
        if (is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $id=$mapnew["parameter"];
                $sql="delete from ' . $this->_table . ' where `mainkey`=\'".$mainkey."\' and id=:id";
                $bind=array(":id"=>$id);
                $this->_db->update($sql,$bind);
                return 1;
            }
        } else {
            return "error";
        }
    }
}       
 ';
       
       
        $this->_content1 = '<?php
namespace ' . strtoupper($modulename) . '\\D;
class ' . $this->_file1 . ' extends \\ZHMVC\\D\\DataBase
{
    public function getAllNum($parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"];
            
            if($s=="")
            {
                $sql="select count(*) as num from ' . $this->_table . '";
                $data=$this->_db->getOne($sql);
            }
            else
            {
                //去掉第一个逻辑符号
                $s_a=explode(" ", $s);
                $tempS="";
                for($j=1;$j<count($s_a);$j++)
                {
                    $tempS.=$s_a[$j]." ";
                }
            
                $sql="select count(*) as num from ' . $this->_table . ' where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from ' . $this->_table . ' where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from ' . $this->_table . '";
            $data=$this->_db->getOne($sql);
        }
        if(empty($data)==true)
        {
            $data["num"]=0;
        }
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
            
    public function getAll($parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"];
            
            if($s=="")
            {
                $sql="select * from ' . $this->_table . '";
                $datas=$this->_db->getAll($sql);
            }
            else
            {
                $s_a=explode(" ", $s);
                $tempS="";
                for($j=1;$j<count($s_a);$j++)
                {
                    $tempS.=$s_a[$j]." ";
                }
                $sql="select * from ' . $this->_table . ' where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ' . $this->_table . ' where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ' . $this->_table . '";
            $datas=$this->_db->getAll($sql);
        }
   
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function getRows()
    {
        return $this->_rows;
    }
            
    public function getPages($limit,$parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"];
            if($s=="")
            {
                $sql="select * from ' . $this->_table . ' order by id desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql);
            }
            else
            {
                $s_a=explode(" ", $s);
                $tempS="";
                for($j=1;$j<count($s_a);$j++)
                {
                    $tempS.=$s_a[$j]." ";
                }
                $sql="select * from ' . $this->_table . ' where ".$tempS." order by id desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ' . $this->_table . ' where ".$parameter." order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ' . $this->_table . ' order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function getOne($postid)
    {
        $sql="select * from ' . $this->_table . ' where id=:id";
        $bind=array(":id"=>$postid);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
            
    public function getOne1($parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"];
            
            if($s=="")
            {
                $sql="select * from ' . $this->_table . '";
                $datas=$this->_db->getOne($sql);
            }
            else
            {
                $s_a=explode(" ", $s);
                $tempS="";
                for($j=1;$j<count($s_a);$j++)
                {
                    $tempS.=$s_a[$j]." ";
                }
                $sql="select * from ' . $this->_table . ' where ".$tempS;
                $datas=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ' . $this->_table . ' where ".$parameter."";
            $datas=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select * from ' . $this->_table . '";
            $datas=$this->_db->getOne($sql);
        }
   
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function add({@InsertB1@})
    {
        $sql="insert into ' . $this->_table . ' ({@InsertP@}) values ({@InsertV@})";
        $bind=array({@InsertA@});
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return $this->_lastid;
    }
            
    public function update({@UpdateB1@})
    {
        $sql="update ' . $this->_table . ' set {@UpdateV@} where id=:id";
        $bind=array({@UpdateA@});
        $this->_db->update($sql,$bind);
        return 1;
    }
            
    public function delete($id)
    {
        $sql="delete from ' . $this->_table . ' where id=:id";
        $bind=array(":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
            
    public function getLastId(){
        return $this->_lastid;
    }
}';
    $this->_content2 = '<?php
namespace ' . strtoupper($modulename) . '\\D;
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
    <a href="' . $this->_file2 . '.php" class="current">管理首页</a> </div>
  	<button class="btn" onClick="location=\'?atcion=main\';" >管理首页</button>
    <button class="btn btn-primary" onClick="location=\'?atcion=add\';" >新增</button>
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
            {@MainTh@}
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
<?php
    /*
    $rs=D("' . $this->_table . '")->getLinkAll("*",true);
    $pages = $rs["rows"];
    */
    $rs1=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
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
        $pa = new \\ZHMVC\\B\\TOOL\\ShowPages();
		$pa->pvar="pg";
		$pa->set(20,$pages);
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
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
    {@MainTd@}
    <td class="taskOptions"><a href="?atcion=add&postid=<?php echo $data["id"]; ?>">编辑</a> | <a href="?atcion=del&postid=<?php echo $data["id"]; ?>" onclick="{if(confirm(\'确定删除吗?\')){return true;}return false;}">删除</a></td>
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
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$data=$rs->getOne($postid);
		$rows=$rs->getRows();
		If($rows!=0)
		{
            $ilistid=$postid;
		    {@Add1@}
		}
		else
		{
            $ilistid="";
		    {@Add2@}
        }
	}
    else
    {
       $ilistid="";
	   {@Add3@}
    }
?>
<form name="PForm" id="PForm" method="post" action="?atcion=save&postid=<?php echo $postid; ?>" class="form-horizontal">
	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>管理</h5>
        </div>
        <div class="widget-content">
          <!-- 表单 -->
            {@AddTd@}
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
            
    {@SaveP@}
   
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$data=$rs->update({@SaveB@});
        $ilistid=$postid;
	}
	Else
	{
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$data=$rs->add({@SaveA@});
        $ilistid=$rs->getLastId();
	}
            
	echo "<script>alert(\'更新成功\');window.location.href=\'' . $this->_file2 . '.php\';</script>";
}
            
function del()
{
	$postid=SafeRequest(getPGC("postid"),0);
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$rs->delete($postid);
		echo "<script>alert(\'更新成功\');window.location.href=\'' . $this->_file2 . '.php\';</script>";
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
';
            
            $this->_content4='<?php
namespace '.strtoupper($modulename).'\\D;
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
// 制定允许其他域名访问
header("Access-Control-Allow-Origin:*");
// 响应类型
header("Access-Control-Allow-Methods:POST");
// 响应头设置
header("Access-Control-Allow-Headers:x-requested-with, content-type");
ini_set("date.timezone","Asia/Shanghai");
define("SIGNATURE","BRAVEGUO");
define("ISZHMVCAPIBUG",FALSE);
include(dirname(dirname(dirname(__FILE__)))."/zhconfig/Config.php");
session_start();
if(ISZHMVCAPIBUG==true)
{
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
}
else
{
    error_reporting(0);//E_ALL 0
    ini_set("display_errors", "0");//1 0
}
$token = SafeRequest(getPGC("token"), 0);
$timestamp = SafeRequest(getPGC("timestamp"), 0);
$randomstr = SafeRequest(getPGC("randomstr"), 0);
$data = getPGC("data");
// 获取数据前进行+处理
$data=str_replace("-","+",$data);
$data1 = base64_decode($data);
//$returnS["message1"]=$data1;
$fname=getPGC("fname"); //方法名称
$id=getPGC("id");  //传递的参数
$page=getPGC("page");  //分页
$curpage=getPGC("curpage");  //分页时当前页码
$order=getPGC("order");  //排序
$where=getPGC("where");  //更新时where的条件
$limit=getPGC("limit");  //limit
$sql=getPGC("sql");  //直接使用sql
$b=getPGC("b");  //备用字段
//$returnS["message1"]=$data1;
$datajson=json_decode($data1, true);
$checktoken=checksignature($timestamp,$randomstr);
$checktoken=1;
$returnS = array(
    "code" => "0",
    "data" => array(),
    "msg" => "success",
    "message" => "成功请求",
    "updateDime" => intval(time())
);
$datajson=getZhDecode($data);
if($token==$checktoken)
{
	$returnS["msg"]="ok";
	if(count($datajson)==1)
	{
		//单一参数
        $map=array();
        $mapA=array();
		$updateid=0;
		if($id!="")
		{
		    if(substr_count($id,"{|}")==0)
		    {
		        if(substr_count($id,"{-}")==0)
		        {
		            //不存在{-},$map为一维数组，主要是为了添加
		            $map="";
		            $map=$id;
		        }
		        elseif(substr_count($id,"{+}")>0)
		        {
		            //存在,id为字符串
		            $idA=explode("{+}", $id);
		            $map[$idA[0]]=$idA[1];
		        }
		        else
		        {
		            //存在,id为字符串
		            $idA=explode("{-}", $id);
		            for($i=0,$xnum=count($idA);$i<$xnum;$i++)
		            {
		                $map[$i]=$idA[$i];
		            }
		        }
		    }
		    else
		    {
		        $idA=explode("{|}", $id);
		        //存在
		        for($i=0,$xnum=count($idA);$i<$xnum;$i++)
		        {
		            $idB=explode("{-}", $idA[$i]);
		            $map[$idB[0]]=$idB[1];
                    $mapA[$i]=$idB[1];
		        }
		    }
		}
		
        if($fname=="getOne")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
	        $datas=$rs1->getOne($map);
	        $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getAll")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->getAll($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getAllNum")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->getAllNum($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getPages")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->getAllNum($page,$map="");
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getOne1")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->getOne1($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getSqlAll")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->getSqlAll($sql);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getSqlOne")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->getSqlOne($sql);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="add")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->add({@InsertD@});
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="update")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->update({@UpdateD@});
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="delete")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->delete($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="SqlUpdate")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
            $datas=$rs1->SqlUpdate($sql);
            $returnS["data"]["data"] = $datas;
        }
	}
	else
	{
		//多个参数
	    $returnS["msg"]="error2";
	    $returnS["message"]="多个参数不支持";
	}
}
else
{
    $returnS["msg"]="1";
	$returnS["msg"]="error1";
	$returnS["message"]="token不正确";
}
$result = json_encode($returnS);
echo $result;
exit();';
            
            
            // 本地数据
        } else {
            // 远程数据
            $this->_content1 = '<?php
namespace ' . strtoupper($modulename) . '\\D;
class ' . $this->_file1 . '
{
    private $_url;
    private $_mainkey;
    private $_host;
    private $_privatekey;
    private $_moduleid;
    
    private $rs;
    private $_lastid;
    private $_rows;

    public function __construct()
    {
        $this->_id="'.guid().'";
        $this->_url="'.$rpcurl.$this->_file1.'rpcserver.php";
        $this->_mainkey="'.$rpcmainkey.'";
        $this->_host="'.$rpchost.'";
        $this->_privatekey="'.$rpcprivatekey.'";
        $this->_moduleid='.$rpcmoduleid.';

        $conn = new \ZHMVC\B\RPC\jsonRPCClient($this->_url, $this->_mainkey, $this->_host, $this->_moduleid, $this->_privatekey, $this->_id);

        if($conn=="error")
        {
            echo "通讯错误";
            exit;
        }
        else
        {
            $this->rs=$conn;
        }
    }
            
    public function getRows()
    {
        return $this->_rows;
    }
          
    public function getLastId(){
        return $this->_lastid;
    }
    
    public function getAllNum($parameter="")
    {
        $data=$this->rs->getAllNum($parameter);
        if(empty($data)==true)
        {
            $data["num"]=0;
        }
        return $data;
    }
    
    public function getAll($parameter="")
    {
        $datas=$this->rs->getAll($parameter);
        $this->_rows=count($datas);
        return $datas;
    }
    
    public function getPages($limit,$parameter="")
    {
        if($parameter=="")
        {
            $datas=$this->rs->getPages($limit);
        }
        else
        {
            $datas=$this->rs->getPages($limit,$parameter);
        }
        $this->_rows=count($datas);
        return $datas;
    }
    
    public function getOne($postid)
    {
        $data=$this->rs->getOne($postid);
        $this->_rows=count($data);
        return $data;
    }
    
    public function add({@InsertB@})
    {
        $parameter=array();
        {@InsertBRPC@}
        $this->_lastid=$this->rs->add($parameter);
        return $this->_lastid;
    }

    public function update({@UpdateB@})
    {
        $parameter=array();
        {@UpdateCRPC@}
        $parameter["id"]=$id;
        $this->rs->update($parameter);
        return 1;
    }
    
    public function delete($id)
    {
        $this->rs->delete($id);
        return 1;
    }
}';
            $this->_content2 = '<?php
namespace ' . strtoupper($modulename) . '\\D;
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
    <a href="' . $this->_file2 . '.php" class="current">管理首页</a> </div>
  	<button class="btn" onClick="location=\'?atcion=main\';" >管理首页</button>
    <button class="btn btn-primary" onClick="location=\'?atcion=add\';" >新增</button>
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
            {@MainTh@}
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
<?php
    $conn=new \\'.strtoupper($modulename).'\\D\\' . $this->_file1 . '();
    $rs=$conn->getAllNum();
    $pages = $rs["num"];
    $rs=null;
	If($pages<=0)
	{
		$OutStr="<tr>";
		$OutStr=$OutStr."<td colspan=10>&nbsp;<font color=\"red\">暂无内容</font></td>";
		$OutStr=$OutStr."</tr></tbody>";
		echo $OutStr;
	}
	Else
	{
        $pa = new \\ZHMVC\\B\\TOOL\\ShowPages();
		$pa->pvar="pg";
		$pa->set(20,$pages);
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$datas=$conn->getPages($pa->limit());
		$rows=$conn->getRows();
		$rs=null;
		//print_r($datas);
	    for($i=0;$i<$rows;$i++)
	    {
	    	$data=$datas[$i];
  ?>
  <tr>
    <td><?php echo $data["id"]; ?></td>
    {@MainTd@}
    <td class="taskOptions"><a href="?atcion=add&postid=<?php echo $data["id"]; ?>">编辑</a> | <a href="?atcion=del&postid=<?php echo $data["id"]; ?>" onclick="{if(confirm(\'确定删除吗?\')){return true;}return false;}">删除</a></td>
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
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$data=$rs->getOne($postid);
		$rows=$rs->getRows();
		If($rows!=0)
		{
            $ilistid=$postid;
		    {@Add1@}
		}
		else
		{
            $ilistid="";
		    {@Add2@}
        }
	}
    else
    {
       $ilistid="";
	   {@Add3@}
    }
?>
<form name="PForm" id="PForm" method="post" action="?atcion=save&postid=<?php echo $postid; ?>" class="form-horizontal">
	<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>管理</h5>
        </div>
        <div class="widget-content">
          <!-- 表单 -->
            {@AddTd@}
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
            
    {@SaveP@}
          
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$data=$rs->update({@SaveB@});
        $ilistid=$postid;
	}
	Else
	{
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$ilistid=$rs->add({@SaveA@});
        //$ilistid=$rs->getLastId();
	}
            
	echo "<script>alert(\'更新成功\');window.location.href=\'' . $this->_file2 . '.php\';</script>";
}
            
function del()
{
	$postid=SafeRequest(getPGC("postid"),0);
	if(($postid!="") && ($postid!="0"))
	{
		$rs=new \\' . strtoupper($modulename) . '\\D\\' . $this->_file1 . '();
		$rs->delete($postid);
		echo "<script>alert(\'更新成功\');window.location.href=\'' . $this->_file2 . '.php\';</script>";
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
';
            // 远程数据
        }
    }

    public function parse()
    {
        $st = new \ZHMVC\D\ShowTables();
        $s = $st->getColumns($this->_pre . $this->_table);
        
        // 插入
        $InsertD = ""; // api插入变量的列表,如$name,$allname,$jxid,$jjjxid
        $UpdateD = ""; // api插入变量的列表,如$name,$allname,$jxid,$jjjxid
        $InsertB1 = ""; // 插入变量的列表,如$name,$allname,$jxid,$jjjxid
        $InsertB = ""; // 插入变量的列表,如$name,$allname,$jxid,$jjjxid
        $InsertP = ""; // 表的字段,如name,allname,jxid,jjjxid
        $InsertV = ""; // 表的字段绑定方法,如name=:name,allname=:allname,jxid=:jxid,jjjxid=:jjjxid
        $InsertA = ""; // 表的字段绑定值,如":name"=>$name,":allname"=>$allname,":jxid"=>$jxid,":jjjxid"=>$jjjxid,":id"=>$id
        $InsertARPC = ''; // 表的字段绑定值,如":name"=>$name,":allname"=>$allname,":jxid"=>$jxid,":jjjxid"=>$jjjxid,":id"=>$id
        $InsertPRPC = ""; // 表的字段,如name,allname,jxid,jjjxid
        $InsertVRPC = ""; // 表的字段绑定方法,如name=:name,allname=:allname,jxid=:jxid,jjjxid=:jjjxid
        $InsertBRPC = "";  //$parameter["name"]=$name
        // 修改
        $UpdateB1 = '$id,'; // 变量的列表,如$name,$allname,$jxid,$jjjxid
        $UpdateB = '$id,'; // 变量的列表,如$name,$allname,$jxid,$jjjxid
        $UpdateV = ""; // 表的字段绑定方法,如name=:name,allname=:allname,jxid=:jxid,jjjxid=:jjjxid
        $UpdateA = ""; // 表的字段绑定值,如":name"=>$name,":allname"=>$allname,":jxid"=>$jxid,":jjjxid"=>$jjjxid,":id"=>$id
        $UpdateARPC = ""; // 表的字段绑定值,如":name"=>$name,":allname"=>$allname,":jxid"=>$jxid,":jjjxid"=>$jjjxid,":id"=>$id  
        $UpdateVRPC = ""; // 表的字段绑定方法,如name=:name,allname=:allname,jxid=:jxid,jjjxid=:jjjxid  
        $UpdateCRPC="";
        // 管理后台
                     // main函数
        $MainTh = '';
        $MainTd = '';
        // add函数
        $Add1 = '';
        $Add2 = '';
        $Add3 = '';
        $AddTd = '';
        // save
        $SaveP = '';
        $SaveB = '$postid,';
        $SaveA = '';
        for ($i = 0, $imax = count($s); $i < $imax; $i ++) {
            $UpdateD .= '$map[' . $i . '],';
            if ($s[$i]['field'] != "id") {
                
                if($s[$i]['field'] == "mainkey")
                {
                    $UpdateB1 .= '$' . $s[$i]['field'] . ',';
                    $UpdateB .= '';
                    $UpdateV .= '`' . $s[$i]['field'] . '`=:' . $s[$i]['field'] . ',';
                    $InsertP .= '`' . $s[$i]['field'] . '`,';
                    $InsertV .= ':' . $s[$i]['field'] . ',';
                    $InsertPRPC .= '`' . $s[$i]['field'] . '`,';
                    $InsertVRPC .= ':' . $s[$i]['field'] . ',';
                    $InsertA .= '":' . $s[$i]['field'] . '"=>$' . $s[$i]['field'] . ',';
                    $InsertARPC .= '":' . $s[$i]['field'] . '"=>$mainkey,';
                    $InsertB1.= '$' . $s[$i]['field'] . ',';
                    $InsertB .= '';
                    $InsertBRPC .= ''."\n";
                    $UpdateA .= '":' . $s[$i]['field'] . '"=>$' . $s[$i]['field'] . ',';
                    $UpdateARPC .= '';
                    $UpdateVRPC  .= ''."\n";
                    $UpdateCRPC  .= ''."\n";
                }
                else
                {
                    $UpdateB1 .= '$' . $s[$i]['field'] . ',';
                    $UpdateB .= '$' . $s[$i]['field'] . ',';
                    $UpdateV .= '`' . $s[$i]['field'] . '`=:' . $s[$i]['field'] . ',';
                    $InsertP .= '`' . $s[$i]['field'] . '`,';
                    $InsertV .= ':' . $s[$i]['field'] . ',';
                    $InsertPRPC .= '`' . $s[$i]['field'] . '`,';
                    $InsertVRPC .= ':' . $s[$i]['field'] . ',';
                    $InsertA .= '":' . $s[$i]['field'] . '"=>$' . $s[$i]['field'] . ',';
                    $InsertARPC .= '":' . $s[$i]['field'] . '"=>$parameter["' . $s[$i]['field'] . '"],';
                    $InsertB1 .= '$' . $s[$i]['field'] . ',';
                    $InsertB .= '$' . $s[$i]['field'] . ',';
                    $InsertBRPC .= '$parameter["' . $s[$i]['field'] . '"]=$' . $s[$i]['field'] . ';'."\n";
                    $UpdateA .= '":' . $s[$i]['field'] . '"=>$' . $s[$i]['field'] . ',';
                    $UpdateARPC .= '":' . $s[$i]['field'] . '"=>$parameter["' . $s[$i]['field'] . '"],';
                    $UpdateVRPC  .= '`' . $s[$i]['field'] . '`=:' . $s[$i]['field'] . ',';
                    $UpdateCRPC  .= '$parameter["' . $s[$i]['field'] . '"]=$' . $s[$i]['field'] . ';'."\n";
                    
                }
                if ($this->_rpctype == "本地") {

                        $MainTh .= '<th>' . $s[$i]['field'] . '</th>
                        ';
                        $MainTd .= '<td><?php echo $data[\'' . $s[$i]['field'] . '\']; ?></td>
                        ';
                }
                else
                {
                    if($s[$i]['field'] == "mainkey")
                    {
                                            
                    }
                    else
                    {
                        $MainTh .= '<th>' . $s[$i]['field'] . '</th>
                        ';
                        $MainTd .= '<td><?php echo $data[\'' . $s[$i]['field'] . '\']; ?></td>
                        ';
                    }
                }
                
                
                // ********add函数特别标出来*******
                $Add1 .= '
                    $' . $s[$i]['field'] . '=$data[\'' . $s[$i]['field'] . '\'];
                    ';
                $Add2 .= '
                    $' . $s[$i]['field'] . '=\'\';
                    ';
                $Add3 .= '
                    $' . $s[$i]['field'] . '=\'\';
                    ';
                // 处理各种类型的字段
                // 1判断是否有括号
                if (substr_count($s[$i]['type'], "(") == 0) {
                    $tempE = $s[$i]['type'];
                    // 没有
                    if ($tempE == "text") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                            ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {

                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                                ';
                            }
                        }
                    } elseif ($tempE == "longtext") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                            ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {

                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                                ';
                            }
                        }
                    } elseif ($tempE == "mediumtext") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                            ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {

                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                                ';
                            }
                        }
                    } 
                    elseif ($tempE == "decimal") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                            ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {
                                
                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                                ';
                            }
                        }
                    }
                    elseif ($tempE == "tinytext") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                            ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {

                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <textarea name="' . $s[$i]['field'] . '" class="textarea_editor span12" rows="6" placeholder="">
                                    <?php echo $' . $s[$i]['field'] . '; ?>
                                    </textarea>
                                  </div>
                        	</div>
                                ';
                            }
                        }
                    } elseif ($tempE == "double") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempE == "float") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempE == "datetime") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '"
                                     data-date="<?php echo $' . $s[$i]['field'] . '; ?>" 
                                     value="<?php echo $' . $s[$i]['field'] . '; ?>"  
                                     data-date-format="yyyy-mm-dd" readonly class="Wdate span11" onFocus="WdatePicker({isShowClear:false,readOnly:true})" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempE == "date") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '"
                                     data-date="<?php echo $' . $s[$i]['field'] . '; ?>" 
                                     value="<?php echo $' . $s[$i]['field'] . '; ?>"  
                                     data-date-format="yyyy-mm-dd" readonly class="Wdate span11" onFocus="WdatePicker({isShowClear:false,readOnly:true})" />
                                  </div>
                        	</div>
                             ';
                    }
                } else {
                    // 去掉(,获取类型
                    $tempA = explode("(", $s[$i]['type']);
                    $tempB = $tempA[0];
                    $tempC = explode(")", $tempA[1]);
                    $tempD = explode(",", $tempC[0]);
                    if ($tempB == "varchar") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {

                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                            }
                        }
                        
                    } elseif ($tempB == "char") {
                        if ($this->_rpctype == "本地") {
                            $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                        }
                        else
                        {
                            if($s[$i]['field'] == "mainkey")
                            {

                            }
                            else
                            {
                                $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                            }
                        }
                        
                    } elseif ($tempB == "decimal") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempB == "tinyint") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempB == "int") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempB == "bigint") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                    <input type="text" name="' . $s[$i]['field'] . '" class="span" placeholder="" 
                                    value="<?php echo $' . $s[$i]['field'] . '; ?>" />
                                  </div>
                        	</div>
                             ';
                    } elseif ($tempB == "enum") {
                        $AddTd .= '
                            <div class="control-group">
                            	<label class="control-label">' . $s[$i]['field'] . ':</label>
                                  <div class="controls">
                                 ';
                        for ($j = 0, $jmax = count($tempD); $j < $jmax; $j ++) {
                            $atemp = $tempD[$j];
                            $atemp = str_replace("'", "", $atemp);
                            $AddTd .= '
                                <label>
                            <input type="radio" name="' . $s[$i]['field'] . '" value="' . $atemp . '" <?php
                            if ($' . $s[$i]['field'] . ' == "' . $atemp . '") { echo "checked";
                            }
                            ?>>' . $atemp . '
                            </label>
                            ';
                        }
                        $AddTd .= '
                            </div>
        	               </div>
                          ';
                    }
                }
                // ********add函数特别标出来*******
                
                // save
                if ($this->_rpctype == "本地") {
                        $SaveP .= '
                    $' . $s[$i]['field'] . '=SafeRequest(getPGC("' . $s[$i]['field'] . '"),0);
                   ';
                    $SaveB .= '$' . $s[$i]['field'] . ',';
                    $SaveA .= '$' . $s[$i]['field'] . ',';  
                }
                else
                {
                    if($s[$i]['field'] == "mainkey")
                    {

                    }
                    else
                    {
                        $SaveP .= '
                    $' . $s[$i]['field'] . '=SafeRequest(getPGC("' . $s[$i]['field'] . '"),0);
                   ';
                        $SaveB .= '$' . $s[$i]['field'] . ',';
                        $SaveA .= '$' . $s[$i]['field'] . ',';  
                    }
                }
                
                
                
            }
        }
        for ($i = 0, $imax = count($s); $i < ($imax-1); $i ++) {
            $InsertD .= '$map[' . $i . '],';
        }
        
        $InsertD = substr($InsertD, 0, strlen($InsertD) - 1);
        $UpdateD = substr($UpdateD, 0, strlen($UpdateD) - 1);
        
        
        $InsertB1 = substr($InsertB1, 0, strlen($InsertB1) - 1);
        $InsertB = substr($InsertB, 0, strlen($InsertB) - 1);
        $InsertP = substr($InsertP, 0, strlen($InsertP) - 1);
        $InsertV = substr($InsertV, 0, strlen($InsertV) - 1);
        $InsertA = substr($InsertA, 0, strlen($InsertA) - 1);
        $InsertARPC = substr($InsertARPC, 0, strlen($InsertARPC) - 1);
        $InsertPRPC = substr($InsertPRPC, 0, strlen($InsertPRPC) - 1);
        $InsertVRPC = substr($InsertVRPC, 0, strlen($InsertVRPC) - 1);
        
        $UpdateB1 = substr($UpdateB1, 0, strlen($UpdateB1) - 1);
        $UpdateB = substr($UpdateB, 0, strlen($UpdateB) - 1);
        $UpdateV = substr($UpdateV, 0, strlen($UpdateV) - 1);
        $UpdateVRPC = substr($UpdateVRPC, 0, strlen($UpdateVRPC) - 1);
        $UpdateA .= '":id"=>$id';
        $UpdateARPC .= '":id"=>$parameter["id"]';
        
        $SaveB = substr($SaveB, 0, strlen($SaveB) - 1);
        $SaveA = substr($SaveA, 0, strlen($SaveA) - 1);
        $this->_content1 = str_replace('{@InsertB@}', $InsertB, $this->_content1);
        $this->_content1 = str_replace('{@InsertB1@}', $InsertB1, $this->_content1);
        $this->_content1 = str_replace('{@InsertBRPC@}', $InsertBRPC, $this->_content1);
        $this->_content1 = str_replace('{@UpdateCRPC@}', $InsertBRPC, $this->_content1);
        $this->_content1 = str_replace('{@InsertP@}', $InsertP, $this->_content1);
        $this->_content1 = str_replace('{@InsertV@}', $InsertV, $this->_content1);
        $this->_content1 = str_replace('{@InsertA@}', $InsertA, $this->_content1);
        $this->_content1 = str_replace('{@UpdateB@}', $UpdateB, $this->_content1);
        $this->_content1 = str_replace('{@UpdateB1@}', $UpdateB1, $this->_content1);
        $this->_content1 = str_replace('{@UpdateA@}', $UpdateA, $this->_content1);
        $this->_content1 = str_replace('{@UpdateV@}', $UpdateV, $this->_content1);
        

        $this->_content3 = str_replace('{@InsertPRPC@}', $InsertPRPC, $this->_content3);
        $this->_content3 = str_replace('{@InsertVRPC@}', $InsertVRPC, $this->_content3);
        $this->_content3 = str_replace('{@InsertARPC@}', $InsertARPC, $this->_content3);
        $this->_content3 = str_replace('{@UpdateARPC@}', $UpdateARPC, $this->_content3);
        $this->_content3 = str_replace('{@UpdateVRPC@}', $UpdateVRPC, $this->_content3);
        // print_r($this->content1);
        
        // 后台$this->content2
        /*
         * $MainTh='';
         * $MainTd='';
         * //add函数
         * $Add1='';
         * $AddTd='';
         * //save
         * $SaveP='';
         * $SaveB='$postid,';
         * $saveA='';
         */
        $this->_content2 = str_replace('{@MainTh@}', $MainTh, $this->_content2);
        $this->_content2 = str_replace('{@MainTd@}', $MainTd, $this->_content2);
        $this->_content2 = str_replace('{@Add1@}', $Add1, $this->_content2);
        $this->_content2 = str_replace('{@Add2@}', $Add2, $this->_content2);
        $this->_content2 = str_replace('{@Add3@}', $Add3, $this->_content2);
        $this->_content2 = str_replace('{@AddTd@}', $AddTd, $this->_content2);
        $this->_content2 = str_replace('{@SaveP@}', $SaveP, $this->_content2);
        $this->_content2 = str_replace('{@SaveB@}', $SaveB, $this->_content2);
        $this->_content2 = str_replace('{@SaveA@}', $SaveA, $this->_content2);
        
        
        $this->_content4 = str_replace('{@InsertD@}', $InsertD, $this->_content4);
        $this->_content4 = str_replace('{@UpdateD@}', $UpdateD, $this->_content4);
    }

    public function saveContent()
    {
        $filepath1 = ZH_PATH . DS . $this->_path1 . $this->_file1 . ZH;
        
        if (file_exists($filepath1)) {} else {
            $fp = fopen($filepath1, 'w');
            fwrite($fp, $this->_content1);
            fclose($fp);
        }
        
        $filepath3 = ZH_PATH . DS . $this->_path1 . $this->_file1.'rpc' . ZH;
        
        if (file_exists($filepath3)) {} else {
            $fp = fopen($filepath3, 'w');
            fwrite($fp, $this->_content3);
            fclose($fp);
        }
        
        $filepath2 = ZH_PATH . DS . $this->_path2 . $this->_file2 . ZH;
        if (file_exists($filepath2)) {} else {
            $fp = fopen($filepath2, 'w');
            fwrite($fp, $this->_content2);
            fclose($fp);
        }

        $filepath4 = ZH_PATH . DS . str_replace('admin', 'api', $this->_path2) . $this->_file1 . ZH;
        if (file_exists($filepath4)) {} else {
            $fp = fopen($filepath4, 'w');
            fwrite($fp, $this->_content4);
            fclose($fp);
        }
    }
}