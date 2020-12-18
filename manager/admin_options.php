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
									<th>所属类别</th>
									<th>名称</th>
									<th>变量名</th>
									<th>类型</th>
									<th>显示顺序</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
<?php
    $rs1 = D("" . $db_pre . "options")->where("classid<>'0'")->getLinkAll("id", true);
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
        $rs = D("" . $db_pre . "options")->where("classid<>'0'")->order("id desc")
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
		<td><?php 
			$bind1=array("id"=>$data['classid']);
			$rs2 = D("" . $db_pre . "options")->where($bind1)->getLinkOne("*", true);
			$data2 = $rs2['datas'];
			$rows2 = $rs2['rows'];
			echo $data2['title'];
			?></td>
									<td><?php echo $data['title']; ?></td>
									<td><?php echo $data['identifier']; ?></td>
									<td><?php echo $data['type']; ?></td>
									<td><?php echo $data['displayorder']; ?></td>
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
            $classid = $data['classid'];
            $displayorder = $data['displayorder'];
            $title = $data['title'];
            $description = $data['description'];
            $identifier = $data['identifier'];
            $type = $data['type'];
            $rules = $data['rules'];
            $rule = unserialize($rules);

            $available = $data['available'];
            $required = $data['required'];
            $search = $data['search'];
            if ($type == "text") {
                $show_text = "";
                $show_textarea = "none";
                $show_radio = "none";
                $show_checkbox = "none";
                $show_select = "none";
                $show_number = "none";
                $rules_text = $rule['text'];
                $rules_radio = "";
                $rules_checkbox = "";
                $rules_select = "";
                $rules_number = "";
                $rules_upload = "";
            } elseif ($type == "textarea") {
                $show_text = "none";
                $show_textarea = "";
                $show_radio = "none";
                $show_checkbox = "none";
                $show_select = "none";
                $show_number = "none";
                $rules_text = "";
                $rules_textarea = $rule['text'];
                $rules_radio = "";
                $rules_checkbox = "";
                $rules_select = "";
                $rules_number = "";
                $rules_upload = "";
            } elseif ($type == "radio") {
                $show_text = "none";
                $show_textarea = "none";
                $show_radio = "";
                $show_checkbox = "none";
                $show_select = "none";
                $show_number = "none";
                $rules_radio = $rule['text'];
                $rules_text = "";
                $rules_textarea = "";
                $rules_checkbox = "";
                $rules_select = "";
                $rules_number = "";
                $rules_upload = "";
            } elseif ($type == "checkbox") {
                $show_text = "none";
                $show_textarea = "none";
                $show_radio = "none";
                $show_checkbox = "";
                $show_select = "none";
                $show_number = "none";
                $rules_checkbox = $rule['text'];
                $rules_text = "";
                $rules_textarea = "";
                $rules_radio = "";
                
                $rules_select = "";
                $rules_number = "";
                $rules_upload = "";
            } elseif ($type == "select") {
                $show_text = "none";
                $show_textarea = "none";
                $show_radio = "none";
                $show_checkbox = "none";
                $show_select = "";
                $show_number = "none";
                $rules_select = $rule['text'];
                $rules_text = "";
                $rules_textarea = "";
                $rules_radio = "";
                $rules_checkbox = "";
                
                $rules_number = "";
                $rules_upload = "";
            } elseif ($type == "number") {
                $show_text = "none";
                $show_textarea = "none";
                $show_radio = "none";
                $show_checkbox = "none";
                $show_select = "none";
                $show_number = "";
                $rules_number = $rule['text'];
                $rules_text = "";
                $rules_textarea = "";
                $rules_radio = "";
                $rules_checkbox = "";
                $rules_select = "";
                $rules_upload = "";
            } elseif ($type == "upload") {
                $show_text = "none";
                $show_textarea = "none";
                $show_radio = "none";
                $show_checkbox = "none";
                $show_select = "none";
                $show_number = "";
                $rules_number = "";
                $rules_text = "";
                $rules_textarea = "";
                $rules_radio = "";
                $rules_checkbox = "";
                $rules_select = "";
                $rules_upload = $rule['text'];
            }
        } else {
            $show_text = "";
            $show_textarea = "none";
            $show_radio = "none";
            $show_checkbox = "none";
            $show_select = "none";
            $show_number = "none";
            $show_upload = "none";
            
            $classid = '';
            
            $displayorder = '';
            
            $title = '';
            
            $description = '';
            
            $identifier = '';
            
            $type = '';
            
            $rules = '';
            
            $available = '';
            
            $required = '';
            
            $search = '';
            $rules_text = "";
            $rules_textarea = "";
            $rules_radio = "";
            $rules_checkbox = "";
            $rules_select = "";
            $rules_number = "";
            $rules_upload = "";
        }
    } else {
        $show_text = "";
        $show_textarea = "none";
        $show_radio = "none";
        $show_checkbox = "none";
        $show_select = "none";
        $show_number = "none";
        $show_upload = "none";
        $classid = '';
        $displayorder = '';
        $title = '';
        $description = '';
        $identifier = '';
        $type = '';
        $rules = '';
        $available = '';
        $required = '';
        $search = '';
        $rules_text = "";
        $rules_textarea = "";
        $rules_radio = "";
        $rules_checkbox = "";
        $rules_select = "";
        $rules_number = "";
        $rules_upload = "";
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
										<label class="control-label">所属分类:</label>
										<div class="controls">
											<select name="classid">
                                    <?php
    $rs2 = D("" . $db_pre . "options")
    ->where("classid='0'")
    ->getLinkAll("`id`,`title`", true);
    $datas2 = $rs2['datas'];
    $rows2 = $rs2['rows'];
    for ($i = 0; $i < $rows2; $i ++) {
        $data2 = $datas2[$i];
        ?>
                                    <option
													value="<?php echo $data2['id'];?>"
													<?php
        If (empty($classid) == false) {
            If ($classid == $data2['id']) {
                echo " selected";
            }
        }
        ?>><?php echo $data2['title'];?></option>
                                    <?php
    }
    ?>
                                    </select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">排序:</label>
										<div class="controls">
											<input type="text" name="displayorder" class="span"
												placeholder="" value="<?php echo $displayorder; ?>" />
										</div>
									</div>
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
									<div class="control-group">
										<label class="control-label">变量名:</label>
										<div class="controls">
											<input type="text" name="identifier" class="span"
												placeholder="" value="<?php echo $identifier; ?>" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">类型:</label>
										<div class="controls">
											<select id="select_id" name="type">
												<option value="text" <?php if($type == "text") echo "selected"?>>字串(text)</option>
												<option value="textarea" <?php if($type == "textarea") echo "selected"?>>编辑框(textarea)</option>
												<option value="number" <?php if($type == "number") echo "selected"?>>数字(number)</option>
												<option value="radio" <?php if($type == "radio") echo "selected"?>>单选(radio)</option>
												<option value="checkbox" <?php if($type == "checkbox") echo "selected"?>>多选(checkbox)</option>
												<option value="select" <?php if($type == "select") echo "selected"?>>选择(select)</option>
												<option value="upload" <?php if($type == "upload") echo "upload"?>>上传(upload)</option>
											</select>

										</div>
									</div>
									<div class="control-group">
										<label class="control-label">其他属性:</label>
										<div class="controls">
											<label for="available"><input name="available"
												type="checkbox" id="available" checked>可用</label> <label
												for="required"><input name="required" type="checkbox"
												id="required" checked>必填</label> <label for="search"><input
												name="search" type="checkbox" id="search">参与搜索</label>

										</div>
									</div>

									<div id="style_text" style="display:<?php echo $show_text;?>">
										<div class="control-group">
											<label class="control-label">字串(text)</label>
											<div class="controls">
												<b>字符最大长度:</b><br /> <input type="text" size="50"
													name="rules_text" value="<?php echo $rules_text?>">
											</div>
										</div>
									</div>

									<div id="style_textarea" style="display:<?php echo $show_textarea;?>">
										<div class="control-group">
											<label class="control-label">编辑框(textarea)</label>
											<div class="controls">
												<b>字符最大长度:</b><br /> <input type="text" size="50"
													name="rules_textarea" value="<?php echo $rules_textarea?>">
											</div>
										</div>
									</div>

									<div id="style_radio" style="display:<?php echo $show_radio;?>">
										<div class="control-group">
											<label class="control-label">单选(radio)</label>
											<div class="controls">
												<b>选项内容:</b><br />只在项目为可选时有效，每行一个选项，后面为内容，例如:
												<br />
												<i>苹果<br />香蕉<br />没有水果
												</i><br />注意:
												选项确定后请勿修改内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的前后位置来实现<br />
												<textarea rows="8" name="rules_radio"
													id="rules[radio][choices]" cols="50"><?php echo $rules_radio?></textarea>
											</div>
										</div>
									</div>


									<div id="style_checkbox" style="display:<?php echo $show_checkbox;?>">
										<div class="control-group">
											<label class="control-label">多选(checkbox)</label>
											<div class="controls">
												<b>选项内容:</b><br />只在项目为可选时有效，每行一个选项，后面为内容，例如:
												<br />
												<i>苹果<br />香蕉<br />菠萝
												</i><br />注意:
												选项确定后请勿修改内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的前后位置来实现<br />
												<textarea rows="8" name="rules_checkbox"
													id="rules[checkbox][choices]" cols="50"><?php echo $rules_checkbox?></textarea>

											</div>
										</div>
									</div>

									<div id="style_select" style="display:<?php echo $show_select;?>">
										<div class="control-group">
											<label class="control-label">选择(select)</label>
											<div class="controls">
												<b>选项内容:</b><br />只在项目为可选时有效，每行一个选项，后面为内容，例如:
												<br />
												<i>mymps分类信息系统<br />mymps企业建站系统<br />mympsB2B商务系统
												</i><br />
												<br />注意:
												选项确定后请勿修改内容的对应关系，但仍可以新增选项。如需调换显示顺序，可以通过移动整行的前后位置来实现<br />
												<textarea rows="8" name="rules_select"
													id="rules[select][choices]" cols="50"><?php echo $rules_select?></textarea>


											</div>
										</div>
									</div>
									<div id="style_number" style="display:<?php echo $show_number;?>">
										<div class="control-group">
											<label class="control-label">数字(number)</label>
											<div class="controls">
												<b>单位（可选）:</b><br /> <input type="text" size="50"
													name="rules_number" value="<?php echo $rules_number?>">
											</div>
										</div>
									</div>
									
									<div id="style_upload" style="display:<?php echo $show_upload;?>">
										<div class="control-group">
											<label class="control-label">字串(text)</label>
											<div class="controls">
												<b>上传名称:</b><br /> <input type="text" size="50"
													name="rules_upload" value="<?php echo $rules_upload?>">
											</div>
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

$("#select_id").change(function(){
	var styles, key;
	styles=new Array('text','textarea','radio','checkbox','select','number','upload');
	for(var i=0;i<styles.length;i++) {
		if(styles[i]==$("#select_id").val())
		{
			$('#style_'+$("#select_id").val()).css('display','');
			console.log('style_'+$("#select_id").val());
		}
		else
		{
			$('#style_'+styles[i]).css('display','none');
			console.log('2style_'+styles[i]);
		}
	}
	
	
});
</script>
<?php
}

