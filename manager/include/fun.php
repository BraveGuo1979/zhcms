<?php
function replace($nei)
{
    // $nei=str_replace("\n","<br>",$nei);
    // $nei=str_replace("\r","",$nei);
    $nei = htmlspecialchars($nei);
    $nei = str_replace("'", "&apos;", $nei);
    return $nei;
}

function un_replace($nei)
{
    $nei = str_replace("&lt;", "<", $nei);
    $nei = str_replace("&gt;", ">", $nei);
    $nei = str_replace("&amp;", "&", $nei);
    $nei = str_replace("&apos;", "'", $nei);
    $nei = str_replace("&quot;", "\"", $nei);
    $nei = str_replace("&#039;", "\'", $nei);
    return $nei;
}

Function SafeRequest($ParaName, $ParaType)
{
    $ParaName = trim($ParaName);
    // 1=数字型，0=文本型
    if ($ParaName != "") {
        If ($ParaType == "1") {
            If (! is_numeric($ParaName)) {
                echo "<script>alert('对不起，您的输入有误，请返回后重新输入');history.go(-1);</script>";
                exit();
            } else {
                $ParaName = str_replace(" ", "", $ParaName);
            }
        } else {
            $ParaName = str_replace("<", "《", $ParaName);
            $ParaName = str_replace(">", "》", $ParaName);
            $ParaName = str_replace("\"", "“", $ParaName);
            $ParaName = str_replace("\'", "‘", $ParaName);
            $ParaName = str_replace("select", "Ｓelect", $ParaName);
        }
        return $ParaName;
    } else {
        return $ParaName;
    }
}

/**
 * 获取$_GET、$_POST、$_COOKIE变量 来自discuz
 * 
 * @param
 *            {string} 变量
 * @param
 *            {string} 类型G、P、C，分别代表$_GET、$_POST、$_COOKIE
 */
function getPGC($k, $type = 'GP')
{
    if ($type == 0)
        $type = 'GP';
    $type = strtoupper($type);
    switch ($type) {
        case 'G':
            $var = $_GET;
            break;
        case 'P':
            $var = $_POST;
            break;
        case 'C':
            $var = $_COOKIE;
            break;
        default:
            if (isset($_GET[$k])) {
                $var = $_GET;
            } else {
                $var = $_POST;
            }
            break;
    }
    return isset($var[$k]) ? $var[$k] : NULL;
}

Function SafeRequest1($ParaName, $ParaType)
{
    // 1=数字型，0=文本型
    if ($ParaName != "") {
        If ($ParaType == "1") {
            If (! is_numeric($ParaName)) {
                echo "<script>alert('对不起，您的输入有误，请返回后重新输入');history.go(-1);</script>";
                exit();
            } else {
                $ParaName = str_replace(" ", "", $ParaName);
            }
        } else {
            $ParaName = str_replace("<", "《", $ParaName);
            $ParaName = str_replace(">", "》", $ParaName);
            $ParaName = str_replace("\"", "“", $ParaName);
            $ParaName = str_replace("\'", "‘", $ParaName);
            $ParaName = str_replace("select", "Ｓelect", $ParaName);
            $ParaName = str_replace("Select", "Ｓelect", $ParaName);
            $ParaName = str_replace("insert", "Ｉnsert", $ParaName);
            $ParaName = str_replace("Insert", "Ｉnsert", $ParaName);
            $ParaName = str_replace("Update", "Ｕpdate", $ParaName);
            $ParaName = str_replace("update", "Ｕpdate", $ParaName);
            $ParaName = str_replace("del", "Ｄel", $ParaName);
            $ParaName = str_replace("Del", "Ｄel", $ParaName);
        }
        return $ParaName;
    } else {
        return $ParaName;
    }
}

Function SafeRequest2($ParaName, $ParaType)
{
    // 1=数字型，0=文本型
    if ($ParaName != "") {
        If ($ParaType == "1") {
            If (! is_numeric($ParaName)) {
                echo "<script>alert('对不起，您的输入有误，请返回后重新输入');history.go(-1);</script>";
                exit();
            } else {
                $ParaName = str_replace(" ", "", $ParaName);
            }
        } else {
            $ParaName = str_replace("<", "《", $ParaName);
            $ParaName = str_replace(">", "》", $ParaName);
            $ParaName = str_replace("\"", "“", $ParaName);
            $ParaName = str_replace("\'", "‘", $ParaName);
            $ParaName = str_replace("select", "Ｓelect", $ParaName);
            $ParaName = str_replace("Select", "Ｓelect", $ParaName);
            $ParaName = str_replace("insert", "Ｉnsert", $ParaName);
            $ParaName = str_replace("Insert", "Ｉnsert", $ParaName);
            $ParaName = str_replace("Update", "Ｕpdate", $ParaName);
            $ParaName = str_replace("update", "Ｕpdate", $ParaName);
            $ParaName = str_replace("del", "Ｄel", $ParaName);
            $ParaName = str_replace("Del", "Ｄel", $ParaName);
        }
        return $ParaName;
    } else {
        return $ParaName;
    }
}

