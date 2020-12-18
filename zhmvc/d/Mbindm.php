<?php
namespace ZHMVC\D;

class Mbindm extends \ZHMVC\D\DataBase
{

public function setTableName()
    {
        $this->_tables=$this->_pre."mbindm";
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
                $sql="select count(*) as num from ".$this->_pre."mbindm";
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
                    
                $sql="select count(*) as num from ".$this->_pre."mbindm where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from ".$this->_pre."mbindm where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from ".$this->_pre."mbindm";
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
                $sql="select * from ".$this->_pre."mbindm";
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
                $sql="select * from ".$this->_pre."mbindm where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."mbindm where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."mbindm";
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
                $sql="select * from ".$this->_pre."mbindm order by moduleid desc LIMIT ".$limit."";
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
                $sql="select * from ".$this->_pre."mbindm where ".$tempS." order by moduleid desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."mbindm where ".$parameter." order by moduleid desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."mbindm order by moduleid desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
    
    public function getOne($moduleid)
    {
        $sql="select * from ".$this->_pre."mbindm where moduleid=:moduleid";
        $bind=array(":moduleid"=>$moduleid);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function add($moduleid,$modelsid)
    {
        $sql="insert into ".$this->_pre."mbindm (`moduleid`,`modelsid`) values (:moduleid,:modelsid)";
        $bind=array(":moduleid"=>$moduleid,":modelsid"=>$modelsid);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function update($moduleid,$modelsid)
    {
        $sql="update ".$this->_pre."mbindm set `modelsid`=:modelsid where moduleid=:moduleid";
        $bind=array(":moduleid"=>$moduleid,":modelsid"=>$modelsid);
        $this->_db->update($sql,$bind);
        return 1;
    }
    
    public function getLastId(){
        return $this->_lastid;
    }
}
