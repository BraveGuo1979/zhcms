<?php
namespace ARTICLE\B;
class ClassConfig
{
    private $module="";//模块
    private $modulename="";//模块名称
    private $moduleprefix="";//模块前缀
    private $modulepath="";//模块路径
    private $rpctype="";//数据类型。是本地还是远程数据
    private $rpcurl="";//如果是远程的话，远程地址
    private $rpcmainkey="";//数据key
    private $rpchost="";//本地站点地址
    private $rpcprivatekey="";//私钥
    private $rpcmoduleid="";//对接的模块id
		
    function __construct()
    {
       $this->module="文章管理";
	   $this->modulename="article";
	   $this->moduleprefix="art_";
	   $this->modulepath="article";
	   $this->rpctype="本地";
	   $this->rpcmainkey="";
	   $this->rpcurl="";
	   $this->rpchost="";
	   $this->rpcprivatekey="";
	   $this->rpcmoduleid="1";
	}
  
	public function getRpcHost(){
		return $this->rpchost;
	}
  
	public function getRpcPrivateKey(){
		return $this->rpcprivatekey;
	}
  
	public function getRpcModuleId(){
		return $this->rpcmoduleid;
	}
  
	public function getRpcType(){
		return $this->rpctype;
	}
	
	public function getRpcMainKey(){
		return $this->rpcmainkey;
	}
	
	public function getRpcUrl(){
		return $this->rpcurl;
	}
	
	public function getModulen(){
		return $this->module;
	}
		
    public function getModulenName(){
		return $this->modulename;
	}
		
	public function getModulePrefix(){
		return $this->moduleprefix;
	}
		
	public function getModulePath(){
		return $this->modulepath;
	}
		
    function __destruct()
    {
	   $this->module="";
	   $this->modulename="";
	   $this->moduleprefix="";
	   $this->modulepath="";
	   $this->rpctype="";
	   $this->rpcmainkey="";
	   $this->rpcurl="";
	   $this->rpchost="";
	   $this->rpcprivatekey="";
	   $this->rpcmoduleid="";
	}
}
