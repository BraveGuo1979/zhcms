<?php
namespace ZHMVC\D;

class Models extends \ZHMVC\D\DataBase
{
    
    public function setTableName()
    {
        $this->_tables=$this->_pre."models";
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
                $sql="select count(*) as num from ".$this->_pre."models";
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
                    
                $sql="select count(*) as num from ".$this->_pre."models where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from ".$this->_pre."models where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from ".$this->_pre."models";
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
                $sql="select * from ".$this->_pre."models";
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
                $sql="select * from ".$this->_pre."models where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."models where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."models";
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
                $sql="select * from ".$this->_pre."models order by id desc LIMIT ".$limit."";
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
                $sql="select * from ".$this->_pre."models where ".$tempS." order by id desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."models where ".$parameter." order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."models order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
    
    public function getOne($postid)
    {
        $sql="select * from ".$this->_pre."models where id=:id";
        $bind=array(":id"=>$postid);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function add($name,$displayorder,$type,$options)
    {
        $sql="insert into ".$this->_pre."models (`name`,`displayorder`,`type`,`options`) values (:name,:displayorder,:type,:options)";
        $bind=array(":name"=>$name,":displayorder"=>$displayorder,":type"=>$type,":options"=>$options);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function update($id,$name,$displayorder,$type,$options)
    {
        $sql="update ".$this->_pre."models set `name`=:name,`displayorder`=:displayorder,`type`=:type,`options`=:options where id=:id";
        $bind=array(":name"=>$name,":displayorder"=>$displayorder,":type"=>$type,":options"=>$options,":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
    
    public function delete($id)
    {
        $sql="delete from ".$this->_pre."models where id=:id";
        $bind=array(":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
    
    public function getLastId(){
        return $this->_lastid;
    }
}