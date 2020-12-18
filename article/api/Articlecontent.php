<?php
namespace ARTICLE\D;
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
            $rs1=new \ARTICLE\D\Articlecontent();
	        $datas=$rs1->getOne($map);
	        $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getAll")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->getAll($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getAllNum")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->getAllNum($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getPages")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->getAllNum($page,$map="");
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getOne1")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->getOne1($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getSqlAll")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->getSqlAll($sql);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="getSqlOne")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->getSqlOne($sql);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="add")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->add($map[0],$map[1],$map[2],$map[3],$map[4],$map[5],$map[6],$map[7],$map[8],$map[9],$map[10],$map[11],$map[12],$map[13],$map[14],$map[15],$map[16],$map[17],$map[18],$map[19]);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="update")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->update($map[0],$map[1],$map[2],$map[3],$map[4],$map[5],$map[6],$map[7],$map[8],$map[9],$map[10],$map[11],$map[12],$map[13],$map[14],$map[15],$map[16],$map[17],$map[18],$map[19],$map[20]);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="delete")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
            $datas=$rs1->delete($map);
            $returnS["data"]["data"] = $datas;
        }
        elseif($fname=="SqlUpdate")
        {
            $rs1=new \ARTICLE\D\Articlecontent();
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