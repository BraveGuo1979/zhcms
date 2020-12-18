<?php
namespace ZHMVC\DB\MANAGER;

if (!isset($_SESSION)) {
    session_start();
}
include (dirname(dirname(__FILE__)) . "/zhconfig/Config.php");
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);

$isp = new \ZHMVC\D\MANAGER\isPermission();
$isper = $isp->getPermission();
$_curlid = $isp->getCUrl();
$c = new \ZHCONFIG\ZhConfig();
$db_pre = $c->getDbPre();
$db_database = $c->getDatabase();
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
                    <a href="showphpinfo.php" title="Go to Home" class="tip-bottom"><i
                            class="icon-home"></i>主页</a> <a href="admin_module.php"
                                                    class="current">模块管理</a>
                </div>
                <button class="btn" onClick="location = '?atcion=main';">管理首页</button>
                <button class="btn btn-primary" onClick="location = '?atcion=add';">新增</button>
            </div>
            <div class="container-fluid">
                <!--  -->
                <div class="row-fluid">
                    <?php
                    $action = SafeRequest(getPGC('atcion'), 0);
                    switch ($action) {
                        case "butable":
                            butable($db_pre);
                            break;
                        case "bindsave":
                            bindsave($db_pre);
                            break;
                        case "bind":
                            bindadd($db_pre);
                            break;
                        case "datamain":
                            datamain($db_pre);
                            break;
                        case "datalist":
                            datalist($db_pre);
                            break;
                        case "datadel":
                            datadel($db_pre);
                            break;
                        case "datasave":
                            datasave($db_pre);
                            break;
                        case "dataadd":
                            dataadd($db_pre);
                            break;
                        case "save":
                            save($db_pre);
                            break;
                        case "add":
                            add($db_pre);
                            break;
                        case "del":
                            del($db_pre, $db_database);
                            break;
                        case "datainfolist":
                            datainfolist($db_pre, $db_database);
                            break;
                        default:
                            main($db_pre);
                    }

                    function main($db_pre) {
                        ?>
                        <div class="widget-box">
                            <div class="widget-content nopadding">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>模块标识</th>
                                            <th>模块名称</th>
                                            <th>数据表前缀</th>
                                            <th>目录</th>
                                            <th>绑定模型</th>
                                            <th>数据源</th>
                                            <th>key</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rs = D("" . $db_pre . "module")->order('id desc')->getLinkAll("id,biaoqianzhui,mulu,name,modulename,modulenamespace,rpcmainkey,rpctype,rpcurl", true);
                                        $datas = $rs['datas'];
                                        $rows = $rs['rows'];
                                        for ($j = 0; $j < $rows; $j ++) {
                                            $data = $datas[$j];
                                            ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['modulename']; ?></td>
                                                <td><?php echo $data['name']; ?></td>
                                                <td><?php echo $data['biaoqianzhui']; ?></td>
                                                <td><?php echo $data['mulu']; ?></td>
                                                <td><?php
                                    $bind1 = array(
                                        "moduleid" => $data['id']
                                    );
                                    $rs1 = D("" . $db_pre . "mbindm")->where($bind1)->getLinkOne("*", true);
                                    $data1 = $rs1['datas'];
                                    $rows1 = $rs1['rows'];
                                    if ($rows1 > 0) {
                                        if ($data1['islist'] == 0) {
                                            $sql2 = "select group_concat(name separator ',') as name from " . $db_pre . "models where find_in_set(cast(id as char), :id)";
                                            $bind2 = array(
                                                "id" => $data1['modelsid']
                                            );
                                            $rs2 = D("" . $db_pre . "models")->where(' find_in_set(cast(id as char), :id) ')->getLinkOne("group_concat(name separator ',') as name", true, $bind2);
                                            $data2 = $rs2['datas'];
                                            $rows2 = $rs2['rows'];
                                            if ($rows2 > 0) {
                                                echo $data2['name'];
                                            } else {
                                                echo "没有绑定";
                                            }
                                        } else {
                                            echo "在子模块中重新绑定";
                                        }
                                    } else {
                                        echo "没有绑定";
                                    }
                                            ?></td>
                                                <td><?php echo $data['rpctype']; ?></td>
                                                <td><?php echo $data['rpcmainkey']; ?></td>
                                                <td class="taskOptions"><a href="?atcion=datainfolist&moduleid=<?php echo $data['id']; ?>&mulu=<?php echo $data['mulu']; ?>">接口说明</a> | 
                                                    <a href="?atcion=datamain&moduleid=<?php echo $data['id']; ?>&mulu=<?php echo $data['mulu']; ?>">数据接口</a> | 
                                                    <?php
                                                    if ($data['modulenamespace'] == "ZHMVC_CATEGORY") {
                                                        echo "分类中绑定";
                                                    } else {
                                                        ?>
                                                        <a href="?atcion=bind&postid=<?php echo $data['id']; ?>">模型绑定</a> | 
                                                        <?php
                                                    }
                                                    ?>
                                                    <a href="?atcion=del&id=<?php echo $data['id']; ?>"
                                                       onclick="{
                                                                                                    if (confirm('确定删除吗?')) {
                                                                                                        return true;
                                                                                                    }
                                                                                                    return false;
                                                                                                }">删除</a>
                                                   | <a href="?atcion=butable&postid=<?php echo $data['id']; ?>">添加其他表</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php
                    }

                    function add($db_pre) {
                        $postid = SafeRequest(getPGC('id'), 0);

                        if (($postid != "") && ($postid != 0)) {
                            $bind = array(
                                "id" => $postid
                            );
                            $data = D("" . $db_pre . "module")->where($bind)->getLinkOne("*");
                            $mulu = $data['mulu'];
                            $biaoqianzhui = $data['biaoqianzhui'];
                            $name = $data['name'];
                            $modulename = $data['modulename'];
                            $rpctype = $data['rpctype'];
                            $rpcmainkey = $data['rpcmainkey'];
                            $rpcurl = $data['rpcurl'];
                            $rpchost = $data['rpchost'];
                            $rpcprivatekey = $data['rpcprivatekey'];
                            $rpcmoduleid = $data['rpcmoduleid'];
                        } else {
                            $mulu = "";
                            $biaoqianzhui = "";
                            $name = "";
                            $modulename = "";
                            $rpctype = "本地";
                            $rpcmainkey = "";
                            $rpcurl = "";
                            $rpchost = "";
                            $rpcprivatekey = "";
                            $rpcmoduleid="1";
                        }
                        ?>
                        <form action="?atcion=save&id=<?php echo $postid; ?>" id="form1"
                              name="form1" method="post" class="form-horizontal">
                            <div class="widget-box">
                                <div class="widget-title">
                                    <span class="icon"> <i class="icon-align-justify"></i>
                                    </span>
                                    <h5>模块管理－－添加模块</h5>
                                </div>
                                <div class="widget-content">
                                    <!-- 表单 -->
                                    <div class="control-group">
                                        <label class="control-label">模块目录:</label>
                                        <div class="controls">
                                            <input type="text" name="mulu" class="span" placeholder=""
                                                   value="<?php echo $mulu; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">表前缀:</label>
                                        <div class="controls">
                                            <input type="text" name="biaoqianzhui" class="span"
                                                   placeholder="" value="<?php echo $biaoqianzhui; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">模块名称:</label>
                                        <div class="controls">
                                            <input type="text" name="name" class="span" placeholder=""
                                                   value="<?php echo $name; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">数据源:</label>
                                        <div class="controls">
                                            <select name="rpctype">
                                                <option value="本地" <?php if ($rpctype == '本地') echo ' selected'; ?>>本地</option>
                                                <option value="远程" <?php if ($rpctype == '远程') echo ' selected'; ?>>远程</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">远程rpc服务地址:</label>
                                        <div class="controls">
                                            <input type="text" name="rpcurl" class="span" placeholder=""
                                                   value="<?php echo $rpcurl; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">mainkey:</label>
                                        <div class="controls">
                                            <input type="text" name="rpcmainkey" class="span" placeholder=""
                                                   value="<?php echo $rpcmainkey; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">本地站点地址，请与服务端一致:</label>
                                        <div class="controls">
                                            <input type="text" name="rpchost" class="span" placeholder=""
                                                   value="<?php echo $rpchost; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">私钥，请与服务端一致:</label>
                                        <div class="controls">
                                            <input type="text" name="rpcprivatekey" class="span" placeholder=""
                                                   value="<?php echo $rpcprivatekey; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">远程模块id:</label>
                                        <div class="controls">
                                            <input type="text" name="rpcmoduleid" class="span" placeholder=""
                                                   value="<?php echo $rpcmoduleid; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">添加</button>
                                    </div>
                                    <!-- 表单 -->
                                </div>
                            </div>
                        </form>
    <?php
}

