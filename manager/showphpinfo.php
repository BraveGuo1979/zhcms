<?php
namespace ZHMVC\DB\MANAGER;
if (! isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(__FILE__)) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);

$_curlid=0;

function tc($temp)
{
    if ($temp == 1) {
        echo '<font color=green><b>√</b></font>';
    } else {
        echo '<font color=red><b>×</b></font>';
    }
}
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
    <div id="breadcrumb"> <a href="main.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>主页</a> <a href="main.php" class="current">系统信息</a> </div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>系统信息</h5>
          </div>
          <div class="widget-content">
            ZHCMS系统由BraveGuo研发，本版本授权给<font color="red">  </font>，当前版本 Alpha 0.01。
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
              <tbody>
            <tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器名：</td>
				<td width="70%" >&nbsp;<?php echo  getenv("SERVER_NAME");?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器IP：</td>
				<td width="70%" >&nbsp;<?php echo  getenv("REMOTE_HOST");?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器端口：</td>
				<td width="70%" >&nbsp;<?php echo  getenv("SERVER_PORT");?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器时间：</td>
				<td width="70%" >&nbsp;<?php echo  date("Y年m月d日 h:s",time());?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器语种：</td>
				<td width="70%" >&nbsp;<?php echo getenv("HTTP_ACCEPT_LANGUAGE");?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;脚本超时时间：</td>
				<td width="70%" >&nbsp;<?php echo get_cfg_var("max_execution_time");?> 秒</td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;站点物理路径：</td>
				<td width="70%" >&nbsp;<?php echo realpath("../")?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器解译引擎：</td>
				<td width="70%" >&nbsp;<?php echo getenv("SERVER_SOFTWARE");?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;服务器操作系统：</td>
				<td width="70%" >&nbsp;<?php echo PHP_OS;?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;PHP解释器版本：</td>
				<td width="70%" >&nbsp;<?php echo PHP_VERSION;?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;可上传最大单文件：</td>
				<td width="70%" >&nbsp;<?php echo get_cfg_var("upload_max_filesize");?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;BCMath
					support：</td>
				<td width="70%" >&nbsp;<?php tc(addslashes("BCMath support"));?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;SMTP<font
					color="#888888">&nbsp;(地址:<?php echo get_cfg_var("SMTP");?>)</font>：
				</td>
				<td width="70%" >&nbsp;<?php tc(addslashes("smtp"));?></td>
			</tr>
			<tr>
				<td width="30%" height="23">&nbsp;&nbsp;XML
					Support：</td>
				<td width="70%" >&nbsp;<?php tc(addslashes("XML Support"));?></td>
			</tr>
			<tr>
				<td width="30%" height="23"></td>
				<td width="70%" >&nbsp;<a href="phpinfo.php"
					target="_blank"><font color="#CC0000">phpinfo</font></a></td>
			</tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon">
            <input type="checkbox" id="title-checkbox" name="title-checkbox" />
            </span>
            <h5>开发信息</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped with-check">
              <tbody>
                <tr>
				<td width="20%"  height="23">&nbsp;&nbsp;产品开发</td>
				<td width="80%" >&nbsp;&nbsp;BraveGuo软件开发工作室</td>
			</tr>
			<tr>
				<td width="20%"  height="23">&nbsp;&nbsp;产品负责</td>
				<td width="80%" >&nbsp;&nbsp;<font color="blue">BraveGuo
				</font>&nbsp;&nbsp;QQ：835197167;&nbsp;E-Mail：<a href="guoyong_1979@163.com" target="_blank">guoyong_1979@163.com</a>
					<br /></td>
			</tr>
			<tr>
				<td width="20%"  height="23">&nbsp;&nbsp;关于我们</td>
				<td width="80%" >&nbsp;&nbsp;<a
					href="http://www.guogege.net/" target="_blank">http://www.guogege.net</a></td>
			</tr>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
<?php 
include (ZH_PATH . DS . 'manager' . DS . 'foot' . ZH);
?>
</body>
</html>