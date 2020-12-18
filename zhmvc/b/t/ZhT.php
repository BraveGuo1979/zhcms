<?php
namespace ZHMVC\B\T;
use Exception;
class ZhT {
	private $_zhFile = "";
	private $_zhFrontSeparator = "{";
	private $_zhBackSeparator = "}";
	private $_zhiCharset;
	private $_zhInVar=array();
	private $_zhInFor=array();
	private $_zhInIf=array();
	private $_zhContent = "";
	private $_zhParameterArray;
	
	public function __construct() {
	
	}
		
	private function getContent()
	{
		if(isset($this->_zhContent))
		{
			return($this->_zhContent);
		}else
		{
			return(NULL);
		}
	}
	
	private function setContent($content)
	{
		$this->_zhContent = $content;
	}
	
	private function getZhiCharset()
	{
		if(isset($this->_zhiCharset))
		{
			return($this->_zhiCharset);
		}
		else
		{
			return(NULL);
		}
	}
	public function setZhiCharset($zhiCharset)
	{
		$this->_zhiCharset = $zhiCharset;
	}
	
	public function setFrontSeparator($zhTemp) {
	    $this->_zhFrontSeparator = $zhTemp;
	}
	
	private function getFrontSeparator() {
		return $this->_zhFrontSeparator;
	}

	public function setBackSeparator($zhTemp) {
	    $this->_zhBackSeparator = $zhTemp;
	}
	
	private function getBackSeparator() {
		return $this->_zhBackSeparator;
	}

	public function setFile($zhFile) {
		$this->_zhFile = $zhFile;
	}

	private function getFile() {
		return $this->_zhFile;
	}
	
	public function setInVtrParameter($ParameterName, $ParameterVale) {
		//判断键是否存在
		if (array_key_exists($ParameterName,$this->_zhInVar))
		{
			//已经存在
			$this->_zhInVar[$ParameterName]=$ParameterVale;
		}
		else
		{
			//不存在
			$tempA=array($ParameterName=>$ParameterVale);
			$tempB=$this->_zhInVar;
			$this->_zhInVar=array_merge($tempA,$tempB);
		}
	}
	
	public function setInIfParameter($ParameterName, $ParameterVale) {
		//判断键是否存在
		if (array_key_exists($ParameterName,$this->_zhInIf))
		{
			//已经存在
			$this->_zhInIf[$ParameterName]=$ParameterVale;
		}
		else
		{
			//不存在
			$tempA=array($ParameterName=>$ParameterVale);
			$tempB=$this->_zhInIf;
			$this->_zhInIf=array_merge($tempA,$tempB);
		}
	}
	
	public function setInForParameter($ParameterName, $ParameterVale) {
		if (array_key_exists($ParameterName,$this->_zhInFor))
		{
			//已经存在
			$this->_zhInFor[$ParameterName]=$ParameterVale;
		}
		else
		{
			//不存在
			$tempA=array($ParameterName=>$ParameterVale);
			$tempB=$this->_zhInFor;
			$this->_zhInFor=array_merge($tempA,$tempB);
		}
	}
	

	private function zhReadFile($fileName)
	{
	    $content=file_get_contents($fileName);
		
		if(is_null($this->getZhiCharset())==TRUE)
		{
			$content = iconv($this->getzhiCharset(),"UTF-8//IGNORE",$content); 
		}
		return $content;
	}
	
	public function parse() {
		try {
			$this->setContent($this->ZhReadFile($this->_zhFile));
			//处理标签
			$zhSplitA=explode($this->_zhFrontSeparator, $this->_zhContent);
			
			if(count($zhSplitA)<=1)
			{
				//没有需要处理的变量
			}
			else
			{
				$this->repInclude();
				
				// 处理判断
				$this->repIf();
				
				// 处理list循环
				//$this->RepList();
				
				// 处理循环
				$this->repFor();
				
				// 处理模块
				$this->repModule();
				
				//处理插件
				$this->repPlus();
				
				// 处理替换的标签
				$this->repString();
			}
		} catch (Exception $e) {
			print $e->getMessage();
			exit;
		}
	}
	
	private function repInclude(){
		$tempS=$this->_zhContent;
		
		$zhSplitA=explode($this->_zhFrontSeparator."include:", $this->_zhContent);
		
		for($i=1,$tempCount=count($zhSplitA); $i<$tempCount; $i++) {
		    $zhSplitB=explode($this->_zhBackSeparator, $zhSplitA[$i]);
		    $tempPath=$zhSplitB[0];
		   
		    $tempContent=$this->zhReadFile(SYSTEM_PATH.$tempPath);
		    $tempS=str_replace($this->_zhFrontSeparator."include:".$tempPath.$this->_zhBackSeparator,$tempContent,$tempS);
		}

		$this->setContent($tempS);
	}
	
