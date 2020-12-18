<?php
include(dirname(dirname(__FILE__))."/zhconfig/Config.php");
$news_config=new \ARTICLE\B\ClassConfig();
$newpath=$news_config->getModulePath();
$smarty->assign('newpath',$newpath);

$news_list_conn=new \ARTICLE\D\Articlecolumn();
$news_conn=new \ARTICLE\D\Articlecontent();
//获取所有一级栏目
$map['fid']=0;
$column_rs1=$news_list_conn->getAll($map);
$smarty->assign('columnrs',$column_rs1);
$map=null;
$smarty->display('about.html',request_uri());