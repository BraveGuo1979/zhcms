<?php
namespace ZHMVC\DB\MANAGER;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(__FILE__)) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
$isp = new \ZHMVC\D\MANAGER\isPermission();
$isper = $isp->getPermission();
$_curlid = $isp->getCUrl();
$c = new \ZHCONFIG\ZhConfig();
$db_pre = $c->getDbPre();
if($isper==1)
{
	$ErrMsg="对不起，你没有访问该页面的权限";
	echo $ErrMsg;
	exit;
}
elseif($isper==0)
{
	$ErrMsg="对不起，地址错误";
	echo $ErrMsg;
	exit;
}


$admin_global_class = array(
    "网站前台设置",
    "SEO优化设置",
    "系统参数配置",
    "会员登录和注册",
    "会员消费设置",
    "图片上传设置",
    "信息审核功能设置",
    "分类信息设置"
);

$admin_global = array(
    "SiteName" => array(
        "des" => '网站名称',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteUrl" => array(
        "des" => '使用域名,范例：http://www.yourdomain.com<br /><i style=color:red>若为二级目录安装，则需填写二级目录,范例:http://www.yourdamain.com/upload</i>',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteQQ" => array(
        "des" => '客服QQ，请只填写一个',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteEmail" => array(
        "des" => '客服邮箱',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteTel" => array(
        "des" => '客服热线',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteBeian" => array(
        "des" => '网站备案号',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteLogo" => array(
        "des" => '网站Logo路径',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteCity" => array(
        "des" => '您的网站面向的城市<br /><i style=color:red>比如：郑州 （提示，填写此项有利于搜索引擎优化）</i>',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteStat" => array(
        "des" => '第三方网站统计代码<br />如有单引号，建议你手动将其改为<b>双引号</b>，否则保存后可能造成系统显示空白，以至无法运行，如出现这种情况，请在ftp中打开/dat/config.php将 SiteStat 对应代码删除即可',
        "type" => '字符型',
        "class" => '网站前台设置'
    ),
    
    "SiteSeoName" => array(
        "des" => '网站标题，用于搜索引擎优化，请适当出现关键词',
        "type" => '字符型',
        "class" => 'SEO优化设置'
    ),
    
    "SiteKeywords" => array(
        "des" => '网站关键词，多个关键词以“,”分隔开',
        "type" => '字符型',
        "class" => 'SEO优化设置'
    ),
    
    "SiteDescription" => array(
        "des" => '网站描述，不超过255个字符',
        "type" => '字符型',
        "class" => 'SEO优化设置'
    ),
    
    "cfg_if_rewrite" => array(
        "des" => '是否启用URL伪静态规则，<br /><i style=color:red>注意：该功能需要空间支持，详细请查看程序包中url_rewrite.txt文件</i>',
        "type" => '布尔型',
        "class" => 'SEO优化设置'
    ),
    
    "cfg_tpl_dir" => array(
        "des" => 'zhmvc模板目录，<i style=color:red>注意：相对template/目录，默认为default</i>',
        "type" => '字符型',
        "class" => '系统参数配置'
    ),
    
    "cfg_if_site_open" => array(
        "des" => '开启关闭网站，<i style=color:red>请注意：非特殊情况，请勿关闭网站，对搜索引擎排名影响甚大！</i>',
        "type" => '布尔型',
        "class" => '系统参数配置'
    ),
    
    "cfg_site_open_reason" => array(
        "des" => '若关闭网站，前台页面显示提示（关闭原因）',
        "type" => '字符型',
        "class" => '系统参数配置'
    ),
    
    "cfg_page_line" => array(
        "des" => '分页每页显示记录数',
        "type" => '字符型',
        "class" => '系统参数配置'
    ),
    
    "cfg_backup_dir" => array(
        "des" => '数据库备份文件夹,<font color=red>注意：相对<b>/dat</b>目录</font><br />为方便管理，所有数据库备份文件都默认保存在<b>/dat</b>目录下<br />具体保存目录，你可以在此设置，不须填写<b>/dat</b><br />保存成功后，系统会自动创建该目录',
        "type" => '字符型',
        "class" => '系统参数配置'
    ),
    
    "cfg_join_othersys" => array(
        "des" => '整合其他CMS系统<br /><i style=color:red><img src=../images/warn.gif align=absmiddle> 注意：不整合其他系统请留空! <br />【当前只支持ucenter，输入框内填写ucenter】</i>',
        "type" => '字符型',
        "class" => '系统参数配置'
    ),
    
    "cfg_editor" => array(
        "des" => '系统编辑器',
        "type" => '布尔型',
        "class" => '系统参数配置'
    ),
    
    "cfg_if_member_register" => array(
        "des" => '是否开启新会员注册',
        "type" => '布尔型',
        "class" => '会员登录和注册'
    ),
    
    "cfg_if_member_log_in" => array(
        "des" => '是否开启会员登录',
        "type" => '布尔型',
        "class" => '会员登录和注册'
    ),
    
    "cfg_member_perpost_consume" => array(
        "des" => '注册会员每发布一条分类信息扣除金币<br /><i style=color:red>注意：该功能只对会员有效<br />若不扣除金币请留空</i>',
        "type" => '字符型',
        "class" => '会员消费设置'
    ),
    
    "cfg_member_upgrade_index_top" => array(
        "des" => '会员分类信息首页置顶扣除金币',
        "type" => '字符型',
        "class" => '会员消费设置'
    ),
    
    "cfg_member_upgrade_list_top" => array(
        "des" => '会员分类信息列表页置顶扣除金币',
        "type" => '字符型',
        "class" => '会员消费设置'
    ),
    
    "cfg_upimg_type" => array(
        "des" => '允许上传的图片格式',
        "type" => '字符型',
        "class" => '图片上传设置'
    ),
    
    "cfg_upimg_size" => array(
        "des" => '允许上传的图片大小，单位为KB',
        "type" => '字符型',
        "class" => '图片上传设置'
    ),
    
    "cfg_upimg_watermark" => array(
        "des" => '上传图片是否开启水印，<i style=color:red>注意：该功能需要您的服务器支持GD库</i>',
        "type" => '布尔型',
        "class" => '图片上传设置'
    ),
    
    "cfg_upimg_watermark_value" => array(
        "des" => '水印显示的内容<br /><i style=color:red>注意：仅支持英文，不支持中文，一般填写网站的域名</i>',
        "type" => '字符型',
        "class" => '图片上传设置'
    ),
    
    "cfg_upimg_watermark_position" => array(
        "des" => '上传图片水印显示的位置。<br /><i style=color:red>注意：1为左下角,2为右下角,3为左上角,4为右上角,5为居中</i>',
        "type" => '字符型',
        "class" => '图片上传设置'
    ),
    
    "cfg_upimg_watermark_color" => array(
        "des" => '水印文字的颜色，可选：白色，红色，黑色<br /><i style=color:red>白色为1，红色为2，黑色为3</i>',
        "type" => '字符型',
        "class" => '图片上传设置'
    ),
    
    "cfg_arealoop_style" => array(
        "des" => '信息列表页地区筛选是否启用下拉框显示<br /><i style=color:red>（默认为平板显示）</font><br /><font color=red>建议：</font>当你填写的地区超过30个以上时，建议开启下拉框显示，以免造成地区过多，页面显示杂乱</i>',
        "type" => '布尔型',
        "class" => '分类信息设置'
    ),
    
    "cfg_info_if_img" => array(
        "des" => '信息联系方式是否以图片形式显示<br /><i style=color:red>（默认为图片显示）</font>',
        "type" => '布尔型',
        "class" => '分类信息设置'
    ),
    
    "cfg_allow_post_area" => array(
        "des" => '是否限制信息发布者所在的省市，若无限制则留空<br /><font color=red>（即您指定地区以外的省市将不能发布信息）</font><br /><font color=red>范例：</font>河南省（建议填写省份，填写城市有时不准确）<br /><i style=color:red>注意：只能填写一个省市，建议填写您所在的省份</i>',
        "type" => '字符型',
        "class" => '分类信息设置'
    ),
    
    "cfg_forbidden_post_ip" => array(
        "des" => '禁止信息发布的IP，请您将禁止发布信息的IP填入框内，多个IP用“,”分开，不禁止IP请留空<br /><font color=red>范例：</font>202.102.205.222,102.152.125.25',
        "type" => '字符型',
        "class" => '分类信息设置'
    ),
    
    "cfg_upimg_number" => array(
        "des" => '发布信息至多能上传多少张图片，默认是4张',
        "type" => '数字',
        "class" => '分类信息设置'
    ),
    
    "cfg_if_nonmember_info" => array(
        "des" => '非注册会员能否发布分类信息',
        "type" => '布尔型',
        "class" => '分类信息设置'
    ),
    
    "cfg_if_nonmember_info_box" => array(
        "des" => '非注册会员发布信息时是否启用注册提示信息窗口<br /><i style=color:red>注意：若您设置了非注册会员不能发布信息，该设置可不填写</i>',
        "type" => '布尔型',
        "class" => '分类信息设置'
    ),
    
    "cfg_nonmember_perday_post" => array(
        "des" => '非注册会员每天至多发布多少条分类信息<br /><i style=color:red>注意：该功能在您设置了非会员可以发布信息时生效<br />若不限制非注册会员发布信息的条数请留空</i>',
        "type" => '字符型',
        "class" => '分类信息设置'
    ),
    
    "cfg_if_info_verify" => array(
        "des" => '是否开启信息发布审核功能',
        "type" => '布尔型',
        "class" => '信息审核功能设置'
    ),
    
    "cfg_if_comment_verify" => array(
        "des" => '是否开启信息评论审核功能',
        "type" => '布尔型',
        "class" => '信息审核功能设置'
    ),
    
    "cfg_if_comment_open" => array(
        "des" => '是否开启网友评论',
        "type" => '布尔型',
        "class" => '信息审核功能设置'
    ),
    
    "cfg_if_guestbook_verify" => array(
        "des" => '是否开启网站留言审核功能',
        "type" => '布尔型',
        "class" => '信息审核功能设置'
    )
);
?>
<!DOCTYPE html>
<html>
<head>
<title>ZHCMS后台管理</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php
include (ZH_PATH . DS . 'manager' . DS . 'css' . ZH);
include (ZH_PATH . DS . 'manager' . DS . 'js' . ZH);
?>
</head>
<body>
<?php 
include (ZH_PATH . DS . 'manager' . DS . 'top' . ZH);
include (ZH_PATH . DS . 'manager' . DS . 'menu' . ZH);
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="admin_config.php" class="current">系统设置</a> <h5>&nbsp;&nbsp;<B>说明</B>：这里的设置代表了系统中的全局设置，如果需要对某个版块或用户组进行单独设置，请到各自的管理中心操作</h5></div>
  </div>
  <div class="container-fluid">
    <!--  -->
    <div class="row-fluid">