	private function repString() {
		$tempS=$this->_zhContent;
		$tempParameterArray=$this->_zhInVar;
		$zhParameterArray=array();
		$zhSplitA=explode($this->_zhFrontSeparator, $this->_zhContent);
		
		for($i=1,$tempCount=count($zhSplitA); $i<$tempCount; $i++) {
		    $zhSplitB=explode($this->_zhBackSeparator, $zhSplitA[$i]);
		    $zhParameterArray[($i-1)]=$zhSplitB[0];
		}
		
		$tempCountA=count($zhParameterArray);
		//$deletekey=array();
		if($tempCountA>0)
		{
		    //print_r($zhParameterArray);
		    for($i=0;$i<$tempCountA;$i++)
		    {
		        //var_dump($i);
		        //var_dump($zhParameterArray[$i]);
		        //对变量循环，看看有没有符合的变量，符合的进行替换
		        if(substr_count($zhParameterArray[$i],":",0)==0)
		        {
		            foreach ($tempParameterArray as $key => $value) {
		                if($key==$zhParameterArray[$i])
		                {
		                    $zhs=new \ZHMVC\B\T\ZhRepString();
		                    //$tempS=$zhs->getRepString($this->_zhFrontSeparator.$key.$this->_zhBackSeparator,$value,$tempS);
		                    $tempS=str_replace($this->_zhFrontSeparator.$key.$this->_zhBackSeparator,$value,$tempS);
		                    //$deletekey[]=$key;
		                    //$tempParameterArray=$this->zhDeleteArrayKey($tempParameterArray,$key);
		                }
		            }
		        }
		        	
		    }
		}
		
		$this->setContent($tempS);
	}
	
	private function repIf(){
		$tempS=$this->_zhContent;
		
		$zhSplitA=explode($this->_zhFrontSeparator."endif:", $tempS);
		
		if(count($zhSplitA)<=1)
		{
		  //没有需要处理的变量
		}
		else
		{
		    for($i=1,$tempCount=count($zhSplitA); $i<$tempCount; $i++) {
			    //echo $tempS;
			    //var_dump($i);
				$zhSplitF=explode($this->_zhFrontSeparator."endif:", $tempS);
				$mainkey="";
				$zhSplitB=explode($this->_zhBackSeparator, $zhSplitF[1]);
				$mainkey=$zhSplitB[0];
				$tempzhSplit=$zhSplitF[0];
				$zhSplitD=explode($this->_zhFrontSeparator."if:", $zhSplitF[0]);
				$zhSplitE=explode($this->_zhBackSeparator,$zhSplitD[(count($zhSplitD)-1)]);
				$key=$zhSplitE[0];
				
				$tempSpStart="";
				$tempSpEnd="";
				$tempSpIf="";
				$tempMain_2=explode($this->_zhFrontSeparator."endif:".$mainkey.$this->_zhBackSeparator, $tempS);
				for($k=1;$k<count($tempMain_2);$k++)
				{
					$tempSpEnd=$tempSpEnd.$tempMain_2[$k].$this->_zhFrontSeparator."endif:".$mainkey.$this->_zhBackSeparator;
				}
				$tempSpEnd=substr($tempSpEnd,0,strlen($tempSpEnd)-strlen($this->_zhFrontSeparator."endif:".$mainkey.$this->_zhBackSeparator));
							
				//获取第一个
				$tempMain_3=explode($this->_zhFrontSeparator."if:".$key.$this->_zhBackSeparator, $tempMain_2[0]);
				
				$tempSpIf=$tempMain_3[(count($tempMain_3)-1)];
				for($k=0;$k<(count($tempMain_3)-1);$k++)
				{
					$tempSpStart=$tempSpStart.$tempMain_3[$k].$this->_zhFrontSeparator."if:".$key.$this->_zhBackSeparator;
				}
				$tempSpStart=substr($tempSpStart,0,strlen($tempSpStart)-strlen($this->_zhFrontSeparator."if:".$key.$this->_zhBackSeparator));

				foreach ($this->_zhInIf as $ifkey => $ifValue) {
					if($mainkey==$ifkey)
					{
						//调用if类进行处理
						$izhif=new \ZHMVC\B\T\ZhIf();
						$izhif->setTempValue($ifValue);
						$izhif->setZhParameter($key);
						$izhif->setTempSpStart($tempSpStart);
						$izhif->setTempSpEnd($tempSpEnd);
						$izhif->setTempSpIf($tempSpIf);
						$izhif->setZhFrontSeparator($this->_zhFrontSeparator);
						$izhif->setZhBackSeparator($this->_zhBackSeparator);
						$izhif->Parse();
						$tempS=$izhif->getContent();
					}
				}
			}
		}
		
		$this->setContent($tempS);
	}

