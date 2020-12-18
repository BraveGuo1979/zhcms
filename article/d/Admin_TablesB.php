<?php
namespace ARTICLE\D;

class Admin_TablesB extends \ZHMVC\D\DataBase
{

    public function add($qianzui, $pathname1, $pathname2, $modulename, $moduleid, $mulu, $rpcpathname, $rpctype = '本地', $rpcurl = '', $rpcmainkey = '',$rpchost='',$rpcprivatekey='',$rpcmoduleid=1)
    {
        //1，批次
        $tablename="pici";
        $sql = "CREATE TABLE `".$qianzui.$tablename."` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(250) DEFAULT NULL COMMENT '姓名',
              `isok`  enum('0','1') NULL DEFAULT '0' COMMENT '是否生效' ,
               PRIMARY KEY (`id`)
            );";
        
        $this->_db->update($sql);
        
        // 添加模块类表
        $mt = new \ZHMVC\D\ModuleTable();
        $mt->add($moduleid, ucwords($tablename));
        // 生成伪表文件
        $tf = new \ZHMVC\B\TOOL\TableToFile($qianzui, $tablename, $pathname1, $pathname2, ucwords($tablename), "admin_" . strtolower($tablename), $modulename,$rpctype, $rpcurl, $rpcmainkey, $rpchost, $rpcprivatekey, $rpcmoduleid);
        $tf->parse();
        $tf->saveContent();
        
        //生成rpc文件
        //生成服务端
        $rpcs = new \ZHMVC\B\TOOL\FileToRPCServer($qianzui, $tablename, $rpcpathname, ucwords($tablename)."rpcserver", $modulename);
        $rpcs->saveContent();
        //插入rpc服务器列表
        $arpc = new \ZHMVC\D\ModuleRpcList();
        $arpc->add($moduleid,'http://'.$_SERVER['SERVER_NAME'].$rpcpathname.ucwords($tablename)."rpcserver".ZH);
        
        
        return 1;
    }
}
