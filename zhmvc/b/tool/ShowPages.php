<?php
namespace ZHMVC\B\TOOL;
class ShowPages{
    public $output; //页面输出结果 @var string;
    public $file; //使用该类的文件,默认为PHP_SELF @var string;
    public $pvar="pg"; //设置页面传递参数变量,默认为p @var string;
    public $psize;  //页面大小  @var integer;
    public $curr; //当前页面 @var integer;
    public $varstr; //要传递的变量数组 @var array;
    public $tpage; //总页数 @var integer;
    public $trecord; //总记录数 @var integer;

    /**
     * 分页设置
     *
     * @access public
     * @param int $pagesize 页面大小 默认20
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @return void
     */

    function set($pagesize=15,$total=0,$style="sabrosus",$current=NULL) {
        //global $HTTP_SERVER_VARS;
        $this->tpage = ceil($total/$pagesize);   //总页数
        $this->trecord = $total;                 //总记录数
        //$current = NULL;
        if (!$current) {
            if(count($_GET)>0)
            {
                if(isset($_GET[$this->pvar]))
                {
                    $current = $_GET[$this->pvar];
                }
                else
                {
                    $current =1;
                }

            }
            else
            {
                $current =1;
            }

        }
        if ($current>$this->tpage) {
            $current = $this->tpage;
        }
        if ($current<1) {
            $current = 1;
        }
        $this->curr=$current;    //当前页
        $this->psize=$pagesize;  //每页大小

        if (!$this->file) {$this->file = $_SERVER['PHP_SELF'];}  //默认是PHP_SELF

        $this->output.="<div class=\"".$style."\">";

        if ($this->tpage>1){
            if($current==1)
            {
                $this->output.="<span class=\"disabled\">首页</span>";
            }
            else
            {
                $this->output.="<a href=".$this->file."?".$this->pvar."=1".($this->varstr)." title='首页'>首页</a>";
            }
            	
            if ($current>10){
                $this->output.="<a href=".$this->file."?".$this->pvar."=".($current-10).($this->varstr)." title='前十页'><font face=webdings>7</font></a>";
            }

            $BaseNum = floor(($current-1)/10)*10;
            $start    = $BaseNum+1;
            $end    = $BaseNum+10;
            //var_dump($end);
            if ($end>$this->tpage)    {$end=$this->tpage;}

            for ($i=($start);$i<=$end;$i++){
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
                $this->output.="<span class=\"disabled\">末页</span>";
            }
            else
            {
               	$this->output.="<a href=".$this->file."?".$this->pvar."=".($this->tpage).($this->varstr)." title='最后页'>末页</a>";
            }

        }
        else
        {
            $this->output.="<span class=\"disabled\">首页</span><span class=\"current\">1</span><span class=\"disabled\">末页</span>";
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
            $this->varstr.='&amp;'.$k.'='.urlencode($v);
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

    function getStartId()
    {
        return (($this->curr-1)*$this->psize);
    }

    function getEndId()
    {
        return (($this->curr)*$this->psize);
    }

    //得到总页数
    function getPages(){
        return $this->tpage;
    }
    //得到当前页
    function getCpage(){
        return $this->curr;
    }

    //得到总记录数
    function getCrecord(){
        return $this->trecord;
    }
}
