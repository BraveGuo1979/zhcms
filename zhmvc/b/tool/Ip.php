<?php
namespace ZHMVC\B\TOOL;
class Ip
{
  function getip() 
 { 
   if(getenv('HTTP_CLIENT_IP')) 
   { 
    $onlineip = getenv('HTTP_CLIENT_IP'); 
   } 
   elseif(getenv('REMOTE_ADDR')) 
   { 
    $onlineip = getenv('REMOTE_ADDR'); 
   } 
   else 
   { 
    $onlineip = $_SERVER['REMOTE_ADDR']; 
   } 
   return $onlineip; 
 } 
 
 //获取用户真实IP 
 function getIpNew() { 
     if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
         $ip = getenv("HTTP_CLIENT_IP"); 
     else 
         if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
             $ip = getenv("HTTP_X_FORWARDED_FOR"); 
         else 
             if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
                 $ip = getenv("REMOTE_ADDR"); 
             else 
                 if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
                     $ip = $_SERVER['REMOTE_ADDR']; 
                 else 
                     $ip = "unknown"; 
     return ($ip); 
 }
 
 function set_ip()
 {
 	return $this->getip();
 }
}