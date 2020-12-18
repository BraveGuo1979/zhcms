<?php
namespace ZHMVC\B\RPC;
function is_assoc($array) {
    if(is_array($array)) {
        $keys = array_keys($array);
        return $keys != array_keys($keys);
    }  
    return false;
}
if (!function_exists('gzdecode')) {
    function gzdecode ($data) {
        $flags = ord(substr($data, 3, 1));
        $headerlen = 10;
        $extralen = 0;
        $filenamelen = 0;
        if ($flags & 4) {
            $extralen = unpack('v' ,substr($data, 10, 2));
            $extralen = $extralen[1];
            $headerlen += 2 + $extralen;
        }
        if ($flags & 8) // Filename
            $headerlen = strpos($data, chr(0), $headerlen) + 1;
            if ($flags & 16) // Comment
                $headerlen = strpos($data, chr(0), $headerlen) + 1;
                if ($flags & 2) // CRC at end of file
                    $headerlen += 2;
                    $unpacked = @gzinflate(substr($data, $headerlen));
                    if ($unpacked === FALSE)
                        $unpacked = $data;
                        return $unpacked;
    }
}
class jsonRPCServer
{
    /**
     * *处理一个request类，这个类中绑定了一些请求参数
     *
     * @param object $object            
     * @return boolean
     *
     */
    public static function handle($object)
    {
        // 判断是否是一个rpc json请求
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] != 'application/json') {
            return false;
        }
        // reads the input data
        $getContent=file_get_contents('php://input');
        //print_r($getContent);
        $request = json_decode($getContent, true);
        
        $method = !empty($request['method']) ? $request['method'] : null;
        $params = !empty($request['params']) ? $request['params'] : null;
        $id = !empty($request['id']) ? $request['id'] : null;
        //对参数进行解压解密
        $host=$params['host'];
        $token=$params['token'];
        $moduleid=$params['moduleid'];
        $addtime=$params['addtime'];
        $isjm=$params['isjm'];
        $data=$params['parameter'];
        //var_dump($params);
        //var_dump($isjm=="true" || $isjm=="1" || $isjm==true || $isjm==1);
        if($isjm=="true" || $isjm=="1" || $isjm==true || $isjm==1)
        {
            if (empty($params['parameter']))
            {
                $params['parameter']='';
            }
            else
            {
                $params2 = \ZHMVC\B\TOOL\OpensslEncryptHelper::decryptWithOpenssl($data);
                $params2 = base64_decode($params2);
                $params3 = gzuncompress($params2);
                $params3 = json_decode($params3, true);
                $params['parameter']=$params3;
            }
        }
        
        $params5['name']=$params;
        $response='';
        //var_dump($method);
        // 执行请求类中的接口
        try {
            //print_r($request);
            $re = call_user_func_array(array(
                        $object,
                        $method
                    ), $params5);
            //var_dump($params);
            //echo "////////////////////////////////////\n<br>";
            //var_dump($re);
           //echo "\n<br>////////////////////////////////////\n<br>";
            $request='';
            $error='';
           // exit;
            if($isjm=="true" || $isjm=="1" || $isjm==true || $isjm==1)
            {
                //对参数进行加工
                if (is_array($re)==true) {
                    //判断是否加密压缩
                    $re=json_encode($re);
                    $re = gzcompress($re);
                    $re = base64_encode($re);
                    $re = \ZHMVC\B\TOOL\OpensslEncryptHelper::encryptWithOpenssl($re);
                    $error='';
                }
                elseif (is_string($re)==true) {
                    $re = gzcompress($re);
                    $re = base64_encode($re);
                    $re = \ZHMVC\B\TOOL\OpensslEncryptHelper::encryptWithOpenssl($re);
                    $error='';
                }
                elseif (is_numeric($re)==true) {
                    $re = gzcompress($re);
                    $re = base64_encode($re);
                    $re = \ZHMVC\B\TOOL\OpensslEncryptHelper::encryptWithOpenssl($re);
                    $error='';
                }
                elseif (is_null($re)==true) {
                    $re='';
                    $error='return is null';
                } else {
                    $re='';
                    $error='unknown method or incorrect parameters';
                }
            }
            else{
                if (is_array($re)==true) {
                    $re=json_encode($re);
                    $error='';
                }
                elseif (is_string($re)==true) {
                    $re = $re;
                    $error='';
                }
                elseif (is_numeric($re)==true) {
                    $re = $re;
                    $error='';
                }
                elseif (is_null($re)==true) {
                    $re='';
                    $error='return is null';
                } else {
                    $re='';
                    $error='unknown method or incorrect parameters';
                }
            }
            
            if($re!="")
            {
                if($isjm=="true" || $isjm=="1" || $isjm==true || $isjm==1)
                {
                    $request='{"jsonrpc":"2.0","result":{"token":"'.$token.'","addtime":"'.$addtime .'","isjm":"'. $isjm .'","parameter":"' .$re. '"},"id":"'.$id.'","error":"'.$error.'"}';
                }
                else
                {
                    $request='{"jsonrpc":"2.0","result":{"token":"'.$token.'","addtime":"'.$addtime .'","isjm":"'. $isjm .'","parameter":' .$re. '},"id":"'.$id.'","error":"'.$error.'"}';
                }
            }
            else
            {
                $request='{"jsonrpc":"2.0","result":{"token":"'.$token.'","addtime":"'. $addtime .'","isjm":"'. $isjm .'","parameter":""},"id":"'.$id.'","error":"'.$error.'"}';
            }
            //echo "--------------------------------------------\n<br>";
            //var_dump($request);
            //echo "\n<br>--------------------------------------------\n<br>";
        } catch (\Exception $e) {
            $request='{"jsonrpc":"2.0","result":{"token":"'.$token.'","addtime":"'. $addtime .'","isjm":"'. $isjm .'","parameter":""},"id":"'.$id.'","error":"'.$e->getMessage().'"}';
        }
        //var_dump($request);
        // json 格式输出
        if (! empty($id)) { // notifications don't want response
            header('content-type: text/javascript');
            echo $request;
        }
        return true;
    }
}
