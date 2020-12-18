<?php
namespace ARTICLE\D;
class Articlecolumnrpc extends \ZHMVC\D\DataBase
{
    public function getAllNum($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            //var_dump($tokenR);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                if(is_array($parameter)==true)
                {
                    $bind=array();
                    $s="";
                    $tempS=parent::parseSql($parameter);
                    $bind=$tempS["bind"];
                    $s=$tempS["s"];
                    if($s=="")
                    {
                        $sql="select count(*) as num from art_articlecolumn where `mainkey`='".$mainkey."'";
                        $data=$this->_db->getOne($sql);
                    }
                    else
                    {
                            //去掉第一个逻辑符号
                            $s_a=explode(" ", $s);
                            $tempS="";
                            for($j=1;$j<count($s_a);$j++)
                            {
                                $tempS.=$s_a[$j]." ";
                            }
    
                            $sql="select count(*) as num from art_articlecolumn where  `mainkey`='".$mainkey."' and ".$tempS;
                            $data=$this->_db->getOne($sql,$bind);
                    }
                }
                elseif($parameter!="")
                {
                    $sql="select count(*) as num from art_articlecolumn where  `mainkey`='".$mainkey."' and ".$parameter;
                    $data=$this->_db->getOne($sql);
                }
                else
                {
                    $sql="select count(*) as num from art_articlecolumn  where  `mainkey`='".$mainkey."'";
                    $data=$this->_db->getOne($sql);
                }
                //echo $sql;
                if(empty($data)==true)
                {
                    $data["num"]=0;
                }
                $this->_rows=$this->_db->getRowCount();
                return $data;
            }
        } else {
            return "error";
        }
    }
            
    public function getAll($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                if(is_array($parameter)==true)
                {
                    $bind=array();
                    $s="";
                    $tempS=parent::parseSql($parameter);
                    $bind=$tempS["bind"];
                    $s=$tempS["s"];
    
                    if($s=="")
                    {
                        $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."'";
                        $datas=$this->_db->getAll($sql);
                    }
                    else
                    {
                        $s_a=explode(" ", $s);
                        $tempS="";
                        for($j=1;$j<count($s_a);$j++)
                        {
                            $tempS.=$s_a[$j]." ";
                        }
                        $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."' and ".$tempS;
                        $datas=$this->_db->getAll($sql,$bind);
                    }
                }
                elseif($parameter!="")
                {
                    $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."' ".$parameter."";
                    $datas=$this->_db->getAll($sql);
                }
                else
                {
                    $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."'";
                    $datas=$this->_db->getAll($sql);
                }
    
                $this->_rows=$this->_db->getRowCount();
                return $datas;
            }
        } else {
            return "error";
        }
    }
    
    public function getPages($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
    
            if($tokenR==false)
            {
                return "error";
            }
            else
            {

                if(count($map)==7)
                {
                    $limit=$map["parameter"];
                    $parameter="";
                }
                else 
                {

                    $parameter=$map["parameter"];
                    $limit=$map["limit"];
                }
                if(is_array($parameter)==true)
                {
                    $bind=array();
                    $s="";
                    $tempS=parent::parseSql($parameter);
                    $bind=$tempS["bind"];
                    $s=$tempS["s"];
                    if($s=="")
                    {
                        $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."' order by id desc LIMIT ".$limit."";
                        $datas=$this->_db->getAll($sql);
                    }
                    else
                    {
                        $s_a=explode(" ", $s);
                        $tempS="";
                        for($j=1;$j<count($s_a);$j++)
                        {
                            $tempS.=$s_a[$j]." ";
                        }
                        $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."' and ".$tempS." order by id desc LIMIT ".$limit."";
                        $datas=$this->_db->getAll($sql,$bind);
                    }
                }
                elseif($parameter!="")
                {
                    $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."' and ".$parameter." order by id desc LIMIT ".$limit."";
                    $datas=$this->_db->getAll($sql);
                }
                else
                {
                    $sql="select * from art_articlecolumn where  `mainkey`='".$mainkey."' order by id desc LIMIT ".$limit."";
                    $datas=$this->_db->getAll($sql);
                }
                $this->_rows=$this->_db->getRowCount();
                return $datas;
            }
        } else {
            return "error";
        }
    }
            
    public function getOne($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
    
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $postid=$mapnew["parameter"];
                $sql="select * from art_articlecolumn where `mainkey`='".$mainkey."' and id=:id";
                $bind=array(":id"=>$postid);
                $data=$this->_db->getOne($sql,$bind);
                $this->_rows=$this->_db->getRowCount();
                return $data;
            }
        } else {
            return "error";
        }
    }
            
    public function add($map)
    {
        if(is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
    
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                $sql="insert into art_articlecolumn (`mainkey`,`name`,`fid`,`type`,`columntype`,`info`) values (:mainkey,:name,:fid,:type,:columntype,:info)";
                $bind=array(":mainkey"=>$mainkey,":name"=>$parameter["name"],":fid"=>$parameter["fid"],":type"=>$parameter["type"],":columntype"=>$parameter["columntype"],":info"=>$parameter["info"]);
                $this->_db->update($sql,$bind);
                $this->_lastid=$this->_db->getLastId();
                return $this->_lastid;
            }
        } else {
            return "error";
        }
    }
            
    public function update($map)
    {
        if (is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $parameter=$mapnew["parameter"];
                $sql="update art_articlecolumn set 
`name`=:name,`fid`=:fid,`type`=:type,`columntype`=:columntype,`info`=:info where `mainkey`='".$mainkey."' and id=:id";
                $bind=array(":name"=>$parameter["name"],":fid"=>$parameter["fid"],":type"=>$parameter["type"],":columntype"=>$parameter["columntype"],":info"=>$parameter["info"],":id"=>$parameter["id"]);
                $this->_db->update($sql,$bind);
                return 1;
            }
        } else {
            return "error";
        }
    }
            
    public function delete($map)
    {
        if (is_array($map) == true) {
            $token=$map["token"];
            $host=$map["host"];
            $moduleid=$map["moduleid"];
            $addtime=$map["addtime"];
            $mainkey=$map["mainkey"];
            $tokenR=$this->checkToken($token,$host,$moduleid,$addtime);
            if($tokenR==false)
            {
                return "error";
            }
            else
            {
                $id=$mapnew["parameter"];
                $sql="delete from art_articlecolumn where `mainkey`='".$mainkey."' and id=:id";
                $bind=array(":id"=>$id);
                $this->_db->update($sql,$bind);
                return 1;
            }
        } else {
            return "error";
        }
    }
}       
 