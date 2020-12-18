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

if ($isper == 1) {
    $ErrMsg = "对不起，你没有访问该页面的权限";
    echo $ErrMsg;
    exit();
} elseif ($isper == 0) {
    $ErrMsg = "对不起，地址错误";
    echo $ErrMsg;
    exit();
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
			<div id="breadcrumb">
				<a href="/manager/showphpinfo.php" title="Go to Home"
					class="tip-bottom"> <i class="icon-home"></i>主页
				</a> <a href="admin_catetypeoptions.php" class="current">管理首页</a>
			</div>
			<button class="btn" onClick="location='?atcion=main';">管理首页</button>
			<button class="btn btn-primary" onClick="location='?atcion=add';">新增</button>
		</div>
		<div class="container-fluid">
			<!--  -->
			<div class="row-fluid">
<?php
$action = SafeRequest(getPGC("atcion"), 0);
switch ($action) {
    case "save":
        save($db_pre);
        break;
    case "add":
        add($db_pre);
        break;
    case "del":
        del($db_pre);
        break;
    default:
        main($db_pre);
}

function main($db_pre)
{
    ?>
<div class="widget-box">
					<div class="widget-content nopadding">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Id</th>
									<th>类别</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
  <?php
    $rs1 = D("" . $db_pre . "options")->where("classid='0'")->getLinkAll("id", true);
    $datas1 = $rs1['datas'];
    $rows1 = $rs1['rows'];
    If ($rows1 <= 0) {
        $OutStr = "<tr>";
        $OutStr = $OutStr . "<td colspan=10>&nbsp;<font color=\"red\">暂无内容</font></td>";
        $OutStr = $OutStr . "</tr></tbody>";
        echo $OutStr;
    } else {
        $pa = new \ZHMVC\B\TOOL\ShowPages();
        $pa->pvar = "pg";
        $pa->set(20, $rows1);
        $rs = D("" . $db_pre . "options")->where("classid='0'")->order("id desc")
        ->limit($pa->limit())
        ->getLinkAll("*", true);
        $datas = $rs['datas'];
        $rows = $rs['rows'];
        // print_r($datas);
        for ($i = 0; $i < $rows; $i ++) {
            $data = $datas[$i];
            ?>
  <tr>
									<td><?php echo $data['id']; ?></td>
									<td><?php echo $data['title']; ?></td>
									<td class="taskOptions"><a
										href="?atcion=add&postid=<?php echo $data['id']; ?>">编辑</a> |
										<a href="?atcion=del&postid=<?php echo $data['id']; ?>"
										onclick="{if(confirm('确定删除吗?')){return true;}return false;}">删除</a></td>
								</tr>
<?php
        }
        ?>
    </tbody>
							<tfoot>
								<tr>
									<td colspan="10">&nbsp;<?php $pa -> output(0); ?></td>
								</tr>
							</tfoot>
<?php
    }
    ?>
</table>
<?php
}

function add($db_pre)
{
    $postid = SafeRequest(getPGC("postid"), 0);
    
    if (($postid != "") && ($postid != "0")) {
        $bind = array(
            "id" => $postid
        );
        $rs = D("" . $db_pre . "options")->where($bind)->getLinkOne("*", true);
        $data = $rs['datas'];
        $rows = $rs['rows'];
        If ($rows != 0) {
            $title = $data['title'];
            $description = $data['description'];
        } else {
            $title = '';
            $description = '';
        }
    } else {
        $title = '';
        $description = '';
    }
    ?>
<form name="PForm" id="PForm" method="post"
							action="?atcion=save&postid=<?php echo $postid; ?>"
							class="form-horizontal">
							<div class="widget-box">
								<div class="widget-title">
									<span class="icon"> <i class="icon-align-justify"></i>
									</span>
									<h5>管理</h5>
								</div>
								<div class="widget-content">
									<!-- 表单 -->
									<div class="control-group">
										<label class="control-label">名称:</label>
										<div class="controls">
											<input type="text" name="title" class="span" placeholder=""
												value="<?php echo $title; ?>" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">描述:</label>
										<div class="controls">
											<input type="text" name="description" class="span"
												placeholder="" value="<?php echo $description; ?>" />
										</div>
									</div>
									
									<div class="form-actions">
										<button type="submit" class="btn btn-success">保存</button>
									</div>
									<!-- 表单 -->
								</div>
							</div>
						</form>
<?php
}

function save($db_pre)
{
    $postid = SafeRequest(getPGC("postid"), 0);
    $classid = 0;
    $displayorder = 0;
    
    $title = SafeRequest(getPGC("title"), 0);
    
    $description = SafeRequest(getPGC("description"), 0);
    
    $identifier ="";
    
    $type ="";
    
    $available = 0;
    
    $required = 0;
    
    $search = "";
    
    $rules = "";
    
    If ($title == "") {
        $ErrMsg = "对不起，长度不符！";
        $ErrMsg = $ErrMsg . "<li>长度等于0或大于250";
        echo $ErrMsg;
        exit();
    }
    
    if (($postid != "") && ($postid != "0")) {
        $updatedata = array(
            "classid" => $classid,
            "displayorder" => $displayorder,
            "title" => $title,
            "description" => $description,
            "identifier" => $identifier,
            "type" => $type,
            "rules" => $rules,
            "available" => $available,
            "required" => $required,
            "search" => $search
        );
        $wheremap = array(
            "id" => $postid
        );
        D("" . $db_pre . "models")->where($wheremap)->LinkUpdate($updatedata);
    } else {
        $updatedata = array(
            "classid" => $classid,
            "displayorder" => $displayorder,
            "title" => $title,
            "description" => $description,
            "identifier" => $identifier,
            "type" => $type,
            "rules" => $rules,
            "available" => $available,
            "required" => $required,
            "search" => $search
        );
        D("" . $db_pre . "options")->LinkInsert($updatedata);
    }
    echo "<script>alert('更新成功');window.location.href='admin_optionsclass.php';</script>";
}

function del($db_pre)
{
    $postid = SafeRequest(getPGC("postid"), 0);
    if (($postid != "") && ($postid != "0")) {
        $wheremap = array(
            "id" => $postid
        );
        D("" . $db_pre . "options")->where($wheremap)->LinkDelete();
        echo "<script>alert('更新成功');window.location.href='admin_optionsclass.php';</script>";
    }
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
