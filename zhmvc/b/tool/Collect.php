<?php
namespace ZHMVC\B\TOOL;

class Collect
{
    private $_url;//要采集的目标地址
    private $_start;//匹配开始标签，唯一
    private $_end;//匹配的结束标签，唯一
    private $_Html;
    private $_OriginalCode;
    private $_NewCode;
    private $_CaijiType;
    
    function __construct($_url, $_start, $_end, $_OriginalCode, $_NewCode) {
        
        $this->_url = $_url;
        $this->_start = $_start;
        $this->_end = $_end;
        $this->_Html="";
        $this->_NewCode = $_NewCode;
        $this->_OriginalCode = $_OriginalCode;
        $this->_CaijiType = 2;
        
    }
    /*
     判断是否可以与目标正常链接
     */
    function isConnect() {
        $status = get_headers($this->_url);
        //	print_r($status);
        
        if (false != stripos($status[0], '200'))
        {
            return true;
        }
        elseif(false != stripos($status[0], '302'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    /*
     获取原始html代码
     检测原始代码编码，不是utf-8则转为utf-8
     */
    function getHtml($s="") {
        
        if($s=="")
        {
            if($this->_CaijiType==1)
            {
                if (self::isConnect()) {
                    $opt=array('http'=>array('header'=>"Referer: '".$this->_url."'"));
                    $context=stream_context_create($opt);
                    //$file_contents = file_get_contents($url,false, $context);
                    $this->_Html = file_get_contents($this->_url,false, $context);
                } else {
                    return false;
                }
            }else
            {
                
                $ip2id= round(rand(600000, 2550000) / 10000); //第一种方法，直接生成
                $ip3id= round(rand(600000, 2550000) / 10000);
                $ip4id= round(rand(600000, 2550000) / 10000);
                //下面是第二种方法，在以下数据中随机抽取
                $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
                $randarr= mt_rand(0,count($arr_1)-1);
                $ip1id = $arr_1[$randarr];
                $randip=$ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
                $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.79 Safari/537.36";//模拟windows用户正常访问
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->_url);
                curl_setopt($ch, CURLOPT_TIMEOUT,30);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$randip, 'CLIENT-IP:'.$randip));
                //追踪返回302状态码，继续抓取
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                
                curl_setopt($ch, CURLOPT_NOBODY, false);
                curl_setopt($ch, CURLOPT_REFERER, 'http://www.mining120.com/');//模拟来路
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_AUTOREFERER,true);
                $this->_Html = curl_exec($ch);
                curl_close($ch);
                //echo $this->_Html;
                /*
                 curl_setopt($ch, CURLOPT_REFERER,'http://www.mining120.com/');
                 curl_setopt($ch, CURLOPT_HEADER, true);
                 curl_setopt($ch, CURLOPT_NOBODY,false);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
                 curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
                 curl_setopt($ch, CURLOPT_AUTOREFERER,true);
                 
                 //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); //是否抓取跳转后的页面
                 $this->_Html = curl_exec($ch);
                 curl_close($ch);
                 */
            }
        }
        else
        {
            $this->_Html =$s;
        }
        