function save($db_pre) {
    
    $postid = SafeRequest(getPGC('id'), 0);
    $mulu = SafeRequest(getPGC('mulu'), 0);
    $biaoqianzhui = SafeRequest(getPGC('biaoqianzhui'), 0);
    $name = SafeRequest(getPGC('name'), 0);
    $modulename = SafeRequest(getPGC('modulename'), 0);
    $rpctype = SafeRequest(getPGC('rpctype'), 0);
    $rpcurl = SafeRequest(getPGC('rpcurl'), 0);
    $rpcmainkey = SafeRequest(getPGC('rpcmainkey'), 0);
    $rpchost = SafeRequest(getPGC('rpchost'), 0);
    $rpcprivatekey = SafeRequest(getPGC('rpcprivatekey'), 0);
    $rpcmoduleid = SafeRequest(getPGC('rpcmoduleid'), 0);
    // 检查install/install.php是否存在
?>
<div class="widget-box">
	<div class="widget-content nopadding">
<?php 
    if (is_dir(ZH_PATH . DS . $mulu) == false) {
        echo "<script>alert('" . $mulu . "不是一个有效的目录');history.back();</script>";
        exit;
    }

    if (is_dir(ZH_PATH . DS . $mulu . DS . "install") == false) {
        echo "<script>alert('" . $mulu . "中安装目录不存在');history.back();</script>";
        exit;
    }

    if (is_file(ZH_PATH . DS . $mulu . DS . "install" . DS . "install.php") == false) {
        echo "<script>alert('" . $name . "安装文件不存在');history.back();</script>";
        exit;
    }

    if (is_file(ZH_PATH . DS . $mulu . DS . "install" . DS . "flock.php") == true) {
        echo "<script>alert('" . $name . "已经安装过了');history.back();</script>";
        exit;
    }

    echo '<iframe width="100%" height="400" frameborder="0" name="i" src="/' . $mulu . '/install/install.php?qianzui=' . $biaoqianzhui . '&mulu=' . $mulu . '&name=' . $name . '&modulename=' . $modulename . '&rpctype=' . $rpctype . '&rpcurl=' . $rpcurl . '&rpcmainkey=' . $rpcmainkey . '&rpchost=' . $rpchost . '&rpcprivatekey=' . $rpcprivatekey . '&rpcmoduleid=' . $rpcmoduleid . '"></iframe>';
    //echo '<iframe width="100%" frameborder="0" name="i" src="/adsense/install/install.php?qianzui=ad_&mulu=adsense&name=广告管理&modulename=&rpctype=本地&rpcurl=&rpcmainkey=&rpchost=&rpcprivatekey=&rpcmoduleid=1"></iframe>';
    //exit;
}

