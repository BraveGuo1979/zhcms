<?php
if (! isset($_SESSION)) {
    session_start();
}
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
?>
<!--Header-part-->
<div id="header">
<h1>ZHCMS</h1>
</div>
<!--close-Header-part-->

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
<ul class="nav">
<li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">欢迎<?php echo $_SESSION['adminname'];?></span><b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="/manager/admin_info.php"><i class="icon-user"></i> 个人信息</a></li>
<li class="divider"></li>
<li><a href="/manager/admin_mima.php"><i class="icon-check"></i> 修改密码</a></li>
<li class="divider"></li>
<li><a href="/manager/loginquit.php"><i class="icon-key"></i> 退出</a></li>
</ul>
</li>
<li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">消息</span> <!-- <span class="label label-important">5</span> --> <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> 收件箱</a></li>
<li class="divider"></li>
<li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> 发件箱</a></li>
<li class="divider"></li>
<li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> 废件箱</a></li>
</ul>
</li>
</ul>
</div>
<!--close-top-Header-menu-->