function save($db_pre)
{
    $postid = SafeRequest(getPGC("postid"), 0);
    
    $classid = SafeRequest(getPGC("classid"), 0);
    
    $displayorder = SafeRequest(getPGC("displayorder"), 0);
    
    $title = SafeRequest(getPGC("title"), 0);
    
    $description = SafeRequest(getPGC("description"), 0);
    
    $identifier = SafeRequest(getPGC("identifier"), 0);
    
    $type = SafeRequest(getPGC("type"), 0);
    
    // $rules=getPGC("rules_".$type);
    
    if ($type == "text") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'maxlength';
        $rules['type'] = 'text';
    } elseif ($type == "textarea") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'maxlength';
        $rules['type'] = 'textarea';
    } elseif ($type == "radio") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'choices';
        $rules['type'] = 'radio';
    } elseif ($type == "checkbox") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'choices';
        $rules['type'] = 'checkbox';
    } elseif ($type == "select") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'choices';
        $rules['type'] = 'select';
    } elseif ($type == "number") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'units';
        $rules['type'] = 'number';
    } elseif ($type == "upload") {
        $rules['text'] = getPGC("rules_" . $type);
        $rules['rule'] = 'units';
        $rules['type'] = 'upload';
    }
    
    $available = SafeRequest(getPGC("available"), 0);
    
    $required = SafeRequest(getPGC("required"), 0);
    
    $search = SafeRequest(getPGC("search"), 0);
    
    $rule = serialize($rules);
    
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
            "rules" => $rule,
            "available" => $available,
            "required" => $required,
            "search" => $search
        );
        $wheremap = array(
            "id" => $postid
        );
        D("" . $db_pre . "options")->where($wheremap)->LinkUpdate($updatedata);
    } else {
        $updatedata = array(
            "classid" => $classid,
            "displayorder" => $displayorder,
            "title" => $title,
            "description" => $description,
            "identifier" => $identifier,
            "type" => $type,
            "rules" => $rule,
            "available" => $available,
            "required" => $required,
            "search" => $search
        );
        D("" . $db_pre . "options")->LinkInsert($updatedata);
    }
    echo "<script>alert('更新成功');window.location.href='admin_options.php';</script>";
}

function del($db_pre)
{
    $postid = SafeRequest(getPGC("postid"), 0);

    if(($postid!="") && ($postid!="0")) {
        $wheremap = array(
            "id" => $postid
        );
        D("" . $db_pre . "options")->where($wheremap)->LinkDelete();
        
        echo "<script>alert('更新成功');window.location.href='admin_options.php';</script>";
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