function butable($db_pre)
{
    $postid = SafeRequest(getPGC('postid'), 0);
    if (($postid != "") && ($postid != 0)) {
        $bind = array(
            "id" => $postid
        );
        $data = D("" . $db_pre . "module")->where($bind)->getLinkOne("*");
        $mulu = $data['mulu'];
        $biaoqianzhui = $data['biaoqianzhui'];
        $name = $data['name'];
        $modulename = $data['modulename'];
        $rpctype = $data['rpctype'];
        $rpcmainkey = $data['rpcmainkey'];
        $rpcurl = $data['rpcurl'];
        $rpchost = $data['rpchost'];
        $rpcprivatekey = $data['rpcprivatekey'];
        $rpcmoduleid = $data['rpcmoduleid'];
        echo '<iframe width="100%" height="400" frameborder="0" name="i" src="/' . $mulu . '/install/installbiao.php?qianzui=' . $biaoqianzhui . '&mulu=' . $mulu . '&name=' . $name . '&modulename=' . $modulename . '&rpctype=' . $rpctype . '&rpcurl=' . $rpcurl . '&rpcmainkey=' . $rpcmainkey . '&rpchost=' . $rpchost . '&rpcprivatekey=' . $rpcprivatekey . '&rpcmoduleid=' . $rpcmoduleid . '&moduleid=' . $postid . '"></iframe>';
    } 
    else {
        echo "error";
        exit;
    }
    
}

