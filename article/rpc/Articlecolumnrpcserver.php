<?php
namespace ARTICLE\D;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers:x-requested-with,content-type");
if (! isset($_SESSION)) {
    session_start();
}
include(dirname(dirname(dirname(__FILE__)))."/zhconfig/Config.php");
include(dirname(dirname(__FILE__))."/config.php");
$Articlecolumnrpcserver = new \ARTICLE\D\Articlecolumnrpc;
\ZHMVC\B\RPC\jsonRPCServer::handle($Articlecolumnrpcserver) or print "no request";
