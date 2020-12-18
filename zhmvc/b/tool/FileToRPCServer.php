<?php
namespace ZHMVC\B\TOOL;

class FileToRPCServer
{
    private $_table;
    private $_file1;
    private $_path1;
    private $_pre;
    private $_content1;//数据管理类

    
    public function __construct($pre,$tablename,$pathname1,$filename1,$modulename)
    {
        $this->_table=$pre.$tablename;
        $this->_file1=$filename1;
        $this->_path1=$pathname1;
       
        $this->content1='<?php
namespace '.strtoupper($modulename).'\\D;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers:x-requested-with,content-type");
if (! isset($_SESSION)) {
    session_start();
}
include(dirname(dirname(dirname(__FILE__)))."/zhconfig/Config.php");
include(dirname(dirname(__FILE__))."/config.php");
$'.$this->_file1.' = new \\'.strtoupper($modulename).'\\D\\'.ucwords($tablename).'rpc;
\ZHMVC\B\RPC\jsonRPCServer::handle($'.$this->_file1.') or print "no request";
';
    }
    
    public function saveContent()
    {
        
        $filepath1=ZH_PATH.DS.$this->_path1.$this->_file1. ZH;
        
        if (file_exists($filepath1)) {
        
        } else {
            $fp = fopen($filepath1,'w');
            fwrite($fp,$this->content1);
            fclose($fp);
        }
       
    }
    
}