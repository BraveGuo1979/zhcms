<?php
namespace ZHMVC\D;

class Options extends \ZHMVC\D\DataBase
{
    
    public function setTableName()
    {
        $this->_tables=$this->_pre."options";
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
                $sql="select count(*) as num from ".$this->_pre."options";
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
                    
                $sql="select count(*) as num from ".$this->_pre."options where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
                
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from ".$this->_pre."options where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from ".$this->_pre."options";
            $data=$this->_db->getOne($sql);
        }
        if(empty($data)==true)
        {
            $data["num"]=0;
        }
       
        echo $sql;
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
                $sql="select * from ".$this->_pre."options";
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
                $sql="select * from ".$this->_pre."options where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
                
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."options where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."options";
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
                $sql="select * from ".$this->_pre."options order by id desc LIMIT ".$limit."";
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
                $sql="select * from ".$this->_pre."options where ".$tempS." order by id desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from ".$this->_pre."options where ".$parameter." order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from ".$this->_pre."options order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
    
    public function getOne($postid)
    {
        $sql="select * from ".$this->_pre."options where id=:id";
        $bind=array(":id"=>$postid);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
    
    public function add($classid,$displayorder,$title,$description,$identifier,$type,$rules,$available,$required,$search)
    {
        $sql="insert into ".$this->_pre."options (`classid`,`displayorder`,`title`,`description`,`identifier`,`type`,`rules`,`available`,`required`,`search`) values (:classid,:displayorder,:title,:description,:identifier,:type,:rules,:available,:required,:search)";
        //echo $sql;
        $bind=array(":classid"=>$classid,":displayorder"=>$displayorder,":title"=>$title,":description"=>$description,":identifier"=>$identifier,":type"=>$type,":rules"=>$rules,":available"=>$available,":required"=>$required,":search"=>$search);
        //print_r($bind);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return 1;
    }
    
    public function update($id,$classid,$displayorder,$title,$description,$identifier,$type,$rules,$available,$required,$search)
    {
        $sql="update ".$this->_pre."options set `classid`=:classid,`displayorder`=:displayorder,`title`=:title,`description`=:description,`identifier`=:identifier,`type`=:type,`rules`=:rules,`available`=:available,`required`=:required,`search`=:search where id=:id";
        $bind=array(":classid"=>$classid,":displayorder"=>$displayorder,":title"=>$title,":description"=>$description,":identifier"=>$identifier,":type"=>$type,":rules"=>$rules,":available"=>$available,":required"=>$required,":search"=>$search,":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
    
    public function delete($id)
    {
        $sql="delete from ".$this->_pre."options where id=:id";
        $bind=array(":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
    
    public function getLastId(){
        return $this->_lastid;
    }
}