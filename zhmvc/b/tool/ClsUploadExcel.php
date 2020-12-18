<?php
namespace ZHMVC\B\TOOL;
class ClsUpload
{
    public function __construct()
    {
        
    }
    
    public function getHtml($filename){
        $str='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
        <body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
        <form name="upload" id="upload" ENCTYPE="multipart/form-data" method="post" action="?atcion=save&filename='.$filename.'">
        <input type="file" id="userfile" name="'.$filename.'">
        <input name="upload" value="上传" type="submit" name="Submit">
        <input name="reset" value="重置" type="reset" name="cancelit">
        </form>
        </body>
        </html>';
        return $str;
    }
    
    public function saveFile($files,$basedir,$fromname,$fromimg,$savedir)
    {
        
        $res='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">';
        //上传文件后缀检查
        if($files){
            foreach( $files as $key => $_value )
            {
                $files[$key]['type'] =$_value['type'];
            }
            if(substr($files[$key]['type'],0,16) !='application/vnd.')
            {
                $res="您选择上传文件格式错误，请重试";
            }
            else 
            {
                $temfilename = $files['userfile']['name'];
                $temphouzhui=explode('.', $files['userfile']['name']);
                
                $newTempFileName='.'.$temphouzhui[(count($temphouzhui)-1)];
                //$newTempFileName=substr($files['userfile']['name'], -4);
                $random =rand(1,4000);
                $filename=$this->formatDateTime(date("Y-m-d G:i:s"),4);
                
                if(!file_exists($basedir))
                {
                    $old=umask(0);
                    mkdir($basedir,0775);
                    umask($old);
                }
                
                $basedir.="/".date('Y');
                $savedir.="/".date('Y');
                if(!file_exists($basedir))
                {
                    $old=umask(0);
                    mkdir($basedir,0775);
                    umask($old);
                }
                
                $basedir.="/".date('m');
                $savedir.="/".date('m');
                if(!file_exists($basedir))
                {
                    $old=umask(0);
                    mkdir($basedir,0775);
                    umask($old);
                }
                
                $basedir.="/".date('d');
                $savedir.="/".date('d');
                if(!file_exists($basedir))
                {
                    $old=umask(0);
                    mkdir($basedir,0775);
                    umask($old);
                }
                
                $uploadPath=$basedir."/".$filename.$random.$newTempFileName;
                $uploadPath1=$savedir."/".$filename.$random.$newTempFileName;
                $tmpfile=$files['userfile']['tmp_name'];
                $uploaderr=$files['userfile']['error'];
                
                if(($tmpfile!="none")&&($tmpfile!=""))
                {
                    if(move_uploaded_file($tmpfile, $uploadPath))
                    {
                        $res="<script>parent.document.".$fromname.".".$fromimg.".value='" .$uploadPath1."'</script>上传成功.";
                    }
                }
                else
                {
                    $res="您没有选择上传文件或者上传超时，请重试";
                }
            }
        }
        else
        {
            $res="您没有选择上传文件或者上传超时，请重试";
        }
        
        return $res;
    }
    
    public function get_extension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
    
    public function formatDateTime($time,$way)
    {
        //分解时间串，格式为：0000-00-00 00:00:00
        $strDateTime = explode(" ",$time);
        //分解年月日;
        $strDate = explode("-",$strDateTime[0]);
        $year  = $strDate[0];
        $month = $strDate[1];
        $day   = $strDate[2];
        //分解时分秒;
        $strTime  = explode(":",$strDateTime[1]);
        $hour     = $strTime[0];
        $minute   = $strTime[1];
        $second   = $strTime[2];
        switch ($way)
        {
            case 1://得到年月日；
                $strDateTime = $year."年".$month."月".$day."日";
                break;
            case 2://月日；
                $strDateTime = $month."-".$day;
                break;
            case 3://得到时分；
                $strDateTime = $hour.":".$minute.":".$second;
                break;
            case 4://得到年月日；
                $strDateTime = $year.$month.$day.$hour.$minute.$second;
                break;
            case 5://得到年月日；
                $strDateTime = $year.".".$month.".".$day;
                break;
            case 6://得到年月日；
                $strDateTime = $day."日";
                break;
            case 7://得到年月日；
                $strDateTime = $hour.":".$minute;
                break;
        }
        return $strDateTime;
    }
    
    public function __destruct () {
        
    }
}

?>