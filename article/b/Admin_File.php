<?php
namespace ARTICLE\B;

class Admin_File
{

    public function Admin_File()
    {
        
    }

    public function add($qianzui, $mulu, $name, $modulename, $rpctype = '本地', $rpcurl = '', $rpcmainkey = '',$rpchost='',$rpcprivatekey='',$rpcmoduleid=1)
    {
        $c = "1";
        $filepath = ZH_PATH . DS . $mulu . DS . "install" . DS . "flock" . ZH;
        $fp = fopen($filepath, 'w');
        fwrite($fp, $c);
        fclose($fp);
        $d = '<?php
                $' . $mulu . '_module_name="' . $name . '";
                $' . $mulu . '_module_prefix="' . $qianzui . '";
                $' . $mulu . '_module_path="' . $mulu . '";
                $' . $mulu . '_module_rpctype="' . $rpctype . '";
                $' . $mulu . '_module_rpcurl="' . $rpcurl . '";
                $' . $mulu . '_module_rpcmainkey="' . $rpcmainkey . '";
                $' . $mulu . '_module_rpchost="' . $rpchost . '";
                $' . $mulu . '_module_rpcprivatekey="' . $rpcprivatekey . '";
                $' . $mulu . '_module_rpcmoduleid="' . $rpcmoduleid . '";
                $_pre="' . $qianzui . '";
                $_path="' . $mulu . '";
                ';
        $filepath = ZH_PATH . DS . $mulu . DS . "config" . ZH;
        $fp = fopen($filepath, 'w');
        fwrite($fp, $d);
        fclose($fp);
        
        $e = '<?php
namespace ' . strtoupper($modulename) . '\\B;
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
       $this->module="' . $name . '";
	   $this->modulename="' . $modulename . '";
	   $this->moduleprefix="' . $qianzui . '";
	   $this->modulepath="' . $mulu . '";
	   $this->rpctype="' . $rpctype . '";
	   $this->rpcmainkey="' . $rpcmainkey . '";
	   $this->rpcurl="' . $rpcurl . '";
	   $this->rpchost="' . $rpchost . '";
	   $this->rpcprivatekey="' . $rpcprivatekey . '";
	   $this->rpcmoduleid="' . $rpcmoduleid . '";
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
';
        $filepath = ZH_PATH . DS . $mulu . DS . "b" . DS . "ClassConfig" . ZH;
        $fp = fopen($filepath, 'w');
        fwrite($fp, $e);
        fclose($fp);
        
        
    }

    public function __destruct()
    {}
}