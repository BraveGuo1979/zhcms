<?php
namespace ZHMVC\B\TOOL;
include(dirname(dirname(dirname(__FILE__)))."/zhconfig/Config.php");
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
    echo $up->saveFile($_FILES, ZH_PATH."/upload", "PForm", "logo","/upload");
}
