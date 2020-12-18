<?php
class baidusitemap
{
    public $site;
    public $urls;
    public $token;
   
    function __construct($site,$token)
    {
        $this->setSite($site);
        $this->setToken($token);
    }
    
    public function send(){
        $api = 'http://data.zz.baidu.com/urls?site='.$this->getSite().'&token='.$this->getToken().'';
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $this->getUrls()),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }

    public function getSite()
    {
        return $this->site;
    }
    
    public function getUrls()
    {
        return $this->urls;
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function setSite($site)
    {
        $this->site = $site;
    }
    
    public function setUrls($urls)
    {
        $this->urls[] = $urls;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
    }

    function __destruct()
    {}
}