function huanyuan($ParaName)
{
    $ParaName = str_replace("《span", "<span", $ParaName);
    $ParaName = str_replace("=“", "=\"", $ParaName);
    $ParaName = str_replace("“》", "\">", $ParaName);
    $ParaName = str_replace("《/span》", "</span>", $ParaName);
    $ParaName = str_replace("《/span》", "</span>", $ParaName);
    $ParaName = str_replace("《br》", "<br />", $ParaName);
    $ParaName = str_replace("《", "<", $ParaName);
    $ParaName = str_replace("》", ">", $ParaName);
    $ParaName = str_replace("《", "<", $ParaName);
    $ParaName = str_replace("“", "\"", $ParaName);
    return $ParaName;
}

function cut($Str, $Length)
{ // $Str为截取字符串，$Length为需要截取的长度
    $i = 0;
    $l = 0;
    $ll = strlen($Str);
    $s = $Str;
    $f = true;
    while ($i <= $ll) {
        if (ord($Str{$i}) < 0x80) {
            $l ++;
            $i ++;
        } else 
            if (ord($Str{$i}) < 0xe0) {
                $l ++;
                $i += 2;
            } else 
                if (ord($Str{$i}) < 0xf0) {
                    $l += 2;
                    $i += 3;
                } else 
                    if (ord($Str{$i}) < 0xf8) {
                        $l += 1;
                        $i += 4;
                    } else 
                        if (ord($Str{$i}) < 0xfc) {
                            $l += 1;
                            $i += 5;
                        } else 
                            if (ord($Str{$i}) < 0xfe) {
                                $l += 1;
                                $i += 6;
                            }
        if (($l >= $Length - 1) && $f) {
            
            $s = substr($Str, 0, $i);
            $f = false;
        }
        if (($l > $Length) && ($i < $ll)) {
            $s = $s . '...';
            break; // 如果进行了截取，字符串末尾加省略符号“...”
        }
    }
    return $s;
}

function cut1($Str, $Length)
{ // $Str为截取字符串，$Length为需要截取的长度
    $i = 0;
    $l = 0;
    $ll = strlen($Str);
    $s = $Str;
    $f = true;
    while ($i <= $ll) {
        if (ord($Str{$i}) < 0x80) {
            $l ++;
            $i ++;
        } else 
            if (ord($Str{$i}) < 0xe0) {
                $l ++;
                $i += 2;
            } else 
                if (ord($Str{$i}) < 0xf0) {
                    $l += 2;
                    $i += 3;
                } else 
                    if (ord($Str{$i}) < 0xf8) {
                        $l += 1;
                        $i += 4;
                    } else 
                        if (ord($Str{$i}) < 0xfc) {
                            $l += 1;
                            $i += 5;
                        } else 
                            if (ord($Str{$i}) < 0xfe) {
                                $l += 1;
                                $i += 6;
                            }
        if (($l >= $Length - 1) && $f) {
            $s = str_replace("有限", "", $s);
            $s = str_replace("有限责任", "", $s);
            $s = str_replace("责任", "", $s);
            $s = cut($s, $Length);
        }
    }
    return $s;
}

function formatDateTime($time, $way)
{
    // 分解时间串，格式为：0000-00-00 00:00:00
    $strDateTime = explode(" ", $time);
    // 分解年月日;
    $strDate = explode("-", $strDateTime[0]);
    $year = $strDate[0];
    $month = $strDate[1];
    $day = $strDate[2];
    // 分解时分秒;
    $strTime = explode(":", $strDateTime[1]);
    $hour = $strTime[0];
    $minute = $strTime[1];
    $second = $strTime[2];
    switch ($way) {
        case 1: // 得到年月日；
            $strDateTime = $year . "年" . $month . "月" . $day . "日";
            break;
        case 2: // 月日；
            $strDateTime = $month . "-" . $day;
            break;
        case 3: // 得到时分；
            $strDateTime = $hour . ":" . $minute . ":" . $second;
            break;
        case 4: // 得到年月日；
            $strDateTime = $year . $month . $day . $hour . $minute . $second;
            break;
        case 5: // 得到年月日；
            $strDateTime = $year . "." . $month . "." . $day;
            break;
        case 6: // 得到年月日；
            $strDateTime = $day . "日";
            break;
        case 7: // 得到年月日；
            $strDateTime = $hour . ":" . $minute;
            break;
    }
    return $strDateTime;
}

