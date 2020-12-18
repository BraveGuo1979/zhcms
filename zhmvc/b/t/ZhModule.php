<?php
namespace ZHMVC\B\T;
class ZhModule{
	private $_content="";
	private $_gyParameter="";//表达式

	public function setZhParameter($gyParameter)
	{
		$this->_gyParameter=$gyParameter;
	}
	
	public function __construct(){
		$this->_content="";
		
	}
	
	public function Parse()
	{
	    //echo $this->_gyParameter;
		//判断是否有参数,分隔符是=
		if(substr_count($this->_gyParameter,"=")==0)
		{
			//没有指定参数，参数为空
			$moduleNameParameter=$this->_gyParameter;
			$moduleParameter="";
		}
		else
		{
			$GySplitA=explode("=", $this->_gyParameter);
			$moduleNameParameter=$GySplitA[0];
			$moduleParameter=$GySplitA[1];
		}

		
		
		//判断插件类型
		if(substr_count($moduleNameParameter,".")==0)
		{
		    //类似module:插件=是公共插件插件目录在module中
		    $moduleClass=$moduleNameParameter;
		    //echo SYSTEM_PATH.DIRECTORY_SEPARATOR."module".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR.$moduleClass.".php";
		    include(SYSTEM_PATH.DIRECTORY_SEPARATOR."module".DIRECTORY_SEPARATOR."common".DIRECTORY_SEPARATOR.$moduleClass.".php");
		    $p=new $moduleClass();
		    $this->_content=$p->getHtml($moduleParameter);
		}
		else
		{
		    //类似module:插件.类=是页面嵌入插件，插件目录在每个插件的s文件夹中
		    //$modulenameT[0]是在数据库中的modulename
		    
		    $modulenameT=explode(".", $moduleNameParameter);
		    $DBModuleName=$modulenameT[0];
		    $moduleClass=$modulenameT[1];
		    
		    //获取数据连接的相关信息
		    $dbcon=new \ZHCONFIG\ZhConfig();
		    $pre=$dbcon->getDbPre();
		    //根据dbmodulename获取到插件目录
		    $moduleinfo=new \ZHMVC\D\ModuleInfo($pre);
		    $moduleNameTe=$moduleinfo->getModuleName($DBModuleName);
		    
		    $moduleName=$moduleNameTe['mulu'];
		    //获取插件的命名空间
		    $modulenamespace=$moduleNameTe['modulenamespace'];
		    //引入插件进行执行
		    $cname="\\ZHMVC\\DB\\".$modulenamespace."\\".$moduleClass;
		    if(!class_exists($cname)){
		        include(SYSTEM_PATH.DIRECTORY_SEPARATOR.$moduleName.DIRECTORY_SEPARATOR."s".DIRECTORY_SEPARATOR."plus".DIRECTORY_SEPARATOR.$moduleClass.".php");
		    }
		    $p=new $cname();
		    $this->_content=$p->getHtml($moduleParameter);
		    $p=null;
		}
	}
	
	public function getContent(){
		return $this->_content;
	}
	
}

