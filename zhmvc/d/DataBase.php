<?php
namespace ZHMVC\D;

use PDO;

class DataBase
{

    public $_table;
    // 数据库表名
    public $_db;
    // 数据库连接
    public $_rows;
    //
    public $_lastid;

    public $_field;

    public $_where;

    public $_order;

    public $_limit;

    private $_bind;
    
    public $_pre;
    
    public $_iv;
    
    public $_key;
    

    public function __construct($table = "")
    {
        $c = new \ZHCONFIG\ZhConfig();
        $this->setPre($c->getDbPre());
        $this->_db = new \ZHMVC\D\ZhPdo();
        $this->_db->setDbDsn("mysql:host=" . $c->getHost() . ";dbname=" . $c->getDatabase() . "");
        $this->_db->setDbUsername($c->getUser());
        $this->_db->setDbPassword($c->getPassWord());
        $this->_db->setDbOptions(array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $c->getCharset() . '',
            PDO::ATTR_PERSISTENT => true
        ));
        $this->_db->setDebugMode(false);
        $this->_db->connect();
        $this->_table = $table;
        
        $this->_bind = "";
        $this->_field = "";
        $this->_where = "";
        $this->_order = "";
        $this->_limit = "";
        $this->_iv = "1234567890123412";
        $this->_key = "20181122eggplant";
    }

    public function __destruct()
    {}

    public function getSqlOne($sql, $parameter = "")
    {
        // $sql="select * from ".$this->_pre."order_info where id=:id";
        if (is_array($parameter) == true) {
            $bind = array();
            $s = "";
            $pnum = count($parameter);
            foreach ($parameter as $k => $v) {
                if ($v == "") {} else {
                    $s .= " and " . $k . "=:$k";
                    $bind[":" . $k] = $v;
                }
            }
            $tnum = substr_count($sql, ":");
            if ($pnum !== $tnum) {
                echo "变量和sql中绑定的参数不符，请检查";
                exit();
            }
            if ($s == "") {
                $data = $this->_db->getOne($sql);
            } else {
                $data = $this->_db->getOne($sql, $bind);
            }
        } elseif ($parameter != "") {
            $tnum = substr_count($sql, ":");
            if ($tnum != 1) {
                echo "变量和sql中绑定的参数不符，请检查";
                exit();
            }
            $bind = array(
                ":id" => $parameter
            );
            $data = $this->_db->getOne($sql, $bind);
        } else {
            $data = $this->_db->getOne($sql);
        }
        $this->_rows = $this->_db->getRowCount();
        return $data;
    }

    public function getSqlAllNum($sql, $parameter = "")
    {
        if (is_array($parameter) == true) {
            $bind = array();
            $s = "";
            $pnum = count($parameter);
            foreach ($parameter as $k => $v) {
                if ($v == "") {} else {
                    $s .= " and " . $k . "=:$k";
                    $bind[":" . $k] = $v;
                }
            }
            $tnum = substr_count($sql, ":");
            if ($pnum !== $tnum) {
                echo "变量和sql中绑定的参数不符，请检查";
                exit();
            }
            if ($s == "") {
                $datas = $this->_db->getOne($sql);
            } else {
                
                $datas = $this->_db->getOne($sql, $bind);
            }
        } elseif ($parameter != "") {
            $tnum = substr_count($sql, ":");
            if ($tnum != 1) {
                echo "变量和sql中绑定的参数不符，请检查";
                exit();
            }
            $bind = array(
                ":id" => $parameter
            );
            $datas = $this->_db->getOne($sql, $bind);
        } else {
            $datas = $this->_db->getOne($sql);
        }
        if (empty($datas) == true) {
            $datas['num'] = 0;
        }
        $this->_rows = $this->_db->getRowCount();
        return $datas;
    }

    public function getSqlAll($sql, $parameter = "")
    {
        if (is_array($parameter) == true) {
            $bind = array();
            $s = "";
            $pnum = count($parameter);
            foreach ($parameter as $k => $v) {
                if ($v == "") {} else {
                    $s .= " and " . $k . "=:$k";
                    $bind[":" . $k] = $v;
                }
            }
            $tnum = substr_count($sql, ":");
            if ($pnum !== $tnum) {
                echo "变量和sql中绑定的参数不符，请检查";
                exit();
            }
            if ($s == "") {
                $datas = $this->_db->getAll($sql);
            } else {
                $datas = $this->_db->getAll($sql, $bind);
            }
        } elseif ($parameter != "") {
            $tnum = substr_count($sql, ":");
            if ($tnum != 1) {
                echo "变量和sql中绑定的参数不符，请检查";
                exit();
            }
            $bind = array(
                ":id" => $parameter
            );
            $datas = $this->_db->getAll($sql, $bind);
        } else {
            $datas = $this->_db->getAll($sql);
        }
        
        $this->_rows = $this->_db->getRowCount();
        return $datas;
    }
    public function getRowCount() {
        return $this->_rows;
    }

    protected function parseSql($parameter)
    {
        $bind = array();
        $s = "";
        $returnS = array();
        $returnS['bind'] = "";
        $returnS['s'] = "";
        
        if (empty($parameter) == true) {
            $returnS['bind'] = $bind;
            $returnS['s'] = $s;
        } else {
            $keyp = array_keys($parameter);
            
            for ($i = 0; $i < count($keyp); $i ++) {
                
                if ($parameter[$keyp[$i]] === "") {} else {
                    
                    if (is_array($parameter[$keyp[$i]]) == true) {
                        // 判断是否是区间查询
                        if (is_array($parameter[$keyp[$i]]['region']) == true) {
                            // 是区间查询
                            
                            // 左区间
                            $myfunctionS = "";
                            $myfunctionE = "(";
                            $parameter_keys = $keyp[$i];
                            $parameter_bindkeys = $parameter[$keyp[$i]]['region']['left']['name'];
                            $parameter_values = $parameter[$keyp[$i]]['region']['left']['value'];
                            if (empty($parameter[$keyp[$i]]['region']['left']['function']) == true) {
                                $myfunctionS = "";
                                $myfunctionE = "";
                            } else {
                                $myfunctionS = $parameter[$keyp[$i]]['region']['left']['function'] . "(";
                                $myfunctionE = ")";
                            }
                            // 获取查询逻辑
                            if (empty($parameter[$keyp[$i]]['region']['left']['logic']) == true) {
                                // 默认的条件是and
                                $s .= "and " . $myfunctionS . $parameter_keys . $myfunctionE;
                                $bind[":" . $parameter_bindkeys] = $parameter_values;
                            } else {
                                $bind[":" . $parameter_bindkeys] = $parameter_values;
                                $s .= $parameter[$keyp[$i]]['region']['left']['logic'] . " " . $myfunctionS . $parameter_keys . $myfunctionE;
                            }
                            // 获取查询条件
                            if (empty($parameter[$keyp[$i]]['region']['left']['condition']) == true) {
                                // 默认的条件是=
                                $s .= "=" . $myfunctionS . ":" . $parameter_bindkeys . $myfunctionE . " ";
                                $bind[":" . $parameter_bindkeys] = $parameter_values;
                            } else {
                                if (array_key_exists(":" . $keyp[$i], $bind) == false) {
                                    $bind[":" . $parameter_bindkeys] = $parameter_values;
                                }
                                
                                $s .= $parameter[$keyp[$i]]['region']['left']['condition'] . $myfunctionS . ":" . $parameter_bindkeys . $myfunctionE . " ";
                            }
                            
                            // 右区间
                            $myfunctionS = "";
                            $myfunctionE = "(";
                            $parameter_keys = $keyp[$i];
                            $parameter_bindkeys = $parameter[$keyp[$i]]['region']['right']['name'];
                            $parameter_values = $parameter[$keyp[$i]]['region']['right']['value'];
                            if (empty($parameter[$keyp[$i]]['region']['right']['function']) == true) {
                                $myfunctionS = "";
                                $myfunctionE = "";
                            } else {
                                $myfunctionS = $parameter[$keyp[$i]]['region']['right']['function'] . "(";
                                $myfunctionE = ")";
                            }
                            // 获取查询逻辑
                            if (empty($parameter[$keyp[$i]]['region']['right']['logic']) == true) {
                                // 默认的条件是and
                                $s .= "and " . $myfunctionS . $parameter_keys . $myfunctionE;
                                $bind[":" . $parameter_bindkeys] = $parameter_values;
                            } else {
                                $bind[":" . $parameter_bindkeys] = $parameter_values;
                                $s .= $parameter[$keyp[$i]]['region']['right']['logic'] . " " . $myfunctionS . $parameter_keys . $myfunctionE;
                            }
                            // 获取查询条件
                            if (empty($parameter[$keyp[$i]]['region']['right']['condition']) == true) {
                                // 默认的条件是=
                                $s .= "=" . $myfunctionS . ":" . $parameter_bindkeys . $myfunctionE . " ";
                                $bind[":" . $parameter_bindkeys] = $parameter_values;
                            } else {
                                if (array_key_exists(":" . $keyp[$i], $bind) == false) {
                                    $bind[":" . $parameter_bindkeys] = $parameter_values;
                                }
                                
                                $s .= $parameter[$keyp[$i]]['region']['right']['condition'] . $myfunctionS . ":" . $parameter_bindkeys . $myfunctionE . " ";
                            }
                            
                            // 是区间查询
                        } else {
                            // 不是区间查询
                            $myfunctionS = "";
                            $myfunctionE = "(";
                            $parameter_keys = $keyp[$i];
                            if (is_array($parameter[$keyp[$i]]) == true) {
                                $parameter_values = $parameter[$keyp[$i]]['value'];
                            } else {
                                $parameter_values = $parameter[$keyp[$i]];
                            }
                            if (empty($parameter[$keyp[$i]]['function']) == true) {
                                $myfunctionS = "";
                                $myfunctionE = "";
                            } else {
                                $myfunctionS = $parameter[$keyp[$i]]['function'] . "(";
                                $myfunctionE = ")";
                            }
                            // 获取查询逻辑
                            if (empty($parameter[$keyp[$i]]['logic']) == true) {
                                // 默认的条件是and
                                $s .= "and " . $myfunctionS . $parameter_keys . $myfunctionE;
                            } else {
                                $s .= $parameter[$keyp[$i]]['logic'] . " " . $myfunctionS . $parameter_keys . $myfunctionE;
                            }
                            // 获取查询条件
                            
                            if (empty($parameter[$keyp[$i]]['condition']) == true) {
                                // 默认的条件是=
                                $s .= "=" . $myfunctionS . ":" . $parameter_keys . $myfunctionE . " ";
                                $bind[":" . $parameter_keys] = $parameter_values;
                            } else {
                                if (array_key_exists(":" . $keyp[$i], $bind) == false) {
                                    if ($parameter[$keyp[$i]]['condition'] == 'like') {
                                        $bind[":" . $parameter_keys] = '%' . $parameter_values . '%';
                                    } else {
                                        $bind[":" . $parameter_keys] = $parameter_values;
                                    }
                                } else {
                                    if ($parameter[$keyp[$i]]['condition'] == 'like') {
                                        $bind[":" . $parameter_keys] = '%' . $parameter_values . '%';
                                    } else {
                                        $bind[":" . $parameter_keys] = $parameter_values;
                                    }
                                    // $bind[":".$parameter_keys]='%'.$parameter_values.'%';
                                }
                                // like模糊查询
                                $s .= " " . $parameter[$keyp[$i]]['condition'] . " " . $myfunctionS . ":" . $parameter_keys . $myfunctionE . " ";
                            }
                            // 不是区间查询
                        }
                        // 结束
                    } else {
                        // 不是区间查询
                        $myfunctionS = "";
                        $myfunctionE = "(";
                        $parameter_keys = $keyp[$i];
                        if (is_array($parameter[$keyp[$i]]) == true) {
                            $parameter_values = $parameter[$keyp[$i]]['value'];
                        } else {
                            $parameter_values = $parameter[$keyp[$i]];
                        }
                        if (empty($parameter[$keyp[$i]]['function']) == true) {
                            $myfunctionS = "";
                            $myfunctionE = "";
                        } else {
                            $myfunctionS = $parameter[$keyp[$i]]['function'] . "(";
                            $myfunctionE = ")";
                        }
                        // 获取查询逻辑
                        if (empty($parameter[$keyp[$i]]['logic']) == true) {
                            // 默认的条件是and
                            $s .= "and " . $myfunctionS . $parameter_keys . $myfunctionE;
                        } else {
                            $s .= $parameter[$keyp[$i]]['logic'] . " " . $myfunctionS . $parameter_keys . $myfunctionE;
                        }
                        // 获取查询条件
                        
                        if (empty($parameter[$keyp[$i]]['condition']) == true) {
                            // 默认的条件是=
                            $s .= "=" . $myfunctionS . ":" . $parameter_keys . $myfunctionE . " ";
                            $bind[":" . $parameter_keys] = $parameter_values;
                        } else {
                            if (array_key_exists(":" . $keyp[$i], $bind) == false) {
                                if ($parameter[$keyp[$i]]['condition'] == 'like') {
                                    $bind[":" . $parameter_keys] = '%' . $parameter_values . '%';
                                } else {
                                    $bind[":" . $parameter_keys] = $parameter_values;
                                }
                            } else {
                                if ($parameter[$keyp[$i]]['condition'] == 'like') {
                                    $bind[":" . $parameter_keys] = '%' . $parameter_values . '%';
                                } else {
                                    $bind[":" . $parameter_keys] = $parameter_values;
                                }
                                // $bind[":".$parameter_keys]='%'.$parameter_values.'%';
                            }
                            // like模糊查询
                            $s .= " " . $parameter[$keyp[$i]]['condition'] . " " . $myfunctionS . ":" . $parameter_keys . $myfunctionE . " ";
                        }
                        // 不是区间查询
                    }
                }
            }
            $returnS['bind'] = $bind;
            $returnS['s'] = $s;
        }
        return $returnS;
    }

    public function SqlUpdate($sql)
    {
//        echo $sql."<br>\n";
        $this->_db->update($sql);
        $this->_lastid = $this->_db->getLastId();
        return 1;
    }

    public function getRows()
    {
        return $this->_rows;
    }
    
    // 简易链式操作
    // 组合字段
    function field($field)
    {
        if ($field != "") {
            $this->_field = $field;
        } else {
            $this->_field = "*";
        }
        return $this;
    }
    // 组合where条件
    function where($parameter = "")
    {
        if (is_array($parameter) == true) {
            
            $bind = array();
            $s = "";
            $tempS = $this->parseSql($parameter);
            
            $bind = $tempS["bind"];
            $s = $tempS["s"];
            if ($s == "") {
                $this->_where = "";
            } else {
                $s_a = explode(" ", $s);
                $tempS = "";
                for ($j = 1; $j < count($s_a); $j ++) {
                    $tempS .= $s_a[$j] . " ";
                }
                $this->_where = "where " . $tempS;
                $this->_bind = $bind;
            }
            //var_dump($this->_where);
        } elseif ($parameter != "") {
            $this->_where = "where " . $parameter;
        } else {
            $this->_where = "";
        }
        
        return $this;
    }
    // 组合order排序条件
    function order($order)
    {
        if ($order != "") {
            $this->_order = "order by " . $order;
        } else {
            $this->_order = "";
        }
        return $this;
    }
    // 组合limit限制条数
    function limit($limit)
    {
        if ($limit != "") {
            $this->_limit = "limit " . $limit;
        } else {
            $this->_limit = "";
        }
        
        return $this;
    }
    // 组合和执行select语句
    function getLinkAll($parameter = "", $IsShowRows = false, $MainBind = '')
    {
        if ($parameter != "") {
            $sql = "select " . $parameter . " from " . $this->_table . " " . $this->_where . " " . $this->_order . " " . $this->_limit . "";
        } else {
            if ($this->_field == "") {
                $this->_field = "*";
            }
            $sql = "select " . $this->_field . " from " . $this->_table . " " . $this->_where . " " . $this->_order . " " . $this->_limit . "";
        }
        
        //echo $sql;
        
        if($MainBind)
        {
            $datas = $this->_db->getAll($sql, $MainBind);
        }
        else 
        {
            if ($this->_bind) {
                $datas = $this->_db->getAll($sql, $this->_bind);
            } else {
                $datas = $this->_db->getAll($sql);
            }
        }

        $this->_rows = $this->_db->getRowCount();
        $rdata = array(
            "rows" => $this->_db->getRowCount(),
            "datas" => $datas
        );
        if ($IsShowRows == true) {
            return $rdata;
        } else {
            return $datas;
        }
    }
    // 组合和执行insert语句
    function LinkInsert($parameter)
    { // 数据库插入操作,接收数组
        if (is_array($parameter) == true) {
            foreach ($parameter as $key => $value) {    
               $keyArr[] = "`".$key."`";
               $valueArr[] = ":".$key."";
            }  
 
            $keys = implode(",", $keyArr);
            $values = implode(",", $valueArr);  
            
            $sql = "insert into " . $this->_table . "(".$keys.") values(".$values.")"; 
            //echo $sql;
            $this->_db->update($sql, $parameter);
            $this->_lastid=$this->_db->getLastId();
            return 1;
        } else {
            echo "参数不是数组";
            exit();
        }
    }
    
    // 组合和执行delect语句
    function LinkDelete($parameter)
    {
        if (is_array($parameter) == true) {
            foreach ($parameter as $key => $value) {
                $keyArr[] = "`".$key."`";
                $valueArr[] = ":".$key."";
            }
        
            $keys = implode(",", $keyArr);
            $values = implode(",", $valueArr);
        
            $sql = "delete from " . $this->_table . " " . $this->_where . "";
            //echo $sql;
            //print_r($parameter);
            $this->_db->update($sql, $parameter);
            return 1;
        } else {
            echo "参数不是数组";
            exit();
        }
    }
    // 组合和执行updata语句
    function LinkUpdate($parameter)
    {
        if (is_array($parameter) == true) {
            $s = "";
            
            foreach ($parameter as $key => $value) {
                $s .= " " . $key . "=:" . $key . ",";
            }
            $s = substr($s, 0, strlen($s) - 1);
            $sql = "update " . $this->_table . " set " . $s . " " . $this->_where; // 修改操作 格式update
            //print_r($this->_bind);
            //print_r($parameter);
            
            $newbind = array_merge($parameter, $this->_bind);
            //print_r($newbind);
            $this->_db->update($sql, $newbind);
            //var_dump($d);
            return 1;
        } else {
            echo "参数不是数组";
            exit();
        }
    }
    // 从表中取一行数据
    function getLinkOne($parameter = "", $IsShowRows = false, $mainbind = '')
    {
        if ($parameter) {
            $sql = "select " . $parameter . " from " . $this->_table . " " . $this->_where . " " . $this->_order . "  limit 1";
        } else {
            if ($this->_field == "") {
                $this->_field = "*";
            }
            $sql = "select " . $this->_field . " from " . $this->_table . " " . $this->_where . " " . $this->_order . " limit 1";
        }
        if($mainbind)
        {
            $data = $this->_db->getOne($sql, $mainbind);
        }
        else 
        {
            //echo $sql;
            if ($this->_bind) {
                $data = $this->_db->getOne($sql, $this->_bind);
            } else {
                $data = $this->_db->getOne($sql);
            }
        }
       // echo $sql;
        //$data = $this->_db->getOne($sql);
        $this->_rows = $this->_db->getRowCount();
        $rdata = array(
            "rows" => $this->_db->getRowCount(),
            "datas" => $data
        );
        if ($IsShowRows == true) {
            return $rdata;
        } else {
            return $data;
        }
    }
    // 获取总行数
    function total()
    {
        $sql = "select count(*) as `num` from " . $this->_table . "  " . $this->_where . "";
        $data = $this->_db->getOne($sql);
        if (empty($data) == true) {
            $data["num"] = 0;
        }
        $this->_rows = $this->_db->getRowCount();
        return $data;
    }
    
    /**
     * @return $_pre
     */
    public function getPre()
    {
        return $this->_pre;
    }
    
    /**
     * @param !CodeTemplates.settercomment.paramtagcontent!
     */
    public function setPre($_pre)
    {
        $this->_pre = $_pre;
    }
    
    public function checkToken($token,$gyhost='',$moduleid='',$addtime='')
    {
        $s=TRUE;
        $sql = "select * from " . $this->_pre . "modulerpc  where mainurl='".$gyhost."' and moduleid='".$moduleid."'";
        //echo $sql;
        $data = $this->_db->getOne($sql);
        $rows = $this->_db->getRowCount();
        if($rows>0)
        {
            $privatekey=$data["privatekey"];
            $token1=md5($gyhost.$moduleid.$addtime.$privatekey);
            if($token1==$token)
            {
                $s=true;
            }
            else
            {
                $s=false;
            }
        }
        else 
        {
            $s=false;
        }
        return $s;
    }
}
