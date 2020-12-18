<?php
namespace ZHMVC\DB\MANAGER;
class ShowPages{
    var $output; //页面输出结果 @var string;
    var $file; //使用该类的文件,默认为PHP_SELF @var string;
    var $pvar="p"; //设置页面传递参数变量,默认为p @var string;
    var $psize;  //页面大小  @var integer;
    var $curr; //当前页面 @var integer;
    var $varstr; //要传递的变量数组 @var array;
    var $tpage; //总页数 @var integer;
    var $trecord; //总记录数 @var integer;

     /**
     * 分页设置
     *
     * @access public
     * @param int $pagesize 页面大小 默认20
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @return void
     */

    function set($pagesize=20,$total=0,$style="sabrosus") {
        //global $HTTP_SERVER_VARS;
        $current=$_GET[$this->pvar];
        $this->tpage = ceil($total/$pagesize);   //总页数
        $this->trecord = $total;                 //总记录数
		
        if (!$current) {$current = $_GET[$this->pvar];}
        if ($current>$this->tpage) {$current = $this->tpage;}
        if ($current<1) {$current = 1;}
		
        $this->curr=$current;    //当前页
        $this->psize=$pagesize;  //每页大小
        
        if (!$this->file) {$this->file = $_SERVER['PHP_SELF'];}  //默认是PHP_SELF
		$this->output.="<div class=\"".$style."\">";
        if ($this->tpage>1){
            if($current==1)
            {
                $this->output.="<span class=\"disabled\"> <  Prev</span><span class=\"current\">1</span>";
            }
            else
            {
            	$this->output.="<a href=".$this->file."?".$this->pvar."=1".($this->varstr)." title='首页'> <  Prev</a><a href=".$this->file."?".$this->pvar."=1".($this->varstr)." title='首页'>1</a>";
            }
			
            if ($current>10){
                $this->output.="<a href=".$this->file."?".$this->pvar."=".($current-10).($this->varstr)." title='前十页'><font face=webdings>7</font></a>";
            }

            $BaseNum = floor(($current-1)/10)*10;
            $start    = $BaseNum+1;
            $end    = $BaseNum+10;
            if ($end>$this->tpage)    {$end=$this->tpage;}

            for ($i=($start+1);$i<$end;$i++){
				if ($current==$i){
                    $this->output.="<span class=\"current\">".$i."</span>";
	            }
	            else
	            {
	               	$this->output.="<a href=".$this->file."?".$this->pvar."=".$i.$this->varstr." title='第".$i."页'>".$i."</a>";
	            }
            }
            if ($BaseNum+10<$this->tpage) {
               	 $this->output.="<a href=".$this->file."?".$this->pvar."=".($current+10).($this->varstr)." title='下十页'><font face=webdings>8</font></a>";
            }
            if ($current==$this->tpage){
                $this->output.="<span class=\"current\">".$this->tpage."</span><span class=\"disabled\">Next  > </span>";
            }
            else
            {
               	$this->output.="<a href=".$this->file."?".$this->pvar."=".($this->tpage).($this->varstr)." title='最后页'>".$this->tpage."</a><a href=".$this->file."?".$this->pvar."=".($this->tpage).($this->varstr)." title='最后页'>Next  > </a>";
            }

        }
		else
		{
			$this->output.="<span class=\"disabled\"> <  Prev</span><span class=\"current\">1</span><span class=\"disabled\">Next  > </span>";
		}
		$this->output.="</div>";
    }

    /**
     * 要传递的变量设置
     *
     * @access public
     * @param array $data   要传递的变量，用数组来表示，参见上面的例子
     * @return void
     */
    function setvar($data) {
        foreach ($data as $k=>$v) {
            $this->varstr='&amp;'.$k.'='.urlencode($v);
        }
    }

    /**
     * 分页结果输出
     *
     * @access public
     * @param bool $return 为真时返回一个字符串，否则直接输出，默认直接输出
     * @return string  1:返回一个字符串,0:直接输出.
     */
    function output($ReturnStr = false) {
        if ($ReturnStr) 
        {
            return $this->output;
        } 
        else 
        {
            echo $this->output;
        }
    }

    /**
     * 生成Limit语句
     *
     * @access public
     * @return string
     */
    function limit() {
        return (($this->curr-1)*$this->psize).','.$this->psize;
    }
}
?>