function datamain($db_pre) {
    $moduleid = SafeRequest(getPGC('moduleid'), 0);
    $mulu = SafeRequest(getPGC('mulu'), 0);
    
    ?>
                        <div class="widget-box">
                            <div class="widget-content nopadding">
                                RPC服务地址：<font color=red>http://<?php echo $_SERVER['SERVER_NAME'];?>/<?php echo $mulu; ?>/rpc/</font>，数据传输的时候使用“申请站点网址+模块id+私钥+时间戳”并md5加密生成密钥值进行校验
                            </div>
                            <div class="widget-content nopadding">
                                
                                <button class="btn btn-primary" onClick="location = '?atcion=dataadd&moduleid=<?php echo $moduleid; ?>&mulu=<?php echo $mulu;?>';">新增站点</button>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>申请站点</th>
                                            <th>模块id</th>
                                            <th>生成的唯一站点值</th>
                                            <th>私钥</th>
                                            <th>申请站点网址</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    $where="moduleid='".$moduleid."'";
    $rs = D("" . $db_pre . "modulerpc")->where($where)->order('id desc')->getLinkAll("id,moduleid,name,mainkey,mainurl,privatekey", true);
    $datas = $rs['datas'];
    $rows = $rs['rows'];
    for ($j = 0; $j < $rows; $j ++) {
        $data = $datas[$j];
        ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['name']; ?></td>
                                                <td><?php echo $data['moduleid']; ?></td>
                                                <td><?php echo $data['mainkey']; ?></td>
                                                <td><?php echo $data['privatekey']; ?></td>
                                                <td><?php echo $data['mainurl']; ?></td>
                                                <td class="taskOptions">
                                                    <a href="?atcion=datalist&postid=<?php echo $data['id']; ?>&moduleid=<?php echo $moduleid; ?>&mulu=<?php echo $mulu;?>">接口列表</a> | 
                                                    <a href="?atcion=datadel&postid=<?php echo $data['id']; ?>&moduleid=<?php echo $moduleid; ?>&mulu=<?php echo $mulu;?>"
                                                       onclick="{
                                                                                                    if (confirm('确定删除吗?')) {
                                                                                                        return true;
                                                                                                    }
                                                                                                    return false;
                                                                                                }">删除</a>
                                                </td>
                                            </tr>
                                                       <?php
                                                   }
                                                   ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

    <?php
}

function dataadd($db_pre) {
    $moduleid = SafeRequest(getPGC('moduleid'), 0);
    $mulu = SafeRequest(getPGC('mulu'), 0);
    $name = "";
    $info = "";
    ?>
                        <form action="?atcion=datasave&moduleid=<?php echo $moduleid; ?>&mulu=<?php echo $mulu;?>" method="post" id="form1" name="form1" class="form-horizontal">
                            <div class="widget-box">
                                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                                    <h5>新增rpc数据站点管理</h5>
                                </div>
                                <div class="widget-content">
                                    <!-- 表单 -->
                                    <div class="control-group">
                                        <label class="control-label">rpc数据站点:</label>
                                        <div class="controls">
                                            <input type="text" name="name" class="span" placeholder="" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">rpc数据站点域名:</label>
                                        <div class="controls">
                                            <input type="text" name="mainurl" class="span" placeholder="" value="" />
                                        </div>
                                    </div>
        							<div class="control-group">
                                        <label class="control-label">模块私钥:</label>
                                        <div class="controls">
                                            <input type="text" name="privatekey" class="span" placeholder="" value="" />
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">添加</button>
                                    </div>
                                    <!-- 表单 -->
                                </div>
                            </div>
                        </form>
    <?php
}

