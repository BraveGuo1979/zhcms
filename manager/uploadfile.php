<?php
namespace ZHMVC\TOOL;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(__FILE__)) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
$action=SafeRequest(getPGC('atcion'),0);
switch ($action)
{
    case "save":
        save();
        break;
    default:
        main();
}

function main()
{
    $up=new \ZHMVC\B\TOOL\ClsUpload();
    echo $up->getHtml("userfile");
}
function save()
{
    $up=new \ZHMVC\B\TOOL\ClsUpload();
    echo $up->saveFile($_FILES, ZH_PATH. DS ."upload", "form1", "SiteLogo","/upload");
}
