<?php
namespace ZHMVC\B\T;
class ZhPlus
{

    private $_content = "";

    private $_zhParameter = ""; // 表达式

    public function setZhParameter($zhParameter)
    {
        $this->_zhParameter = $zhParameter;
    }

    public function __construct()
    {
        $this->_content = "";
    }

    public function Parse()
    {
        // 判断是否有参数,分隔符是=
        if (substr_count($this->_zhParameter, "=") == 0) {
            // 没有指定参数，参数为空
            $plusNameParameter = $this->_zhParameter;
            $plusParameter = "";
        } 
        else 
        {
            $zhSplitA = explode("=", $this->_zhParameter);
            $plusNameParameter = $zhSplitA[0];
            $plusParameter = $zhSplitA[1];
        }
        
        // 判断插件类型
        //$GySplitB = explode("-", $plusnameParameter);
        // 获取插件根目录
        //$plusRootPath = $GySplitB[0];
        //$plusnameParameter = $GySplitB[1];
        
        // 获取插件实际文件夹
        $zhSplitC = explode(".", $plusNameParameter);
        
        $plusName = $zhSplitC[0];
        $plusClass = $zhSplitC[1];
        
        $c = new \ZHCONFIG\ZhConfig();
        $pre = $c->getDbPre();
        //根据插件名称获取相应的信息
        $pn=new \ZHMVC\D\PlusCommon($pre);
        $plusInfo=$pn->getPlusName($plusName);
        $plusRootPath=$plusInfo['plusrootpath'];
        $pluspath=$plusInfo['pluspath'];
        // 判断一下插件是否安装，如果未安装，则显示为空
        if (is_file(SYSTEM_PATH.$plusRootPath.DIRECTORY_SEPARATOR."flock.php") == true) {
            
            if (! class_exists($plusClass)) {
                include (SYSTEM_PATH.$pluspath);
            }
            $p = new $plusClass();
            $this->_content= $p->getHtml($plusNameParameter);
        } else {
            $this->_content= "";
        }
    }

    public function getContent()
    {
        return $this->_content;
    }
}