<?php
/*
 * $vendorMapNew = array(
 * array(
 * "name"=>"smarty",
 * "value"=>ZH_PATH.DS."mod".DS."smarty".DS."Smarty.class".ZH,
 * "type"=>"mod",
 * "loadtype"=>"file"),
 * array("name"=>"member","value"=>ZH_PATH.DS."member".DS."d".DS."member".ZH,"type"=>"mod","loadtype"=>"file"),
 * array("name"=>"WechatPhpSdk","value"=>ZH_PATH.DS."mod".DS."Wechat".DS."include".ZH,"type"=>"mod","loadtype"=>"file")
 * );
 */
$vendorMapNew[] = array("name" => "zhsearch","value" => ZH_PATH . DS . "mod" . DS . "zhsearch" . DS . "Zhsearch.class" . ZH,"type" => "mod","loadtype" => "file");
$vendorMapNew[] = array("name" => "smarty","value" => ZH_PATH . DS . "mod" . DS . "smarty" . DS . "Smarty.class" . ZH,"type" => "mod","loadtype" => "file");
$vendorMapNew[] = array("name" => "WechatPhpSdk","value" => ZH_PATH . DS . "mod" . DS . "Wechat" . DS . "include" . ZH,"type" => "mod","loadtype" => "file");
$vendorMapNew[] = array("name" => "article","value" => ZH_PATH . DS ."article". DS . "map" .ZH,"type" => "mod","loadtype" => "module");
$vendorMapNew[] = array("name" => "collection","value" => ZH_PATH . DS ."collection". DS . "map" .ZH,"type" => "mod","loadtype" => "module");