        //echo $this->_Html;
    }
    
    /*
    
    */
    function returnHtml()
    {
        self::getHtml();
        if($this->_OriginalCode!=$this->_NewCode)
        {
            self::getCode($this->_OriginalCode,$this->_NewCode);
        }
        return $this->_Html;
    }
    
    function getCode($strFrom,$strTo)
    {
        //注：如果是windows
        
        if(PHP_OS!="Linux")
        {
            $this->_Html = iconv($strFrom, $strTo."//IGNORE", $this->_Html);
        }
        else
        {
            $this->_Html = iconv($strFrom, $strTo, $this->_Html);
        }
        
    }
    /*
     $html 通过getHtml()得到
         去除空白
         转义可能引起正则混乱的标签
     */
    function cutHtmlNew($contentStart,$contentEnd) {
        if($this->_Html == false || empty($this->_Html)) return false;
       
        //$start = trim($this->_start);
        //$end = trim($this->_end);
        //echo $this->_Html;
        $start = trim($contentStart);
        $end = trim($contentEnd);
        //	echo $start;
        
        //获取真正的要采集的内容块
        self::cutMainHtmlNew();
        //echo $this->_Html;
        //var_dump($this->_Html);
        //echo $this->_start;
        
        $start = preg_replace('/>\s+</', '><', $start);
        $end = preg_replace('/>\s+</', '><', $end);
        $this->_Html = preg_replace('/>\s+</', '><', $this->_Html);
        
        $firstStr=explode($start,$this->_Html);
        
        if(count($firstStr)>0)
        {
            $this->_Html=$firstStr[1];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        $sendStr=explode($end,$this->_Html);
        
        
        if(count($sendStr)>0)
        {
            $this->_Html=$sendStr[0];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        return $this->_Html;
    }
    
    /*
     $html 通过getHtml()得到
     去除空白
     转义可能引起正则混乱的标签
     */
    function cutHtml($contentStart,$contentEnd) {
        self::getHtml();
        
        if($this->_Html == false || empty($this->_Html)) return false;
        
        if($this->_OriginalCode!=$this->_NewCode)
        {
            self::getCode($this->_OriginalCode,$this->_NewCode);
        }
        
        //$start = trim($this->_start);
        //$end = trim($this->_end);
        //echo $this->_Html;
        //echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        $start = trim($contentStart);
        $end = trim($contentEnd);
        //	echo $start;
        //获取真正的要采集的内容块
        self::cutMainHtml();
        //echo $this->_Html;
        //echo $this->_Html;
        //echo $this->_start;
        
        $start = preg_replace('/>\s+</', '><', $start);
        $end = preg_replace('/>\s+</', '><', $end);
        $this->_Html = preg_replace('/>\s+</', '><', $this->_Html);
        
        
        $firstStr=explode($start,$this->_Html);
        
        if(count($firstStr)>0)
        {
            $this->_Html=$firstStr[1];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        $sendStr=explode($end,$this->_Html);
        
        
        if(count($sendStr)>0)
        {
            $this->_Html=$sendStr[0];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        return $this->_Html;
    }
    
    /*
     $html 通过getHtml()得到
     去除空白
     转义可能引起正则混乱的标签
     */
    function cutMainHtmlNew() {
        if($this->_Html == false || empty($this->_Html)) return false;
        
        $start = trim($this->_start);
        $end = trim($this->_end);
        
        $start = preg_replace('/>\s+</', '><', $start);
        $end = preg_replace('/>\s+</', '><', $end);
        $this->_Html = preg_replace('/>\s+</', '><', $this->_Html);
        
        //echo $this->_start;
        $firstStr=explode($start,$this->_Html);
        
        if(count($firstStr)>0)
        {
            $this->_Html=$firstStr[1];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        $sendStr=explode($end,$this->_Html);
        
        
        if(count($sendStr)>0)
        {
            $this->_Html=$sendStr[0];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        return $this->_Html;
    }
    
    /*
     $html 通过getHtml()得到
     去除空白
     转义可能引起正则混乱的标签
     */
    function cutMainHtml() {
        
        self::getHtml();
        
        if($this->_Html == false || empty($this->_Html)) return false;
        
        if($this->_OriginalCode!=$this->_NewCode)
        {
            self::getCode($this->_OriginalCode,$this->_NewCode);
        }
        
        $start = trim($this->_start);
        $end = trim($this->_end);
        
        $start = preg_replace('/>\s+</', '><', $start);
        $end = preg_replace('/>\s+</', '><', $end);
        $this->_Html = preg_replace('/>\s+</', '><', $this->_Html);
        
        //echo $this->_start;
        //var_dump($start);
        
        $firstStr=explode($start,$this->_Html);
        //print_r($firstStr);
        if(count($firstStr)>0)
        {
            $this->_Html=$firstStr[1];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        $sendStr=explode($end,$this->_Html);
        
        
        if(count($sendStr)>0)
        {
            $this->_Html=$sendStr[0];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        return $this->_Html;
    }
    
    /*
     不进行采集内容，直接提取
     $html 通过getHtml()得到
     去除空白
     转义可能引起正则混乱的标签
     */
    function cutContentHtml($_iHtml) {
        //self::getHtml();
        // echo $this->_Html;
        $this->_Html=$_iHtml;
        if($this->_Html == false || empty($this->_Html)) return false;
        
        $start = trim($this->_start);
        $end = trim($this->_end);
        
        $start = preg_replace('/>\s+</', '><', $start);
        $end = preg_replace('/>\s+</', '><', $end); 
        $this->_Html = preg_replace('/>\s+</', '><', $this->_Html);
        
        $firstStr=explode($start,$this->_Html);
        
        if(count($firstStr)>0)
        {
            $this->_Html=$firstStr[1];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        $sendStr=explode($end,$this->_Html);
        if(count($sendStr)>0)
        {
            $this->_Html=$sendStr[0];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        return $this->_Html;
    }
    
    //
    function cutHtmlList($contentStart,$contentEnd)
    {
        self::getHtml();
        
        if($this->_Html == false || empty($this->_Html)) return false;
        
        if($this->_OriginalCode!=$this->_NewCode)
        {
            self::getCode($this->_OriginalCode,$this->_NewCode);
        }
        
        $start = trim($this->_start);
        $end = trim($this->_end);
        
        $contentStart = trim($contentStart);
        $contentEnd = trim($contentEnd);
        
        $contentStart = preg_replace('/>\s+</', '><', $contentStart); 
        $contentEnd = preg_replace('/>\s+</', '><', $contentEnd); 
        
        
        //获取真正的要采集的内容块
        self::cutMainHtml();
        //print_r($contentStart);
        //var_dump($this->_Html);
        $string = str_replace(PHP_EOL, '', $this->_Html); 
        $this->_Html = preg_replace('/>\s+</', '><', $this->_Html); 
        
        $firstStr1=explode($contentStart,$this->_Html);

        if(count($firstStr1)>0)
        {
            $this->_Html=$firstStr1[1];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        $sendStr2=explode($contentEnd,$this->_Html);
        if(count($sendStr2)>0)
        {
            $this->_Html=$sendStr2[0];
        }
        else
        {
            $this->_Html=$this->_Html;
        }
        
        //$firstStr=explode($start,$this->_Html);
        
        $listcount=count($firstStr1)-1;
        
        if($listcount<=0)
        {
            return false;
        }
        
        $listItem=array();
        for($i=1;$i<count($firstStr1);$i++)
        {
            $sendStr=explode($contentEnd,$firstStr1[$i]);
            if(count($sendStr)>0)
            {
                $listItem[$i-1]=$sendStr[0];
            }
            else
            {
                $listItem[$i-1]="";
            }
        }
        return $listItem;
        
    }
    /*
     url地址检查
     $url 待转化url string
             绝对url则直接返回
             相对url则转化为绝对url并返回
     $baseurl 基准url,绝对路径形式，结尾不加'/'
     */
    function checkUrl($url, $baseurl) {
        if (empty($baseurl)) return false;
        if (substr($url, 0, 1) == '/') {
            return $baseurl.$url;
        } else if(substr($url, 0, 2) == './') {
            return $baseurl.'/'.substr($url, 1);
        } else if(substr($url, 0, 7) == 'http://') {
            return $baseurl.'/'.$url;
        } else {
            return $url;
        }
        
    }
}

