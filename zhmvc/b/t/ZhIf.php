<?php
namespace ZHMVC\B\T;
class ZhIf
{
	private $_content="";//返回值
	private $_zhParameter="";//表达式
	// 循环前的字符串
	private $_tempSpStart="";
	// 循环后的字符串
	private $_tempSpEnd="";
	// 循环的字符串
	private $_tempSpIf="";
	private $_tempValue="";
	
	private $_zhFrontSeparator = "{";
	
	private $_zhBackSeparator = "}";
	
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
	public function setTempSpIf($tempSpIf)
	{
		$this->_tempSpIf=$tempSpIf;
	}
	
	public function Parse()
	{
		$c_a=$this->_tempSpStart.$this->_tempSpIf.$this->_tempSpEnd;
		$c_b=$this->_tempSpStart.$this->_tempSpEnd;
		
		//对表达式进行分析 判断是否含有空格
		if(substr_count($this->_zhParameter," ")==0)
		{
		    //echo "11111";
			//如果不含有表达式，判断当前值的真假
			if(isset($this->_tempValue))
			{
				if($this->_tempValue==true)
				{
					$this->_content=$c_a;
				}
				else
				{
					$this->_content=$c_b;
				}
			}
			else
			{
				$this->_content=$c_b;
			}
		}
		else
		{
		    
			$mainkey=explode(" ", $this->_zhParameter);
	        //print_r(count($mainkey));
			if(count($mainkey)==2)
			{
				if($this->_tempValue==$mainkey[1])
				{
					$this->_content=$c_a;
				}
				else
				{
					$this->_content=$c_b;
				}
			}
			elseif(count($mainkey)==3)
			{
			    $key1=$this->_tempValue;
				$key2=$mainkey[1];//表达式
				$key3=$mainkey[2];
				//echo "<br>------------------<br>";
				//print_r($key2);
				//echo "<br>------------------<br>";
				switch ($key2) 
				{
					case "eq":
						if($key1==$key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case "=":
						if($key1==$key3)
						{
							//echo "<br>--------true----------<br>";
							$this->_content=$c_a;
						}
						else
						{
							//echo "<br>--------false----------<br>";
							$this->_content=$c_b;
						}
						break;
					case ">":
						if($key1 > $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case ">=":
						if($key1 >= $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case "<":
						if($key1 < $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case "<=":
						if($key1 <= $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					default:
						
				}
			}
			else
			{
				$key1=$this->_tempValue;
				$key2=$mainkey[1];//表达式
				$key3=$mainkey[2];
				//echo "<br>------------------<br>";
				//print_r($key2);
				//echo "<br>------------------<br>";
				switch ($key2) 
				{
					case "eq":
						if($key1==$key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case "=":
						if($key1==$key3)
						{
							//echo "<br>--------true----------<br>";
							$this->_content=$c_a;
						}
						else
						{
							//echo "<br>--------false----------<br>";
							$this->_content=$c_b;
						}
						break;
					case ">":
						if($key1 > $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case ">=":
						if($key1 >= $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case "<":
						if($key1 < $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					case "<=":
						if($key1 <= $key3)
						{
							$this->_content=$c_a;
						}
						else
						{
							$this->_content=$c_b;
						}
						break;
					default:
						
				}
			}
		}
	}
	
	public function getContent()
	{
		return $this->_content;
	}
}
