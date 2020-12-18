<?php 
include (dirname(__FILE__) . "/zhconfig/Config.php");
include (dirname(__FILE__) . '/openid.php');
if (! isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION["openid"]))
{
    $openid =$_SESSION['openid'];
}
else
{
    $tools = new Openid();
    $openid = $tools->GetOpenid();
    $_SESSION['openid']=$openid;
}
$conn=new \ZPSC\D\Zpsc();

$sql = "select * from user_bind where open_id='" . $openid . "'";
$data=$conn->getSqlOne($sql);
$row=$conn->getRowCount();
if ($row == 0) {
    echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>医保卡制卡申请</title>
</head>
<body>
请先通过菜单绑定用户信息！
</body>
<html>';
}
$u_id_number = $data['u_id_number'];

$sql1 = "select id,telephone,username from wjw_user where u_id_number='" . $u_id_number . "'";
$data1=$conn->getSqlOne($sql1);
$row1=$conn->getRowCount();
if ($row1 == 0) {
    echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>医保卡制卡申请</title>
</head>
<body>
用户信息不存在！
</body>
<html>';
}

$userid=$data1['id'];
$xingming=$data1['username'];
?>
<!DOCTYPE HTML>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1 maximum-scale=2, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Add to Home">
		<meta name="format-detection" content="telephone=no">
		<meta http-equiv="x-rim-auto-match" content="none">
		<title>医保卡制卡申请</title>
		<meta name="keywords" content="医保卡制卡申请">
		<meta name="description" content="医保卡制卡申请">
		<meta name="Copyright" content="医保卡制卡申请">
		<meta name="author" content="braveguo">
		<!-- 网站的ico图标 -->
		<link rel="shortcut icon" href="/s/t/img/favicon.jpg" type="image/x-icon">
		<link href="/s/t/css/publi.css" rel="stylesheet" type="text/css">
		<link href="/s/t/css/style.css" rel="stylesheet" type="text/css">
		<!--[if lt IE 9]>
			<script src="/s/t/js/html5.js"></script>
			<script src="/s/t/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		<!-- ====================================loading -->
		<section id="loading"></section>
		<!-- ====================================页面开始 -->
		<!--登录-->
		<header class="acc_head">
			<div class="clearfix">
				<h1 class="tc">医保卡制卡申请</h1>
			</div>
		</header>
		<section class="acc_apply">
		<form id="form_tijiao" name="form_tijiao" method="post" action="save.php?openid=<?php echo $openid;?>&userid=<?php echo $userid;?>" >
			<ul>
				<li class="clearfix">
					<label class="tl">员工姓名：</label>
					<?php echo $xingming;?>
				</li>
				<li class="clearfix">
					<label class="tl acc_five">员工性别：</label>
					<input autocomplete="off" placeholder="请输入姓名" class="" type="text" />
				</li>
				<li class="clearfix">
					<label class="tl acc_five">身份证号码：</label>
					<input autocomplete="off" placeholder="请输入身份证号码" class="" type="text" />
				</li>
				<li class="clearfix" >
					<label class="tl acc_four fl">照片：</label>
				</li>
				<li style="border-top: 0; margin-bottom: 60px;">
					<div class="acc_img">
						<p class="tc">参考样例</p>
						<div id="sss">
							<img class="acc_imgin" src="/s/t/img/acc_img.png" id="img0">
						</div>
						<div class="acc_sc">
							<a href="javascript:;" class="tc acc_scicon">选择图片</a>
							<input type="file" name="file0" id="file0" multiple class="ph08" />

						</div>
						<p class="ph09 ">注：身份证上的所有信息清晰可见，必须能看清证件号。照片需免冠，建议未化妆。手持证件人的五官清晰可见。照片内容真实有效。不得做任何修改。支持jpg、jpeg、bmp、gif格式照片。大小不超过5M</p>
					</div>
				</li>
				<li class="clearfix">
					<label class="tl acc_five">审核结果：</label>
					<input autocomplete="off" placeholder="请输入身份证号码" class="" type="text" />
				</li>
			</ul>
		</form>
		</section>
		<footer class="acc_foot clearfix">
			<a href="#" class="fl tc acc_cancel">取消</a>
			<a onclick="document.getElementById('form_tijiao').submit();" class="fl tc acc_sure">提交</a>
		</footer>
		<!--弹出层-->
		<article id="tip">
			<div class="pack">
				<h1 class="tc">提交成功</h1>
				<p class="tc"></p>
				<a href="#">确定</a>
			</div>
		</article>
		<!-- 网站要用到的一些类库 -->
		<script src="/s/t/js/jquery1.8.3.min.js"></script>
		<script src="/s/t/js/script.js"></script>
		<script type="text/javascript">
		
			$(function() {
				/*document.documentElement.style.fontSize=document.documentElement.clientWidth*12/320+'px';*/
				$(window).on("load", function() {
						$("#loading").fadeOut();
					})
					// ========================================浮层控制
				$("#tip .pack a").on("click", function() {
					$("#tip").fadeOut()
					$("#tip .pack p").html("")
					return false;
				})

				function alerths(str) {
					$("#tip").fadeIn()
					$("#tip .pack p").html(str)
					return false;
				}
				$(".acc_sure").on("click", function() {
					alerths("请等待审核！")
					return false;
				})
				$("#file0").change(function() {
					if (this.files && this.files[0]) {
						var objUrl = getObjectURL(this.files[0]);
						console.log("objUrl = " + objUrl);
						if (objUrl) {
							$("#img0").attr("src", objUrl);
							$("#file0").click(function(e) {
								$("#img0").attr("src", objUrl);
							});
						} else {
							//IE下，使用滤镜
							this.select();
							var imgSrc = document.selection.createRange().text;
							var localImagId = document.getElementById("sss");
							//图片异常的捕捉，防止用户修改后缀来伪造图片
							try {
								preload.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = data;
							} catch (e) {
								this._error("filter error");
								return;
							}
							this.img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale',src=\"" + data + "\")";
						}
					}
				});
				//建立一個可存取到該file的url
				function getObjectURL(file) {
					var url = null;
					if (window.createObjectURL != undefined) { // basic
						url = window.createObjectURL(file);
					} else if (window.URL != undefined) { // mozilla(firefox)
						url = window.URL.createObjectURL(file);
					} else if (window.webkitURL != undefined) { // webkit or chrome
						url = window.webkitURL.createObjectURL(file);
					}
					return url;
				}
			})
		</script>
	</body>

</html>