function datasave($db_pre) {
    $moduleid = SafeRequest(getPGC('moduleid'), 0);
    $name = SafeRequest(getPGC('name'), 0);
    $mainurl = SafeRequest(getPGC('mainurl'), 0);
    $mulu = SafeRequest(getPGC('mulu'), 0);
    $privatekey = SafeRequest(getPGC('privatekey'), 0);
    //判断这个站点是否已经安装了一个，如果已经安装了则把已经添加的编号添加进去生成新的id值
    
    
    //根据url生成一个md5的key，这个key保存在服务器
    //生成规则为，1先用crypt('something'.guid(),'ZHMVC')产生一个唯一的key，然后对可以进行md5
    $secretkey=md5(crypt($mainurl.guid(),'ZHMVC'));
    //2mainkey系统自带的函数进行加密
    $mainkey=GyEncrypt($mainurl.$privatekey, 'E', $secretkey); 
    
    if ($name == "") {
        //echo $plusattribute;
        echo "<script>alert('没有添加插件属性');history.back();</script>";
        exit;
    }

    $bind = array("moduleid" => $moduleid, "name" => $name, "mainkey" => $mainkey, "mainurl" => $mainurl, "secretkey" => $secretkey, "privatekey" => $privatekey);
    D($db_pre . "modulerpc")->LinkInsert($bind);
    echo "<script>alert('更新成功');window.location.href='admin_module.php?atcion=datamain&moduleid=" . $moduleid . "&mulu=".$mulu."';</script>";
}

function datalist($db_pre) {
    $moduleid = SafeRequest(getPGC('id'), 0);
    ?>
                        <div class="widget-box">

                            <div class="widget-content nopadding">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>申请站点</th>
                                            <th>密钥</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    $rs = D("" . $db_pre . "modulerpc")->order('id desc')->getLinkAll("id,moduleid,name,mainkey", true);
    $datas = $rs['datas'];
    $rows = $rs['rows'];
    for ($j = 0; $j < $rows; $j ++) {
        $data = $datas[$j];
        ?>
                                            <tr>
                                                <td><?php echo $data['id']; ?></td>
                                                <td><?php echo $data['name']; ?></td>
                                                <td><?php echo $data['mainkey']; ?></td>
                                                <td class="taskOptions">
                                                    <a href="?atcion=datalist&postid=<?php echo $data['id']; ?>">接口列表</a> | 
                                                    <a href="?atcion=del&id=<?php echo $data['id']; ?>"
                                                       onclick="{
                                                                                                    if (confirm('确定删除吗?')) {
                                                                                                        return true;
                                                                                                    }
                                                                                                    return false;
                                                                                                }">删除</a>
                                                </td>
                                            </tr>
        <?php
    }
    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

    <?php
}

function datadel($db_pre) {
	$moduleid = SafeRequest(getPGC('moduleid'), 0);
    $mulu = SafeRequest(getPGC('mulu'), 0);
    $postid = SafeRequest(getPGC("postid"), 0);
    if (($postid != "") && ($postid != "0")) {
        $bind = array(
            "id" => $postid
        );
        D("" . $db_pre . "modulerpc")->where($bind)->LinkDelete($bind);
        echo "<script>alert('更新成功');window.location.href='admin_module.php?atcion=datamain&moduleid=" . $moduleid . "&mulu=".$mulu."';</script>";
    } else {
        echo "传参错误";
    }
}

