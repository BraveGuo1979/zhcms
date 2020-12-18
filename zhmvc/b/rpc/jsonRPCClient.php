<?php
namespace ZHMVC\B\RPC;
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
class jsonRPCClient
{
    private $debug;
    private $url;
    // 请求id
    private $id;
    private $notification = false;
    private $_mainkey;
    private $_host;
    private $_moduleid;
    private $_isjm;
    private $_addtime;
    private $_privatekey;
    /**
     *
     * @param $url
     * @param bool $debug
     */
    public function __construct($url, $mainkey, $host, $moduleid, $privatekey, $id=1, $isjm=true, $proxy=80, $debug = false)
    {
        //查询key是否合法
        // server URL
        $this->url = $url;
        $this->_mainkey=$mainkey;
        $this->_host=$host;
        $this->_moduleid=$moduleid;
        $this->_isjm=$isjm;
        //$this->_addtime=strtotime('now');
        $this->_addtime='app1';
        $this->_privatekey=$privatekey;
        // proxy
        empty($proxy) ? $this->proxy = '' : $this->proxy = $proxy;
        // debug state
        empty($debug) ? $this->debug = false : $this->debug = true;
        // message id
        $this->id = $id;
    }
    
    /**
     *
     * @param boolean $notification
     */
    public function setRPCNotification($notification)
    {
        empty($notification) ? $this->notification = false : $this->notification = true;
    }
    
    /**
     *
     * @param $method
     * @param $params
     * @return bool
     * @throws Exception
     */
    public function __call($method, $params)
    {
        // 检验request信息
        if (! is_scalar($method)) {
            throw new \Exception('Method name has no scalar value');
        }
        
        //$params = array_values($params);
        //$params_s=json_encode($params[0]);
        $params_s=$params[0];
        if (!is_array($params)) {
            throw new \Exception('Params must be given as array');
        }
        
        if ($this->notification) {
            $currentId = NULL;
        } else {
            $currentId = $this->id;
        }
        
        $token=md5($this->_host.$this->_moduleid.$this->_addtime.$this->_privatekey);
        
        // 拼装成一个request请求
        /* $request = array(
         'jsonrpc' => '2.0',
         'method' => $method,
         'params' => $params,
         'id' => $currentId
         );
         $request = json_encode($request);
         $this->_privatekey
         token=CryptoJS.MD5(opt.host+opt.mid+opt.addtime+PRIVATEKEY).toString();
         */
        //var_dump($params);
        $request='';
        if($this->_isjm=="true" || $this->_isjm=="1" || $this->_isjm==true || $this->_isjm==1)
        {
            //对参数进行加工
            //1.压缩
            if (empty($params_s))
            {
                $params='';
            }
            else 
            {
                $params=json_encode($params_s);
                $params = gzcompress($params);
                $params = base64_encode($params);
                //2.加密
                $params = \ZHMVC\B\TOOL\OpensslEncryptHelper::encryptWithOpenssl($params); 
            }
        }
        else{
            $params=$params_s;
        }
  
        if($params!="")
        {
            if($this->_isjm=="true" || $this->_isjm=="1" || $this->_isjm==true || $this->_isjm==1)
            {
                $request='{"jsonrpc":"2.0","method":"'.$method.'","params":{"token":"'.$token.'","mainkey":"'.$this->_mainkey.'","host":"'.$this->_host.'","moduleid":"'.$this->_moduleid.'","addtime":"'.$this->_addtime .'","isjm":"'. $this->_isjm .'","parameter":"' .$params. '"},"id":"'.$this->id.'"}';
            }
            else 
            {
                $request='{"jsonrpc":"2.0","method":"'.$method.'","params":{"token":"'.$token.'","mainkey":"'.$this->_mainkey.'","host":"'.$this->_host.'","moduleid":"'.$this->_moduleid.'","addtime":"'.$this->_addtime .'","isjm":"'. $this->_isjm .'","parameter":' .$params. '},"id":"'.$this->id.'"}';
            }
        }
        else
        {
            $request='{"jsonrpc":"2.0","method":"'.$method.'","params":{"token":"'.$token.'","mainkey":"'.$this->_mainkey.'","host":"'.$this->_host.'","moduleid":"'.$this->_moduleid.'","addtime":"'. $this->_addtime .'","isjm":"'. $this->_isjm .'","parameter":""},"id":"'.$this->id.'"}';
        }
        
        $this->debug && $this->debug .= '***** Request *****' . "\n" . $request . "\n" . '***** End Of request *****' . "\n\n";
        
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => $request
            )
        );
        // 关键几部
        $context = stream_context_create($opts);
        $result = file_get_contents($this->url, false, $context);
        
        if($this->debug)
        {
            echo "--------------------------------------------\n<br>";
            print_r($result);
            echo "\n<br>--------------------------------------------\n<br>";
        }
        //var_dump($result);
        //echo($result);
        if ($result) {
            $response = json_decode($result, true);
        } else {
            throw new \Exception('Unable to connect to ' . $this->url);
        }
        
        // 输出调试信息
        if ($this->debug) {
            echo nl2br(($this->debug));
        }
        // 检验response信息
        if (! $this->notification) {
            // check
            //var_dump($response);
            //var_dump($currentId);
            if ($response['id'] != $currentId) {
                throw new \Exception('Incorrect response id (request id: ' . $currentId . ', response id: ' . $response['id'] . ')');
            }
            if ($response['error']!='') {
                throw new \Exception('Request error: ' . $response['error']);
            }
            //echo $response['result']['parameter'];
            //解密，解压
            if($response['result']['isjm']=="true" || $response['result']['isjm']=="1" || $response['result']['isjm']==true || $response['result']['isjm']==1)
            {
                $re = \ZHMVC\B\TOOL\OpensslEncryptHelper::decryptWithOpenssl($response['result']['parameter']);
                $newhtmlText = base64_decode($re);
                $re = gzuncompress($newhtmlText);
                $re = json_decode($re, true);
            }
            else 
            {
                $re = $response['result']['parameter'];
            }
            return $re;
        } else {
            return true;
        }
    }
}
