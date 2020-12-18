<?php
namespace ZHMVC\B\TOOL;

class FileToApi
{
    private $_table;
    private $_file1;
    private $_path1;
    private $_pre;
    private $_content1;//数据管理类

    
    public function __construct($pre,$tablename,$pathname1,$filename1,$modulename)
    {
        $this->_table=$pre.$tablename;
        $this->_file1=$filename1;
        $this->_path1=$pathname1;
       
        $this->content1='<?php
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
$datajson=json_decode($data1, true);
$checktoken=checksignature($timestamp,$randomstr);
$returnS = array(
    "code" => "0",
    "data" => array(),
    "msg" => "success",
    "message" => "成功请求",
    "updateDime" => intval(time())
);
if($token==$checktoken)
{
	$returnS["msg"]="ok";
	if(count($datajson)==1)
	{
		//单一参数
		$fname=$datajson[0]["fname"];  //方法名称
        $id=$datajson[0]["id"];  //传递的参数
        $page=$datajson[0]["page"];  //分页
        $curpage=$datajson[0]["curpage"];  //分页时当前页码
        $order=$datajson[0]["order"];  //排序
        $where=$datajson[0]["where"];  //更新时where的条件
        $limit=$datajson[0]["limit"];  //limit
        $sql=$datajson[0]["sql"];  //直接使用sql
        $b=$datajson[0]["b"];  //备用字段
        $map=array();
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
		        else 
		        {
		            //存在,id为字符串
		            $map=explode("{-}", $id);
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
		        }
		    }
		}
		
        if($fname=="getOne")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getOne($map);
	        $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getAll")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getAll($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getAllNum")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getAllNum($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getPages")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getPages($limit,$map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getOne1")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getOne1($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getSqlAll")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getSqlAll($sql);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getSqlOne")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->getSqlOne($sql);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="add")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $ss="";
            for($j=0;$j<count($map);$j++)
            {
                $ss=$map[$j].",";
            }
            $ss=substr($ss,0,strlen($ss)-1); 
            $datas=$rs1->add($ss);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="update")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $updateid=$map[(count($map)-1)];
            $ss="";
            for($j=0;$j<(count($map)-1);$j++)
            {
                $ss=map[$j].",";
            }
            $ss=substr($ss,0,strlen($ss)-1);
            $datas=$rs1->update($updateid,$ss);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="delete")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
            $datas=$rs1->delete($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="SqlUpdate")
        {
            $rs1=new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'();
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
exit();
';
    }
    
    public function saveContent()
    {
        
        $filepath1=ZH_PATH.DS.$this->_path1.$this->_file1. ZH;
        
        if (file_exists($filepath1)) {
        
        } else {
            $fp = fopen($filepath1,'w');
            fwrite($fp,$this->content1);
            fclose($fp);
        }
       
    }
    
}