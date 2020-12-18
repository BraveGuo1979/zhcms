<?php
namespace ZHMVC\B\TOOL;
class Module_Interface
{

    public function __construct()
    {
        
    }

    public function getInterface($interfacepath,$modulepath)
    {
        $c = new \ZHCONFIG\ZhConfig();
        $pre=$c->getDbPre();
        if (is_dir($interfacepath)) {
            $file_list = scandir($interfacepath);
            foreach ($file_list as $file) {
                if ($file != '.' && $file != '..') {
                    $tempF = str_replace(".php", "", $file);
                    // echo "bbbb".$tempF."<br><br><br><br>";
                    if (is_dir($modulepath)) {
                        $modulefile_list = scandir($modulepath);
                        foreach ($modulefile_list as $modulefile) {
                            if ($modulefile != '.' && $modulefile != '..') {
                                // echo "aaaaa".$modulefile."<br><br><br><br>";
                                // var_dump(substr_count($modulefile,$tempF));
                                If (substr_count($modulefile, $tempF) != 0) {
                                    // 插入数据库
                                    //echo "dddd" . $modulefile . "<br>";
                                    $imodulename=str_replace(".php", "", $modulefile);
                                    $imoduletype=$tempF;
                                    $imodulepath=$modulepath.DIRECTORY_SEPARATOR.$modulefile;
                                    $imodulepath=str_replace(ZH_PATH, "", $imodulepath);
                                    $pc=new \ZHMVC\D\ModuleCommon($pre);
                                    $pc->add($imodulename, $imoduletype, $imodulepath);
                                } 
                            }
                        }
                    }
                }
            }
        }
        return 1;
    }
}
