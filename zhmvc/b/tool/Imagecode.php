<?php
namespace ZHMVC\B\TOOL;
class Imagecode
{
    private $width;
    private $height;
    private $counts;
    private $distrubcode;
    private $fonturl;
    private $session;

    function __construct($width = 120, $height = 30, $counts = 4, $distrubcode = "1235467890qwertyuipkjhgfdaszxcvbnm")
    {
        $this->width = $width;
        $this->height = $height;
        $this->counts = $counts;
        $this->distrubcode = $distrubcode;
        $this->fonturl = ZH_PATH.DS."zhmvc".DS."b".DS."tool".DS."font".DS."TektonPro-BoldCond.otf";

        $this->session = $this->sessioncode();
        if (! isset($_SESSION)) {
			session_start();
		}
        $_SESSION['Verification'] = $this->session;
    }

    function imageout()
    {
        $im = $this->createimagesource();
        $this->setbackgroundcolor($im);
        $this->set_code($im);
        //$this->setdistrubecode($im);
        ImageGIF($im);
        ImageDestroy($im);
    }

    private function createimagesource()
    {
        return imagecreate($this->width, $this->height);
    }

    private function setbackgroundcolor($im)
    {
        $bgcolor = ImageColorAllocate($im, rand(200, 255), rand(200, 255), rand(200, 255)); // 背景颜色
        imagefill($im, 0, 0, $bgcolor);
    }

    private function setdistrubecode($im)
    {
        $count_h = $this->height;
        $cou = floor($count_h * 2);
        for ($i = 0; $i < $cou; $i ++) {
            $x = rand(0, $this->width);
            $y = rand(0, $this->height);
            $jiaodu = rand(0, 360);
            $fontsize = rand(8, 15);
            $fonturl = $this->fonturl;
            $originalcode = $this->distrubcode;
            $countdistrub = strlen($originalcode);
            $dscode = $originalcode[rand(0, $countdistrub - 1)];
            $color = ImageColorAllocate($im, rand(40, 140), rand(40, 140), rand(40, 140));
            imagettftext($im, $fontsize, $jiaodu, $x, $y, $color, $fonturl, $dscode);
        }
    }

    private function set_code($im)
    {
        $width = $this->width;
        $counts = $this->counts;
        $height = $this->height;
        $scode = $this->session;
        $y = floor($height / 2) + floor($height / 4);
        $fontsize = rand(14, 20);
        //echo $this->fonturl;
        $counts = $this->counts;
        for ($i = 0; $i < $counts; $i ++) {
            $char = $scode[$i];
            $x = floor($width / $counts) * $i + 10;
            $jiaodu = rand(- 10, 30);
            $color = ImageColorAllocate($im, rand(0, 50), rand(50, 100), rand(100, 140));
            imagettftext($im, $fontsize, $jiaodu, $x, $y, $color, $this->fonturl, $char);
        }
    }

    private function sessioncode()
    {
        $originalcode = $this->distrubcode;
        $countdistrub = strlen($originalcode);
        $_dscode = "";
        $counts = $this->counts;
        for ($j = 0; $j < $counts; $j ++) {
            $dscode = $originalcode[rand(0, $countdistrub - 1)];
            $_dscode .= $dscode;
        }
        return $_dscode;
    }
}

