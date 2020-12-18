<?php
namespace ZHMVC\D;

class Optionvalue extends \ZHMVC\D\DataBase
{

    public function setTableName()
    {
        $this->_tables=$this->_pre."optionvalue";
    }
    
    public function getTableName()
    {
        return $this->_tables;
    }
    
    public function getAllNum($parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"];
            
            if($s=="")
            {
                $sql="select count(*) as num from ".$this->_pre."optionvalue";
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
                    
                $sql="select count(*) as num from ".$this->_pre."optionvalue where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from ".$this->_pre."optionvalue where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from ".$this->_pre."optionvalue";
            $data=$this->_db->getOne($sql);
        }
        if(empty($data)==true)
        {
            $data["num"]=0;
        }
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function getAll($parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"]; 
                
            if($s=="")
            {
                $sql="select * from ".$this->_pre."optionvalue";
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
                $sql="select * from ".$this->_pre."optionvalue where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."optionvalue where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."optionvalue";
            $datas=$this->_db->getAll($sql);
        }
     
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
    
    public function getRows()
    {
        return $this->_rows;
    }
    
    public function getPages($limit,$parameter="")
    {
        if(is_array($parameter)==true)
        {
            $bind=array();
            $s="";
            $tempS=parent::parseSql($parameter);
            $bind=$tempS["bind"];
            $s=$tempS["s"];
            if($s=="")
            {
                $sql="select * from ".$this->_pre."optionvalue order by moduleid desc LIMIT ".$limit."";
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
                $sql="select * from ".$this->_pre."optionvalue where ".$tempS." order by moduleid desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."optionvalue where ".$parameter." order by moduleid desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."optionvalue order by moduleid desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
    
    public function getOne($moduleid,$modelsid,$listid,$name)
    {
        $sql="select value from ".$this->_pre."optionvalue where moduleid=:moduleid and listid=:listid and modelsid=:modelsid and name=:name";
        $bind=array(":moduleid"=>$moduleid,":modelsid"=>$modelsid,":listid"=>$listid,":name"=>$name);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function add($moduleid,$modelsid,$listid,$name,$value,$articleid)
    {
        $sql="insert into ".$this->_pre."optionvalue (`moduleid`,`modelsid`,`listid`,`name`,`value`,`articleid`) values (:moduleid,:modelsid,:listid,:name,:value,:articleid)";
        //echo $sql;
        $bind=array(":moduleid"=>$moduleid,":modelsid"=>$modelsid,":listid"=>$listid,":name"=>$name,":value"=>$value,":articleid"=>$articleid);
        //print_r($bind);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function update($moduleid,$modelsid,$listid,$name,$value,$articleid)
    {
        $sql="update ".$this->_pre."optionvalue set `value`=:value where moduleid=:moduleid and listid=:listid and modelsid=:modelsid and name=:name and articleid=:articleid";
       // echo $sql;
        $bind=array(":moduleid"=>$moduleid,":modelsid"=>$modelsid,":listid"=>$listid,":name"=>$name,":value"=>$value,":articleid"=>$articleid);
        //print_r($bind);
        $this->_db->update($sql,$bind);
        return 1;
    }
    
    public function getLastId(){
        return $this->_lastid;
    }
}
