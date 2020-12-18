<?php
define('ZHMVC_VERSION', '2.0.0 Alpha');
define('ZH', '.php');
define('LOG', '.log');
define('DS', DIRECTORY_SEPARATOR);
define('ZH_PATH',dirname(dirname(__FILE__)));//定义系统目录
define('ISZHMVCBUG',true);//定义是否调试
define('SIGNATURENEW','hbcheng');

header('Access-Control-Allow-Origin:*'); 

if(ISZHMVCBUG==true)
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
else
{
    error_reporting(0);//E_ALL 0
    ini_set('display_errors', '0');//1 0
}
include_once(ZH_PATH.DS."common".DS."tool".DS."Fun".ZH);
include_once(ZH_PATH.DS."common".DS."ZhLoader".ZH);
//include_once(ZH_PATH.DS."common".DS."ModLoader".ZH);

$smarty = new \Smarty();
$smarty->setTemplateDir(ZH_PATH.DS."s".DS."t".DS);
$smarty->setCompileDir(ZH_PATH.DS."smartycompile".DS);
$smarty->setCacheDir(ZH_PATH.DS."cache".DS);
$smarty->caching = false;
$smarty->cache_lifetime = 300;
$smarty->left_delimiter  = '{@#';
$smarty->right_delimiter = '#@}';
$CssRand=getRandChar(6);
$smarty->assign("CssRand", $CssRand);
$smarty->registerPlugin("function", "RepModule", "RepModule");
$smarty->registerPlugin("function", "RepPlus", "RepPlus");
$smarty->assign('PageCss','<LINK href="/common/css/pagecss.css" type="text/css" rel="stylesheet">');
$smarty->assign("TPATH", "/s/t/");
//获取系统变量
$sysconn=new \ZHMVC\D\SystemConfig();
$srs=$sysconn->getAll();
//print_r($srs);
$srsnum=$sysconn->getRows();
for($i=0;$i<$srsnum;$i++)
{
    $smarty->assign($srs[$i]['name'], $srs[$i]['value']);
}