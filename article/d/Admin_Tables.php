<?php
namespace ARTICLE\D;

class Admin_Tables extends \ZHMVC\D\DataBase
{

    public function add($qianzui, $pathname1, $pathname2, $modulename, $moduleid, $mulu, $rpcpathname, $rpctype = '本地', $rpcurl = '', $rpcmainkey = '',$rpchost='',$rpcprivatekey='',$rpcmoduleid=1)
    {
        
        //1，栏目表
        $tablename="articlecolumn";
        $sql = "CREATE TABLE `".$qianzui.$tablename."` (
              `id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目id',
              `mainkey` char(32) NOT NULL,
              `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目名称',
              `fid` int(4) NOT NULL DEFAULT '0' COMMENT '父id',
              `type` int(4) NOT NULL DEFAULT '0' COMMENT '栏目类型',
              `columntype` enum('2','1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否为栏目，1代表是文章的栏目，0代表为系统的栏目',
              `info` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目描述',
              PRIMARY KEY (`id`),
              KEY `fid` (`fid`)
            )";
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
        
        //2，内容表
        $tablename="articlecontent";
        $sql = "CREATE TABLE `".$qianzui.$tablename."` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章id',
              `mainkey` char(32) NOT NULL,
              `columnid` int(6) NOT NULL DEFAULT '0' COMMENT '文章所属栏目编码',
              `columnname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文章所属栏目名称',
              `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文章标题',
              `addtime` datetime DEFAULT NULL COMMENT '文章添加时间',
              `content` longtext COLLATE utf8_unicode_ci COMMENT '文章内容',
              `keyword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文章关键字',
              `fromname` varchar(250) COLLATE utf8_unicode_ci DEFAULT '本站' COMMENT '文章来源',
              `viewnum` bigint(20) unsigned DEFAULT '0' COMMENT '文章浏览数',
              `byte` bigint(20) unsigned DEFAULT '0' COMMENT '文章长度',
              `tcolor` varchar(250) NOT NULL DEFAULT '' COMMENT '文章标题颜色',
              `author` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文章作者',
              `img` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片地址',
              `isimg` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '文章是否图片文章',
              `isdel` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '文章是否删除',
              `ispass` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '文章是否审核',
              `istop` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '文章是否置顶',
              `contentlistid` bigint(20) NOT NULL DEFAULT '0',
              `adminid` int(10) NOT NULL DEFAULT '0',
              `cjroot` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '集采地址',
              PRIMARY KEY (`id`),
              KEY `columnid` (`columnid`),
              KEY `isdel` (`isdel`),
              KEY `ispass` (`ispass`),
              KEY `isimg` (`isimg`),
              KEY `istop` (`istop`),
              KEY `adminid` (`adminid`)
            )";
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
