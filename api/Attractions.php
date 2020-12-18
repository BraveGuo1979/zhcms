<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
// 制定允许其他域名访问
header("Access-Control-Allow-Origin:*");
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with, content-type');
ini_set('date.timezone', 'Asia/Shanghai');
session_start();
include(dirname(dirname(__FILE__))."/zhconfig/Config.php");
$returnS = array(
    'status' => '0',
    'data' => array(),
    'msg' => 'success',
    'message' => '成功请求',
    'updateDime' => intval(time())
);

$pg = SafeRequest(getPGC("pg"), 0);


$rs1 = new \LVYOU\D\Attractions();
$datas = $rs1->getAllNum();
$rows = $datas["num"];
if($rows>0)
{
    $pa = new \ZHMVC\B\TOOL\ShowPages();
    $pa->pvar = "pg";
    $pa->set(10, $rows);
    $rs = new \LVYOU\D\Attractions();
    $datas = $rs->getPages($pa->limit());
    $rows = $rs->getRows();
    $rs = null;
    
    for($i=0;$i<$rows;$i++)
    {
        $datas[$i]['addtime']=format_date(strtotime($datas[$i]['addtime']));
    }
    
    $returnS['data'] = $datas;
    $returnS['code'] = "0";
    $returnS['allPages'] = $pa->getPages();
    $returnS['msg'] = "ok";
    $returnS['message'] = "成功";
}
else
{
    $returnS['code'] = "1";
    $returnS['msg'] = "error";
    $returnS['allPages'] = "0";
    $returnS['message'] = "超时";
}

$result = json_encode($returnS);
echo $result;
exit();