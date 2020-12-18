<?php
define('ZHMVC_VERSION', '2.0.0 Alpha');
define('ZH', '.php');
define('LOG', '.log');
define('DS', DIRECTORY_SEPARATOR);
define('ZH_PATH',dirname(dirname(__FILE__)));//定义系统目录
define('ISDUB', true);
if(ISDUB===true)
{
    error_reporting(E_ALL);//E_ALL 0
    ini_set('display_errors', '1');//1 0
}
else
{
    error_reporting(0);//E_ALL 0
    ini_set('display_errors', '0');//1 0
}
include(ZH_PATH.DS."common".DS."Fun".ZH);
include(ZH_PATH.DS."common".DS."ZhLoader".ZH);
$CssRand=getRandChar(6);

