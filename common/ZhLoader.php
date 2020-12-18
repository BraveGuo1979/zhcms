<?php

class ZhLoader
{

    public static $vendorMap;

    /**
     * 注册自动加载机制
     * 
     * @access public
     * @param callable $autoload
     *            自动加载处理方法
     * @return void
     */
    public static function register($autoload = null)
    {
        // echo "执行1.<br>\n";
        // 加载类库映射文件
        /*
         *
         * 参数说明：
         * value：路径
         * type：类型，system是系统，mod是模块
         * loadtype：连接类型 class是类，file是文件
         */
        self::$vendorMap = array(
            'zhmvc' => array(
                'value' => ZH_PATH . DS . 'zhmvc',
                'type' => 'system',
                'loadtype' => 'class'
            ),
            'zhconfig' => array(
                'value' => ZH_PATH . DS . 'zhconfig',
                'type' => 'system',
                'loadtype' => 'class'
            )
        );
        
        $vendorMapNew = array();
        // 读取配置文件
        if (is_file($file = ZH_PATH . DS . "zhconfig" . DS . "ClassMap" . ZH) == true) {
            
            include $file;
            $mapnum = count($vendorMapNew);
            for ($i = 0; $i < $mapnum; $i ++) {
                
                if ($vendorMapNew[$i]['loadtype'] == 'module') {
                    $vendorModuleMapNew = array();
                    $modulMapPath = $vendorMapNew[$i]['value'];
                    
                    // 需要分别加载模块
                    if (is_file($modulMapPath)) {
                        include_once $modulMapPath;
                    }
                    $moduleMapNum = count($vendorModuleMapNew);
                    for ($j = 0; $j < $moduleMapNum; $j ++) {
                        self::$vendorMap[$vendorModuleMapNew[$j]['name']] = array(
                            'value' => $vendorModuleMapNew[$j]['value'],
                            'type' => $vendorModuleMapNew[$j]['type'],
                            'loadtype' => $vendorModuleMapNew[$j]['loadtype']
                        );
                    }
                } else {
                    self::$vendorMap[$vendorMapNew[$i]['name']] = array(
                        'value' => $vendorMapNew[$i]['value'],
                        'type' => $vendorMapNew[$i]['type'],
                        'loadtype' => $vendorMapNew[$i]['loadtype']
                    );
                }
            }
        }
        // print_r(self::$vendorMap);
        // 注册系统自动加载
        spl_autoload_register($autoload ?: 'ZhLoader::autoload', true, true);
    }

    /**
     * 自动加载器
     */
    public static function autoload($class)
    {
        // echo "执行2.<br>\n";
        $file = self::findFile($class);
        // var_dump(file_exists($file));
        // var_dump($file);
        if (file_exists($file)) {
            self::includeFile($file);
        }
    }

    /**
     * 解析文件路径
     */
    private static function findFile($class)
    {
        //echo "执行3.<br>\n";
        //echo "class---" . $class . "<br>\n";
        $classA = explode('\\', $class);
        
        $vendor = strtolower($classA[0]);
        // print_r($classA);
        // echo '=================================================';
        // echo $vendor."<br>";
        $filePath = '';
        if (array_key_exists($vendor, self::$vendorMap) == true) {
            // echo $vendor."存在<br>";
            $className = $classA[(count($classA) - 1)];
            $vendorArray = self::$vendorMap[$vendor]; // 文件基目录
                                                      // print_r($vendorArray);
            if ($vendorArray['type'] == 'system') {
                $filePath .= $vendorArray['value'] . DIRECTORY_SEPARATOR;
                for ($i = 1; $i < (count($classA) - 1); $i ++) {
                    $filePath .= strtolower($classA[$i]) . DIRECTORY_SEPARATOR;
                }
                $filePath .= $className . ZH; // 文件相对路径
                                               // echo "-----------";
                                               // echo $filePath;
            } elseif ($vendorArray['type'] == 'mod') {
                $filePath = $vendorArray['value'];
            }
        } else {
            // echo $vendor."不存在<br>";
            $vendor = '';
            for ($i = 0; $i < count($classA); $i ++) {
                $vendor .= $classA[$i] . "_";
            }
            $vendor = substr($vendor, 0, strlen($vendor) - 1);
            if (array_key_exists($vendor, self::$vendorMap) == true) {
                // echo $vendor."存在<br>";
                $className = $classA[(count($classA) - 1)];
                $vendorArray = self::$vendorMap[$vendor]; // 文件基目录
                                                          // print_r($vendorArray);
                if ($vendorArray['type'] == 'system') {
                    $filePath .= $vendorArray['value'] . DIRECTORY_SEPARATOR;
                    for ($i = 1; $i < (count($classA) - 1); $i ++) {
                        $filePath .= strtolower($classA[$i]) . DIRECTORY_SEPARATOR;
                    }
                    $filePath .= $className . ZH; // 文件相对路径
                                                   // echo "-----------";
                                                   // echo $filePath;
                } elseif ($vendorArray['type'] == 'mod') {
                    $filePath = $vendorArray['value'];
                }
            } else {
                //print_r($classA);
                $filePath = ZH_PATH . DIRECTORY_SEPARATOR;
                // 判断路径的深度
                $filePath1 = DIRECTORY_SEPARATOR . "";
                for ($i = 0; $i < (count($classA) - 1); $i ++) {
                    $filePath1 .= strtolower($classA[$i]) . DIRECTORY_SEPARATOR;
                }
                
                $filePath1 .= $classA[(count($classA) - 1)] . ZH; // 文件相对路径
                
                if (is_file($filePath . $filePath1) == true) {
                    $filePath = $filePath . $filePath1;
                } elseif (is_file($filePath . DIRECTORY_SEPARATOR . "mod" . $filePath1) == true) {
                    $filePath = $filePath . DIRECTORY_SEPARATOR . "mod" . $filePath1;
                }
                
                //echo $filePath1 . "<br>";
               //print_r($classA);
            }
        }
        
        return $filePath; // 文件标准路径
    }

    /**
     * 引入文件
     */
    private static function includeFile($file)
    {
        // echo "执行4.<br>\n";
        if (is_file($file)) {
            include $file;
        }
    }

/**
 * 注册 classmap
 * 
 * @access public
 * @param string|array $class
 *            类名
 * @param string $map
 *            映射
 * @return void
 */
}
\ZhLoader::register();
//spl_autoload_register('ZhLoader::autoload'); // 注册自动加载
