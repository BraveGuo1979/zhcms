<?php
if (! isset($_SESSION)) {
    session_start();
}
include (str_replace('', '/', dirname(__FILE__)) . "/config.php");
include (SITE_PATH . '/islogin.php');
phpinfo();

