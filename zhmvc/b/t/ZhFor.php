<?php
namespace ZHMVC\B\T;
class ZhFor
{
	private $_content="";//返回值
	private $_zhFrontSeparator = "{";
	private $_zhBackSeparator = "}";
	//private $_zhParameter=array();//主体数组结构由6个变量组成，1主内容。2维度。3start主体。4end主体。5mainkey。6key
	private $_zhParameter="";//表达式
	// 循环前的字符串
	private $_tempSpStart="";
	// 循环后的字符串
	private $_tempSpEnd="";
	// 循环的字符串
	private $_tempSpFor="";
	
	private $_tempValue="";
	
	public function __construct()
	{
		$this->_content="";
	}
	
	public function setZhFrontSeparator($zhFrontSeparator)
	{
		$this->_zhFrontSeparator=$zhFrontSeparator;
	}
	public function setZhBackSeparator($zhBackSeparator)
	{
		$this->_zhBackSeparator=$zhBackSeparator;
	}
	public function setTempValue($tempValue)
	{
		$this->_tempValue=$tempValue;
	}
	public function setZhParameter($zhParameter)
	{
		$this->_zhParameter=$zhParameter;
	}
	public function setTempSpStart($tempSpStart)
	{
		$this->_tempSpStart=$tempSpStart;
	}
	public function setTempSpEnd($tempSpEnd)
	{
		$this->_tempSpEnd=$tempSpEnd;
	}
	public function setTempSpFor($tempSpFor)
	{
		$this->_tempSpFor=$tempSpFor;
	}
	
	public function Parse()
	{
		if(is_array($this->_tempValue)==true)
		{
			//判断数组维数
			//判断是否为空
			if(empty($this->_tempValue)==true)
			{
				$this->_content=$this->_tempSpStart.$this->_tempSpEnd;
			}
			else
			{
				$weidu=$this->arrayLevel($this->_tempValue);
				if($weidu==1)
				{
					//如果是1维数组，则直接替换就ok
					//判断数组是否为空
						foreach ($this->_tempValue as $forkey => $forvalue) {
							$tkey=$this->_zhFrontSeparator.$this->_zhParameter.".".$forkey.$this->_zhBackSeparator;
							$this->_tempSpFor=str_replace($tkey, $forvalue, $this->_tempSpFor);
						}
						$this->_content=$this->_tempSpStart.$this->_tempSpFor.$this->_tempSpEnd;
				}
				elseif($weidu==2)
				{
					$tempS="";
					$tempT="";
					//如果是2维数组，则循环替换
					for($i=0,$tc=count($this->_tempValue,0);$i<$tc;$i++)
					{
						$tempT=$this->_tempSpFor;
						foreach ($this->_tempValue[$i] as $forkey => $forvalue) {
							$tkey=$this->_zhFrontSeparator.$this->_zhParameter.".".$forkey.$this->_zhBackSeparator;
							$tempT=str_replace($tkey, $forvalue, $tempT);
						}
						$tempS=$tempS.$tempT;
					}
					$this->_content=$this->_tempSpStart.$tempS.$this->_tempSpEnd;
				}
				else
				{
					$this->_content=$this->_tempSpStart.$this->_tempSpEnd;
				}
			}
		}
		else
		{
			$this->_content=$this->_tempSpStart.$this->_tempSpEnd;
		}
	}
	
	/**
    * 返回数组的维度
    * @param [type] $arr [description]
    * @return [type]     [description]
    */
    function arrayLevel($arr)
    {
	    $al = array(0);
	    
	    $this->aL($arr,$al);
	    return max($al);
    }
	
	function aL($arr,&$al,$level=0)
	    {
		    if(is_array($arr)){
			    $level++;
			    $al[] = $level;
			    foreach($arr as $v)
			    {
			    	$this->aL($v,$al,$level);
			    }
		    }
	    }
	
	public function getContent()
	{
		return $this->_content;
	}
	
}