	private function RepFor(){
		
		$tempS=$this->_zhContent;
		
		$zhSplitA=explode($this->_zhFrontSeparator."endfor:", $tempS);
		if(count($zhSplitA)<=1)
		{
				//没有需要处理的变量
		}
		else
		{
		    
			for($i=1,$tempCount=count($zhSplitA); $i<$tempCount; $i++) {
				$zhSplitF=explode($this->_zhFrontSeparator."endfor:", $tempS);
				$mainKey="";
				$zhSplitB=explode($this->_zhBackSeparator, $zhSplitF[1]);
				$mainKey=$zhSplitB[0];//主要的部分
				//var_dump($mainkey);
				//$tempZhSplit=$zhSplitF[0];
				$zhSplitD=explode($this->_zhFrontSeparator."for:", $zhSplitF[0]);
				$zhSplitE=explode($this->_zhBackSeparator,$zhSplitD[(count($zhSplitD)-1)]);
				$key=$zhSplitE[0];
				//进行处理$mainkey
				
				$tempSpStart="";
				$tempSpEnd="";
				$tempSpFor="";
				
				$tempMain_2=explode($this->_zhFrontSeparator."endfor:".$mainKey.$this->_zhBackSeparator, $tempS);	
				for($k=1;$k<count($tempMain_2);$k++)
				{
				    $tempSpEnd=$tempSpEnd.$tempMain_2[$k].$this->_zhFrontSeparator."endfor:".$mainKey.$this->_zhBackSeparator;
				}
				$tempSpEnd=substr($tempSpEnd,0,strlen($tempSpEnd)-strlen($this->_zhFrontSeparator."endfor:".$mainKey.$this->_zhBackSeparator));
							
				//获取第一个
				$tempMain_3=explode($this->_zhFrontSeparator."for:".$key.$this->_zhBackSeparator, $tempMain_2[0]);
				$tempSpFor=$tempMain_3[(count($tempMain_3)-1)];
				for($k=0;$k<(count($tempMain_3)-1);$k++)
				{
					$tempSpStart=$tempSpStart.$tempMain_3[$k].$this->_zhFrontSeparator."for:".$key.$this->_zhBackSeparator;
				}
				$tempSpStart=substr($tempSpStart,0,strlen($tempSpStart)-strlen($this->_zhFrontSeparator."for:".$key.$this->_zhBackSeparator));
				foreach ($this->_zhInFor as $forKey => $forValue) {
				    if($mainKey==$forKey)
					{
					    //处理值
						$izhfor=new \ZHMVC\B\T\ZhFor();
						$izhfor->setTempValue($forValue);
						$izhfor->setZhParameter($key);
						$izhfor->setTempSpStart($tempSpStart);
						$izhfor->setTempSpEnd($tempSpEnd);
						$izhfor->setTempSpFor($tempSpFor);
						$izhfor->setZhFrontSeparator($this->_zhFrontSeparator);
						$izhfor->setZhBackSeparator($this->_zhBackSeparator);
						$izhfor->Parse();
						$tempS=$izhfor->getContent();
					}
				}
			}
		}
		$this->setContent($tempS);
	}
	
	private function RepModule(){
		$tempS=$this->_zhContent;
		
		$zhSplitA=explode($this->_zhFrontSeparator."module:", $tempS);
		
		if(count($zhSplitA)<=1)
		{
			//没有需要处理的变量
		}
		else
		{
			for($i=1,$tempCount=count($zhSplitA); $i<$tempCount; $i++) {
				$zhSplitB=explode($this->_zhBackSeparator, $zhSplitA[$i]);
				$key=$zhSplitB[0];
				$Module=new \ZHMVC\B\T\ZhModule();
				$Module->setZhParameter($key);
				$Module->Parse();
				$tempModule=$Module->getContent();
				$tempS=str_replace($this->_zhFrontSeparator."module:".$key.$this->_zhBackSeparator,$tempModule,$tempS);
			}
		}
				
		$this->setContent($tempS);
	}
	
	private function RepPlus(){
	    $tempS=$this->_zhContent;
	
	    $zhSplitA=explode($this->_zhFrontSeparator."plus:", $tempS);
	
	    if(count($zhSplitA)<=1)
	    {
	        //没有需要处理的变量
	    }
	    else
	    {
	        for($i=1,$tempCount=count($zhSplitA); $i<$tempCount; $i++) {
	            $zhSplitB=explode($this->_zhBackSeparator, $zhSplitA[$i]);
	            $key=$zhSplitB[0];
	            $Plus=new \ZHMVC\B\T\ZhPlus();
	            $Plus->setZhParameter($key);
	            $Plus->Parse();
	            $tempPlus=$Plus->getContent();
	            $tempS=str_replace($this->_zhFrontSeparator."plus:".$key.$this->_zhBackSeparator,$tempPlus,$tempS);
	        }
	    }
	
	    $this->setContent($tempS);
	}
	
	public function Out(){
	    
		return $this->_zhContent;
	}
}
