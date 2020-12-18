<?php
include(dirname(dirname(dirname(__FILE__)))."/zhconfig/Config.php");
Header("Content-type: image/GIF");
$imagecode = new \ZHMVC\B\TOOL\Imagecode(80, 30);
$imagecode->imageout();