function DateDiff($interval, $date1, $date2)
{
    $time_difference = strtotime($date2) - strtotime($date1);
    switch ($interval) {
        case "w":
            $retval = bcdiv($time_difference, 604800);
            break;
        case "d":
            $retval = bcdiv($time_difference, 86400);
            break;
        case "h":
            $retval = bcdiv($time_difference, 3600);
            break;
        case "n":
            $retval = bcdiv($time_difference, 60);
            break;
        case "s":
            $retval = $time_difference;
            break;
    }
    return $retval;
}

function cnSubStr($string, $sublen)
{
    if ($sublen >= strlen($string)) {
        return $string;
    }
    $s = "";
    for ($i = 0; $i < $sublen; $i) {
        if (ord($string{$i}) > 127) {
            $s .= $string{$i} . $string{$i};
            continue;
        } else {
            $s .= $string{$i};
            continue;
        }
    }
}

/* 功能:显示搜索中要搜索的关键词 */
function keywordcolor($stmkeyword, $strings)
{
    for ($i = 0, $max = count($stmkeyword); $i < $max; $i ++) {
        $strings = str_replace($stmkeyword[$i], "<font color=#ff0000>" . $stmkeyword[$i] . "</font>", $strings);
    }
    return $strings;
}

/* 功能:显示一个漂亮的表格 */
function useColor()
{
    /*
     * * 请牢记我们最后使用过的颜色标记
     */
    static $ColorValue;
    /* 选择下一个颜色 */
    if ($ColorValue == "#FFFFFF") {
        $ColorValue = "#f3f3f3";
    } else {
        $ColorValue = "#FFFFFF";
    }
    
    return ($ColorValue);
}

function fontquantoban($ParaName)
{
    $ParaName = preg_replace('/\xa3([\xa1-\xfe])/e', 'chr(ord(\1)-0x80)', $ParaName);
    return ($ParaName);
}

function htou($str)
{
    $tmpstr = explode("&#", $str);
    for ($i = 1, $max = count($tmpstr); $i < $max; $i ++) {
        $tmp .= "&#" . $tmpstr[$i] . ";";
    }
    return $tmp;
}

function DelCode($str)
{
    $str = str_replace('《', '', $str);
    $str = str_replace('》', '', $str);
    $search = array(
        "'<script[^>]*?>.*?</script>'si", // strip out javascript
        "'<[\/\!]*?[^<>]*?>'si", // strip out html tags
        "'([\r\n])[\s]+'", // strip out white space
        "'&(quot|#34|#034|#x22);'i", // replace html entities
        "'&(amp|#38|#038|#x26);'i", // added hexadecimal values
        "'&(lt|#60|#060|#x3c);'i",
        "'&(gt|#62|#062|#x3e);'i",
        "'&(nbsp|#160|#xa0);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&(reg|#174);'i",
        "'&(deg|#176);'i",
        "'&(#39|#039|#x27);'",
        "'&(euro|#8364);'i", // europe
        "'&a(uml|UML);'", // german
        "'&o(uml|UML);'",
        "'&u(uml|UML);'",
        "'&A(uml|UML);'",
        "'&O(uml|UML);'",
        "'&U(uml|UML);'",
        "'&szlig;'i"
    );
    $replace = array(
        "",
        "",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
        chr(161),
        chr(162),
        chr(163),
        chr(169),
        chr(174),
        chr(176),
        chr(39),
        chr(128),
        "?",
        "?",
        "?",
        "?",
        "?",
        "?",
        "?"
    );
    $str = preg_replace($search, $replace, $str);
    return trim($str);
}

/**
 * *截取字符
 * *
 */
function get_word($content, $length, $more = 1)
{
    if (WEB_LANG == 'utf-8') {
        $content = get_utf8_word($content, $length, $more);
        return $content;
    }
    
    if (WEB_LANG == 'big5') {
        $more = 1; // 不这样的话.截取字符容易使用页面乱码
    }
    
    if (! $more) {
        $length = $length + 2;
    }
    if ($length > 10) {
        $length = $length - 2;
    }
    if ($length && strlen($content) > $length) {
        $num = 0;
        for ($i = 0; $i < $length - 1; $i ++) {
            if (ord($content[$i]) > 127) {
                $num ++;
            }
        }
        $num % 2 == 1 ? $content = substr($content, 0, $length - 2) : $content = substr($content, 0, $length - 1);
        $more && $content .= '..';
    }
    return $content;
}
