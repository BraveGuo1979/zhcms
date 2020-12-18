<?php
namespace ZHMVC\DB\MANAGER;

class ShowIp
{

    function getip()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('REMOTE_ADDR')) {
            $onlineip = getenv('REMOTE_ADDR');
        } else {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }

    function set_ip()
    {
        return $this->getip();
    }
}