<?php

/** 
 * @author Administrator
 * 处理第三方类包含文件
 */
class ModLoader
{

    public static $vendorMap;
    /**
     */
    public function __construct()
    {
        
    }
    
    public static function load()
    {
        $vendorMapNew=array();
        $vendorModuleMapNew=array();
        //读取配置文件
        if (is_file($file = ZH_PATH.DS."zhconfig".DS."ClassMap".ZH)==true) {
            
            include $file;
            $mapnum=count($vendorMapNew);
            //var_dump($vendorMapNew);
            for($i=0;$i<$mapnum;$i++)
            {
                //var_dump($vendorMapNew[$i]['loadtype']);
                if($vendorMapNew[$i]['loadtype']=="file")
                {
                    //echo $vendorMapNew[$i]['value'];
                    self::includeFile($vendorMapNew[$i]['value']);
                }
                elseif($vendorMapNew[$i]['loadtype']=="system")
                {
                    //读取模块内置的map
                    //echo $vendorMapNew[$i]['value'];
                    //$vendorModuleMapNew=array();
                    self::includeFile($vendorMapNew[$i]['value']);
                    $moduleMapNum=count($vendorModuleMapNew);
                    var_dump($vendorModuleMapNew);
                    for($j=0;$j<$moduleMapNum;$j++)
                    {
                       // var_dump($vendorMapNew[$i]['loadtype']);
                        self::includeFile($vendorModuleMapNew[$j]['value']);
                    }
                }
            }
        }
    }
    
    /**
     * 引入文件
     */
    private static function includeFile($file)
    {
        //var_dump(is_file($file));
        if (is_file($file)) {
            include_once $file;
            //echo $file;
        }
    }

    /**
     */
    function __destruct()
    {
        
    }
}

\ModLoader::load();