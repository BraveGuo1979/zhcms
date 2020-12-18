<?php
namespace ARTICLE\B;
class ClsFun
{
    private $data1String;
    private $data2String;
    private $data3String;
    function __construct()
    {
        $this->data1String="";
        $this->data2String="";
        $this->data3String="";
    }
    
    // 递归显示分类
    function data1arr($tree, $rootId = 0, $level = 0)
    {
        foreach ($tree as $leaf) {
            if ($leaf['fid'] == $rootId) {
                $this->data1String.="<tr>
                        <td>|" . str_repeat('----', $level) . "&nbsp;&nbsp;&nbsp;(" . $leaf['id'] . ")</td>
                        <td>" . $leaf['name'] . "</td>
                        <td>";
                if ($leaf['type'] == "2") {
                    $this->data1String.="大分类";
                } else {
                    $this->data1String.="小栏目";
                }
                $this->data1String.="</td>
                       <td>";
                if ($leaf['columntype'] == "2") {
                    $this->data1String.="单文章对应多文章";
                } elseif ($leaf['columntype'] == "1") {
                    $this->data1String.="文章列表";
                } elseif ($leaf['columntype'] == "0") {
                    $this->data1String.="单文章";
                }
                $this->data1String.="</td>
                       <td><a href=\"?atcion=add&postid=" . $leaf['id'] . "\">修改</a>&nbsp;&nbsp;&nbsp;<a href=\"?atcion=del&postid=" . $leaf['id'] . "\">删除</a></td>
                      </tr>";
                foreach ($tree as $l) {
                    if ($l['fid'] == $leaf['id']) {
                        self::data1arr($tree, $leaf['id'], $level + 1);
                        break;
                    }
                }
            }
        }
    }
    
    // 递归显示分类
    function datafuandzhuan1arr($tree, $rootId = 0, $level = 0)
    {
        foreach ($tree as $leaf) {
            if ($leaf['fid'] == $rootId) {
                $this->data1String.="<tr>
                        <td>|" . str_repeat('----', $level) . "&nbsp;&nbsp;&nbsp;(" . $leaf['id'] . ")</td>
                        <td>" . $leaf['name'] . "</td>
                       <td><a href=\"?atcion=add&postid=" . $leaf['id'] . "\">修改</a>&nbsp;&nbsp;&nbsp;<a href=\"?atcion=add&postid=" . $leaf['id'] . "\">删除</a></td>
                      </tr>";
                foreach ($tree as $l) {
                    if ($l['fid'] == $leaf['id']) {
                        self::data1arr($tree, $leaf['id'], $level + 1);
                        break;
                    }
                }
            }
        }
    }
    
    // 递归显示分类
    function data3arr($tree, $rootId = 0, $level = 0)
    {
        foreach ($tree as $leaf) {
            if ($leaf['fid'] == $rootId) {
                $this->data3String.="<tr>
                        <td>|" . str_repeat('----', $level) . "&nbsp;&nbsp;&nbsp;(" . $leaf['id'] . ")</td>
                        <td>" . $leaf['name'] . "</td>
                        <td><a href=\"?atcion=add&postid=" . $leaf['id'] . "\">修改</a>&nbsp;&nbsp;&nbsp;<a href=\"?atcion=add&postid=" . $leaf['id'] . "\">删除</a></td>
                      </tr>";
                foreach ($tree as $l) {
                    if ($l['fid'] == $leaf['id']) {
                        self::data1arr($tree, $leaf['id'], $level + 1);
                        break;
                    }
                }
            }
        }
    }
    
    // 递归显示分类
    function data2arr($tree, $rootId = 0, $level = 0, $ckfid = 0)
    {
        foreach ($tree as $leaf) {
            if ($leaf['fid'] == $rootId) {
                if ($ckfid == $leaf['id']) {
                    $this->data2String.="<option value='" . $leaf['id'] . "' selected>" . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . $leaf['name'] . "</option>";
                } else {
                   $this->data2String.="<option value='" . $leaf['id'] . "'>" . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . $leaf['name'] . "</option>";
                }
                foreach ($tree as $l) {
                    if ($l['fid'] == $leaf['id']) {
                        self::data2arr($tree, $leaf['id'], $level + 1, $ckfid);
                        break;
                    }
                }
            }
        }
    }
    
    function getData1()
    {
        return $this->data1String;
    }
    
    function getData2()
    {
        return $this->data2String;
    }
    
    function getData3()
    {
        return $this->data3String;
    }

    function __destruct()
    {
        $this->data1String="";
        $this->data2String="";
        $this->data3String="";
    }
}