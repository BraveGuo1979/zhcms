<?php
include(dirname(dirname(__FILE__))."/zhconfig/Config.php");

$db=new \ARTICLE\D\Articlecontent();

$zhs=new \zhsearch($db);
$aid=1;
$keyword='事业';

$limit='';

$contents='（原标题：习近平致信祝贺中央电视台建台暨新中国电视事业诞生60周年）习近平致信祝贺中央电视台建台暨新中国电视事业诞生60周年强调锐意改革创新　壮大主流舆论努力打造具有强大引领力传播力影响力的国际一流新型主流媒体
新华社北京9月26日电（记者胡浩）在中央电视台';
/*$zhs->add($contents, $aid);
*/
//$num=$zhs->searchNum($keyword);
//var_dump($num);
$s=$zhs->searchPage($keyword);
$row=$zhs->getRows();
print_r($s);
var_dump($row);