<?php 

$action=SafeRequest(getPGC('action'),0);

switch ($action)
{
    case "save":
        save($admin_global_class,$admin_global,$db_pre);
        break;
    default:
        main($admin_global_class,$admin_global,$db_pre);
}

function main($admin_global_class,$admin_global,$db_pre)
{
    $rs=D("".$db_pre."config")->getLinkAll("",true);
    $datas = $rs['datas'];
    $rows = $rs['rows'];
    for ($j = 0; $j < $rows; $j ++) {
        $data[$datas[$j]['name']] = $datas[$j]['value'];
    }
    
?>
    <form action="?" id="form1" name="form1" method="post" class="form-horizontal">
    <?php 
    foreach($admin_global_class as $k =>$zhmvc_v){
    ?>
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
        <h5><?php echo $zhmvc_v;?></h5>
      </div>
      <div class="widget-content">
      <?php 
      foreach ($admin_global as $k =>$a){
          if ($a["class"]==$zhmvc_v){
              if(!isset($data[$k]))
              {
                  $valuezhi="";
              }
              else 
              {
                  $valuezhi=$data[$k];
              }
              
	  ?>
          	<div class="control-group">
              <label class="control-label"><?php echo $a["des"];?></label>
              <div class="controls">
                <?php 
                if($k == 'SiteDescription' || $k == 'SiteStat' || $k == 'cfg_forbidden_post_ip' || $k =='cfg_site_open_reason'){
                    echo "<textarea name=\"".$k."\" class=\"input\" style=\"height:100px\">".$valuezhi."</textarea>";
                }
                elseif($k == 'SiteLogo')
                {
                    echo "<input name=\"".$k."\" type=\"text\" class=\"span\" value=\"".$valuezhi."\" readonly=\"true\"><br />&nbsp;<iframe name=\"up\" frameborder=\"0\" width=\"300\" height=\"60\" scrolling=\"no\" src=\"uploadfile.php\"></iframe>";
                    if($valuezhi) 
                    { 
                        echo "<a href=\"".$valuezhi."\" target=\"_blank\"><img src=\"".$valuezhi."\" alt=\"\" height=\"30\" width=\"80\" />预览图片</a>";
                    } 
                }
                elseif($k == 'cfg_editor')
                {
                    echo '<select name="'.$k.'"><option value="1" ';
                    if($valuezhi==1){
                        echo "selected=\"selected\"";
                    }
                    echo ">==eWebEditor编辑器==</option><option value=\"2\" "; 
                    if($valuezhi==2){
                        echo "selected=\"selected\"";
                    }
                    echo ">==CKEditor编辑器==</option><option value=\"3\" ";
                    if($valuezhi==3){
                        echo "selected=\"selected\"";
                    }
                    echo ">==UEditor编辑器==</option><option value=\"4\" ";
                    if($valuezhi==4){
                        echo "selected=\"selected\"";
                    }
                    echo ">==KindEditor编辑器==</option></select>";
                }
                else
                {
                    if($a["type"]=="布尔型"){
                        echo "<select name=\"".$k."\"/>";
                        echo "<option value=\"1\"";
                        echo ($valuezhi == 1)?" selected='selected' style='background-color:#6eb00c; color:white!important;'":"";
                        echo ">开启</option>";
                        echo "<option value=\"0\"";
                        echo  ($valuezhi == 0)?" selected='selected' style='background-color:#6eb00c; color:white!important;'":"";
                        echo ">关闭</option>";
                        echo "<select>";
                    }else{
                        echo "<input name=\"".$k."\" value=\"".$valuezhi."\" class=\"input\"/>";
                    }
                }
                echo ($a["type"]=="布尔型")?"&nbsp;":"<p class=\"help-block\" style='color:blue'>{@#\$".$k."#@}</p>";
                ?>
              </div>
            </div>
       <?php 
			}
      }
       ?>
      </div>
    </div>
    <?php 
    }
    ?>
    <div class="widget-box">
      <div class="widget-content">
        <div class="form-actions">
        	<input type="hidden" name="action" value="save">
            <button type="submit" class="btn btn-success">保存</button>
        </div>
      </div>
    </div>
    
    </form>
<?php 
}

function save($admin_global_class,$admin_global,$db_pre)
{
    $id=SafeRequest(getPGC('id'),0);
    
    foreach ($admin_global as $k =>$a){
        
        $zhi=SafeRequest(getPGC($k),0);
        $map['name']=$k;
        $rs=D("".$db_pre."config")->where($map)->order("id desc")->getLinkAll("",true);
        $map=null;
        $datas = $rs['datas'];
        $rows = $rs['rows'];
        if($rows>0)
        {
            $wheremap=array();
            $updatedata=array();
            $wheremap['name']=$k;
            $updatedata["value"]=$zhi;
            D("".$db_pre."config")->where($wheremap)->LinkUpdate($updatedata);
            $wheremap=null;
            $updatedata=null;
        }
        else
        {
            $updatedata=array();
            $updatedata["name"]=$k;
            $updatedata["value"]=$zhi;
            D("".$db_pre."config")->LinkInsert($updatedata);
            $updatedata=null;
        }
    }
    
    //$sysEditor=SafeRequest(getPGC('editor'),0);
    echo "<br>更新成功!";
}
?>
  </div>
  <!--  -->
  </div>
</div>
<?php 
include (ZH_PATH . DS . 'manager' . DS . 'foot' . ZH);
?>
</body>
</html>