function bindadd($db_pre) {
    $postid = SafeRequest(getPGC('postid'), 0);

    if (($postid != "") && ($postid != 0)) {
        // $sql="select mulu,`biaoqianzhui`,name,modulename from ".$db_pre."module where id=:id";
        $bind = array(
            "id" => $postid
        );
        $rs1 = D($db_pre . "module")->where($bind)->getLinkOne("`mulu`,`biaoqianzhui`,`name`,`modulename`", true);
        //print_r($rs1);
        // $data=$conn->getOne($sql,$bind);
        $name = $rs1['datas']['name'];
    } else {
        $mulu = "";
        $biaoqianzhui = "";
        $name = "";
        $modulename = "";
    }

    // 获取已经绑定的模型
    // $sql="select modelsid as aid,islist from ".$db_pre."mbindm where moduleid=:moduleid";
    $bind = array(
        "moduleid" => $postid
    );
    $rs = D($db_pre . "mbindm")->where($bind)->getLinkOne("modelsid as aid,islist", true);
    $data = $rs['datas'];
    $rows = $rs['rows'];
    // $data=$conn->getOne($sql,$bind);
    // $rows=$conn->getRowCount();
    $options = "";
    $islist = "0";
    if ($rows > 0) {
        $options = $data['aid'];
        $islist = $data['islist'];
    }
    ?>
                        <form action="?atcion=bindsave&postid=<?php echo $postid; ?>"
                              id="form1" name="form1" method="post" class="form-horizontal">
                            <div class="widget-box">
                                <div class="widget-title">
                                    <span class="icon"> <i class="icon-align-justify"></i>
                                    </span>
                                    <h5>模块管理－－绑定模型</h5>
                                </div>
                                <div class="widget-content">
                                    <!-- 表单 -->
                                    <div class="control-group">
                                        <label class="control-label">模块名称:</label>
                                        <div class="controls">
                                            <input type="text" name="name" class="span" placeholder=""
                                                   value="<?php echo $name; ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">绑定类型</label>
                                        <div class="controls">
                                            <label> <input type="radio" name="islist" value="0"
    <?php
    if ($islist == "0") {
        echo "checked";
    }
    ?> /> 主模块
                                            </label> <label> <input type="radio" name="islist" value="1"
    <?php
    if ($islist == "1") {
        echo "checked";
    }
    ?> /> 子模块
                                            </label>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">可用模型选项:</label>
                                        <div class="controls">
                                            <select name="from[]" id="multiselect" class="form-control"
                                                    size="8" multiple="multiple">
                                                <?php
                                                if ($options != "") {
                                                    $where = "  id not in (" . $options . ")  ORDER BY id DESC";
                                                    $order = "id DESC";
                                                } else {
                                                    $where = "";
                                                    $order = "displayorder,id DESC";
                                                }
                                                $rs2 = D($db_pre . "models")->where($where)
                                                        ->order($order)
                                                        ->getLinkAll("*", true);
                                                $datas2 = $rs2['datas'];
                                                $row2 = $rs2['rows'];
                                                // $datas2 = $conn->getAll($sql2);
                                                // $row2 = count($datas2);
                                                for ($j = 0; $j < $row2; $j ++) {
                                                    $data2 = $datas2[$j];
                                                    ?>
                                                    <option
                                                        value="<?php echo $data2['id']; ?>"><?php echo $data2['name']; ?></option>
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
                                        <label class="control-label">已选模型选项:</label>
                                        <div class="controls">
                                            <select name="options[]" id="multiselect_to"
                                                    class="form-control" multiple="multiple">
    <?php
    if ($options != "") {
        // $sql1 = "SELECT id,name FROM `" . $db_pre . "models` WHERE id in(" . $options . ") ORDER BY ";
        $where1 = "id in(" . $options . ")";
        $order1 = "displayorder,id DESC";
        $rs1 = D($db_pre . "models")->where($where1)
                ->order($order1)
                ->getLinkAll("*", true);
        $datas1 = $rs1['datas'];
        $row1 = $rs1['rows'];
        for ($i = 0; $i < $row1; $i ++) {
            $data1 = $datas1[$i];
            ?>
                                                        <option value="<?php echo $data1['id']; ?>">
                                                                <?php echo $data1['name']; ?>
                                                        </option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success">添加</button>
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

function bindsave($db_pre) {
    $postid = SafeRequest(getPGC('postid'), 0);
    $islist = SafeRequest(getPGC('islist'), 0);
    $options = getPGC("options");
    $option = implode(',', $options);

    // $sql="select * from " . $db_pre . "mbindm where moduleid=:moduleid";
    $bind = array(
        ":moduleid" => $postid
    );
    $rs = D($db_pre . "mbindm")->where($bind)->getLinkOne("*", true);
    $data = $rs['datas'];
    $rows = $rs['rows'];
    if ($rows > 0) {
        $wheremap = array();
        $updatedata = array();
        $wheremap['moduleid'] = $postid;
        $updatedata["modelsid"] = $option;
        $updatedata["islist"] = $islist;
        D($db_pre . "mbindm")->where($wheremap)->LinkUpdate($updatedata);
    } else {
        $bind = array(
            ":moduleid" => $postid,
            ":modelsid" => $option,
            ":islist" => $islist
        );

        D($db_pre . "mbindm")->LinkInsert($bind);
    }
    echo "<script>alert('更新成功');window.location.href='admin_module.php';</script>";
}

function del($db_pre, $db_database) {
    $postid = SafeRequest(getPGC('id'), 0);

    if (($postid != "") && ($postid != "0")) {
        $bind = array(
            "id" => $postid
        );
        $rs = D($db_pre . "module")->where($bind)->getLinkOne("id,biaoqianzhui,mulu,name", true);
        $data = $rs['datas'];
        $rows = $rs['rows'];

        $biaoqianzhui = $data['biaoqianzhui'];
        print_r($biaoqianzhui);
        $mulu = $data['mulu'];
        $rs1 = D('information_schema.tables')->where("table_name LIKE '" . $biaoqianzhui . "_%' and TABLE_SCHEMA='" . $db_database . "'")->getLinkAll("CONCAT( 'drop table ', table_name, ';' ) as deltables", true);
        $datas1 = $rs1['datas'];
        $rows1 = $rs1['rows'];
        //print_r($datas1);
        for ($i = 0; $i < $rows1; $i ++) {
            $data = $datas1[$i];
            //print_r($data);
            D('information_schema.tables')->SqlUpdate($data['deltables']);
        }

        echo "删除数据表。<br>";

        // 删除目录中的config.php和flock.php文件
        $path = ZH_PATH . DS . $mulu;
        // 要删除的文件夹
        // 如果php文件不是ANSI,而是UTF-8模式,而且要删除的文件夹中包含汉字字符的话,调用函数前需要转码
        $path = iconv('utf-8', 'gb2312', $path);
        // 删除config.php
        $file1 = $path . DS . "config.php";
        $file2 = $path . DS . "install" . DS . "flock.php";
        $file3 = $path . DS . "b" . DS . "ClassConfig.php";
        $file4 = $path . DS . "map.php";
        echo "删除指定文件1." . $file1 . "。<br>";
        // my_del($path);
        @unlink($file1);
        echo "删除指定文件2" . $file2 . "。<br>";
        @unlink($file2);
        echo "删除指定文件3" . $file3 . "。<br>";
        @unlink($file3);
        echo "删除指定文件4" . $file4 . "。<br>";
        @unlink($file4);
        echo "删除文件结束。<br>";
        // 删除后台菜单
        // $sql="delete from ".$db_pre."admin_menu where menuname='".$biaoqianzhui."'";
        $deldata["menuname"] = $biaoqianzhui;
        D($db_pre . "admin_menu")->where($deldata)->LinkDelete($deldata);
        echo "删除后台菜单。<br>";
        // $sql="delete from ".$db_pre."module where id=:id";
        $bind = array(
            "id" => $postid
        );
        D($db_pre . "module")->where($bind)->LinkDelete($bind);
        echo "删除模块关联表。<br>";
        // $sql="delete from ".$db_pre."moduletable where moduleid=:id";
        $bindmoduleid = array(
            "moduleid" => $postid
        );
        // echo $sql;
        D($db_pre . "moduletable")->where($bindmoduleid)->LinkDelete($bindmoduleid);
        D($db_pre . "moduletablesub")->where($bindmoduleid)->LinkDelete($bindmoduleid);
        D($db_pre . "modulerpc")->where($bindmoduleid)->LinkDelete($bindmoduleid);
        D($db_pre . "modulerpclist")->where($bindmoduleid)->LinkDelete($bindmoduleid);
        echo "<script>alert('模块已经删除。');window.location.href='admin_module.php';</script>";
    }
}

function my_del($path) {
    if (is_dir($path)) {
        $file_list = scandir($path);
        foreach ($file_list as $file) {
            if ($file != '.' && $file != '..') {
                my_del($path . '/' . $file);
            }
        }
        @rmdir($path);
        // 这种方法不用判断文件夹是否为空, 因为不管开始时文件夹是否为空,到达这里的时候,都是空的
    } else {
        @unlink($path);
        // 这两个地方最好还是要用@屏蔽一下warning错误,看着闹心
    }
    
}

function datainfolist($db_pre) {
    $moduleid = SafeRequest(getPGC('moduleid'), 0);
    $subc = new \ZHMVC\D\ModuleTableSub();
    $datas=$subc->getAllModuleForMid($moduleid);
   
    ?>
                        <div class="widget-box">

                            <div class="widget-content nopadding">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>类名</th>
                                            <th>方法名</th>
                                            <th>文件名</th>
                                            <th>说明</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    $rows = count($datas);
    for ($j = 0; $j < $rows; $j ++) {
        $data = $datas[$j];
        ?>
                                            <tr>
                                                <td><?php echo $data['classname']; ?></td>
                                                <td><?php echo $data['subname']; ?></td>
                                                <td><?php echo $data['filename']; ?></td>
                                                <td><?php echo $data['mapcontent']; ?></td>
                                            </tr>
        <?php
    }
    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

    <?php
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