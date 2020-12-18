<?php
if (! isset($_SESSION)) {
    session_start();
}
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
// 生成菜单
$str_leftmenu = array();
$str_headmenu = array();
// $sql = "select id,title,url,menuname from " . $db_pre . "admin_menu where pid='0'";
$map = array();
$map['pid'] = '0';
$rs = D("zhmvc_admin_menu")->field("id,title,url,menuname")->where($map)->order(' id asc')->getLinkAll("", true);
$map = null;
$datas = $rs['datas'];
$rows = $rs['rows'];
for ($i = 0; $i < $rows; $i ++) {
    $data = $datas[$i];
    $str_leftmenu[$i][0]['id'] = $data['id'];
    $str_leftmenu[$i][0]['title'] = $data['title'];
    $str_leftmenu[$i][0]['url'] = $data['url'];
    $str_leftmenu[$i][0]['menuname'] = str_replace('_', '', $data['menuname']);
    $str_headmenu[$i]['id'] = $data['id'];
    $str_headmenu[$i]['title'] = $data['title'];
    $str_headmenu[$i]['url'] = $data['url'];
    $str_headmenu[$i]['menuname'] = str_replace('_', '', $data['menuname']);
    // $sql1 = "select id,title,url,menuname from " . $db_pre . "admin_menu where pid=:pid";
    $map = array();
    $map['pid'] = $data['id'];
    $rs1 = D("zhmvc_admin_menu")->field("id,title,url,menuname")
    ->where($map)->order(' id asc')
    ->getLinkAll("", true);
    $map = null;
    $datas1 = $rs1['datas'];
    $rows1 = $rs1['rows'];
    // echo $sql;
    if ($rows1 > 0) {
        for ($j = 0; $j < $rows1; $j ++) {
            $data1 = $datas1[$j];
            $str_leftmenu[$i][($j + 1)]['id'] = $data1['id'];
            $str_leftmenu[$i][($j + 1)]['title'] = $data1['title'];
            $str_leftmenu[$i][($j + 1)]['url'] = $data1['url'];
            $str_leftmenu[$i][($j + 1)]['menuname'] = str_replace('_', '', $data1['menuname']);
            // $str_leftmenu[$i][($j + 1)] = "<a href=\"" . $data1['url'] . "\" target=\"main\">" . $data1['title'] . "</a>";
        }
    }
}
?>
<!--sidebar-menu-->
<div id="sidebar">
<a href="#" class="visible-phone"><i class="icon icon-home"></i> 系统</a>
  <ul>
  
    <li <?php if($_curlid==0) echo 'class="active"';?>><a href="/manager/showphpinfo.php"><i class="icon icon-home"></i> <span>系统信息</span></a> </li>
<?php 
$count_leftheadmenu = count($str_headmenu);
for($i=0;$i<$count_leftheadmenu;$i++)
{
    //一级栏目管理
    
?>
    <li class="submenu <?php if($_curlid==$str_headmenu[$i]['id']) echo 'active';?>"> <a href="#"><i class="icon icon-th-list"></i> <span><?php echo $str_headmenu[$i]['title']?></span></a>
      <ul>
      <?php
			$temp_left=$str_leftmenu[$i];
			$count_t_l=count($temp_left);
			for($j=1;$j<$count_t_l;$j++)
			{
		?>
        <li><a href="<?php echo $temp_left[$j]['url'];?>"><?php echo $temp_left[$j]['title'];?></a></li>
        <?php  
			}
		?>
      </ul>
    </li>
<?php 
}
?>
  </ul>
</div>
<!--sidebar-menu-->