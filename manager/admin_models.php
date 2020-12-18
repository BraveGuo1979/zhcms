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
include (ZH_PATH . DS . 'manager' . DS . 'js1' . ZH);
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
				</a> <a href="admin_catetypemodels.php" class="current">管理首页</a>
			</div>
			<button class="btn" onClick="location = '?atcion=main';">管理首页</button>
			<button class="btn btn-primary" onClick="location = '?atcion=add';">新增</button>
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
									<th>模型名称</th>
									<th>显示顺序</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
<?php
$rs1 = D("" . $db_pre . "models")->getLinkAll("id", true);
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
   $rs = D("" . $db_pre . "models")->order("id desc")
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
									<td><?php echo $data['name']; ?></td>
									<td><?php echo $data['displayorder']; ?></td>
									<td class="taskOptions"><a
										href="?atcion=add&postid=<?php echo $data['id']; ?>">编辑</a> |
										<a href="?atcion=del&postid=<?php echo $data['id']; ?>"
										onclick="{if (confirm('确定删除吗?')) {return true;} return false;}">删除</a></td>
								</tr>
<?php
    }
?>
                                        </tbody>
							<tfoot>
								<tr>
									<td colspan="10">&nbsp;<?php $pa->output(0); ?></td>
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
                            $rs = D("" . $db_pre . "models")->where($bind)->getLinkOne("*", true);
                            $data = $rs['datas'];
                            $rows = $rs['rows'];
                            If ($rows != 0) {
                                $name = $data['name'];
                                $displayorder = $data['displayorder'];
                                $type = $data['type'];
                                $options = $data['options'];
                                if ($options != "") {
                                    $options1 = explode(',', $options);
                                }
                            } else {
                                
                                $name = '';
                                
                                $displayorder = '';
                                
                                $type = '';
                                
                                $options = '';
                            }
                        } else {
                            
                            $name = '';
                            
                            $displayorder = '';
                            
                            $type = '';
                            
                            $options = '';
                        }
                        ?>
                                <form name="PForm" id="PForm"
							method="post" action="?atcion=save&postid=<?php echo $postid; ?>"
							class="form-horizontal" onsubmit="selectalloption('moptselect');">
							<div class="widget-box">
								<div class="widget-title">
									<span class="icon"> <i class="icon-align-justify"></i>
									</span>
									<h5>分类选项基本设置</h5>
								</div>
								<div class="widget-content">
									<!-- 表单 -->
									<div class="control-group">
										<label class="control-label">模型名称:</label>
										<div class="controls">
											<input type="text" name="name" class="span" placeholder=""
												value="<?php echo $name; ?>" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">显示顺序:</label>
										<div class="controls">
											<input type="text" name="displayorder" class="span"
												placeholder="" value="<?php echo $displayorder; ?>" />
										</div>
									</div>
									<!--
                                            <div class="control-group">
                                                    <label class="control-label">类型:</label>
                                                    <div class="controls">
                                                            <input type="text" name="type" class="span" placeholder=""
                                                                    value="<?php echo $type; ?>" />
                                                    </div>
                                            </div>
                                            -->
									<!-- 表单 -->
								</div>
								<div class="widget-title">
									<span class="icon"> <i class="icon-align-justify"></i>
									</span>
									<h5>分类模型选项设置</h5>
								</div>
								<div class="widget-content">
									<!-- 表单 -->
									<div class="control-group">
										<label class="control-label">可用系统选项:</label>
										<div class="controls">
											<select name="from[]" id="multiselect" class="form-control"
												size="8" multiple="multiple">
                       <?php
                        if ($options != "") {
                            $W = " classid != 0 and id not in (" . $options . ")";
                        } else {
                            $W = " classid != 0";
                        }
                        $rs2 = D("" . $db_pre . "options")->where($W)
                            ->order('displayorder,id DESC')
                            ->getLinkAll("`id`,`title`,`type`", true);
                        $datas2 = $rs2['datas'];
                        $rows2 = $rs2['rows'];
                        for ($j = 0; $j < $rows2; $j ++) {
                            $data2 = $datas2[$j];
                            ?>
                             <option value="<?php echo $data2['id']; ?>"><?php echo $data2['title']; ?>(<?php echo $data2['type']; ?>)</option>
        <?php
                        }
                        ?>
                                                    </select>
										</div>
									</div>
									<div class="control-group">
										<button type="button" id="multiselect_rightAll"
											class="btn btn-block">全部添加</button>
										<button type="button" id="multiselect_rightSelected"
											class="btn btn-block">添加</button>
										<button type="button" id="multiselect_leftSelected"
											class="btn btn-block">删除</button>
										<button type="button" id="multiselect_leftAll"
											class="btn btn-block">全部删除</button>
									</div>
									<div class="control-group">
										<label class="control-label">模型选项:</label>
										<div class="controls">
											<select name="options[]" id="multiselect_to"
												class="form-control" multiple="multiple">
                        <?php
                        if ($options != "") {
                            
                            $rs1 = D("" . $db_pre . "options")->where("classid!=0 and id in(" . $options . ")")
                                ->order('displayorder,id DESC')
                                ->getLinkAll("`id`,`title`,`type`", true);
                            $datas1 = $rs1['datas'];
                            $rows1 = $rs1['rows'];
                            for ($i = 0; $i < $rows1; $i ++) {
                                $data1 = $datas1[$i];
                                ?>
                                                                <option
													value="<?php echo $data1['id']; ?>">
                                                                <?php echo $data1['title']; ?>
                                                                    (<?php echo $data1['type']; ?>)
                                                                </option>
            <?php
                            }
                        }
                        ?>
                                                    </select>
										</div>
									</div>
									<div class="form-actions">
										<button type="submit" class="btn btn-success">保存</button>
									</div>
									<!-- 表单 -->
								</div>
							</div>

						</form>
						<script type="text/javascript">
                                    $(document).ready(function () {
                                        $('#multiselect').multiselect();
                                    });
                                </script>
                                <?php
                    }

                    function save($db_pre)
                    {
                        $postid = SafeRequest(getPGC("postid"), 0);
                        
                        $name = SafeRequest(getPGC("name"), 0);
                        
                        $displayorder = SafeRequest(getPGC("displayorder"), 0);
                        
                        $type = 0;
                        
                        $options = getPGC("options");
                        
                        $option = implode(',', $options);
                        
                        If (($name == "")) {
                            $ErrMsg = "对不起，长度不符！";
                            $ErrMsg = $ErrMsg . "<li>长度等于0或大于250";
                            echo $ErrMsg;
                            exit();
                        }
                        
                        if (($postid != "") && ($postid != "0")) {
                            $updatedata = array(
                                "name" => $name,
                                "displayorder" => $displayorder,
                                "type" => $type,
                                "options" => $option
                            );
                            $wheremap = array(
                                "id" => $postid
                            );
                            D("" . $db_pre . "models")->where($wheremap)->LinkUpdate($updatedata);
                        } else {
                            $updatedata = array(
                                "name" => $name,
                                "displayorder" => $displayorder,
                                "type" => $type,
                                "options" => $option
                            );
                            D("" . $db_pre . "models")->LinkInsert($updatedata);
                        }
                        echo "<script>alert('更新成功');window.location.href='admin_models.php';</script>";
                    }

                    function del($db_pre)
                    {
                        $postid = SafeRequest(getPGC("postid"), 0);
                        if (($postid != "") && ($postid != "0")) {
                            $wheremap = array(
                                "id" => $postid
                            );
                            D("" . $db_pre . "models")->where($wheremap)->LinkDelete();
                            echo "<script>alert('更新成功');window.location.href='admin_models.php';</script>";
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
