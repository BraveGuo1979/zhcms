<?php
namespace ARTICLE\D;
class Articlecolumn extends \ZHMVC\D\DataBase
{
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
                $sql="select count(*) as num from art_articlecolumn";
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
            
                $sql="select count(*) as num from art_articlecolumn where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from art_articlecolumn where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from art_articlecolumn";
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
                $sql="select * from art_articlecolumn";
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
                $sql="select * from art_articlecolumn where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from art_articlecolumn where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from art_articlecolumn";
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
                $sql="select * from art_articlecolumn order by id desc LIMIT ".$limit."";
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
                $sql="select * from art_articlecolumn where ".$tempS." order by id desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from art_articlecolumn where ".$parameter." order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from art_articlecolumn order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function getOne($postid)
    {
        $sql="select * from art_articlecolumn where id=:id";
        $bind=array(":id"=>$postid);
        $data=$this->_db->getOne($sql,$bind);
        $this->_rows=$this->_db->getRowCount();
        return $data;
    }
            
    public function getOne1($parameter="")
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
                $sql="select * from art_articlecolumn";
                $datas=$this->_db->getOne($sql);
            }
            else
            {
                $s_a=explode(" ", $s);
                $tempS="";
                for($j=1;$j<count($s_a);$j++)
                {
                    $tempS.=$s_a[$j]." ";
                }
                $sql="select * from art_articlecolumn where ".$tempS;
                $datas=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from art_articlecolumn where ".$parameter."";
            $datas=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select * from art_articlecolumn";
            $datas=$this->_db->getOne($sql);
        }
   
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function add($mainkey,$name,$fid,$type,$columntype,$info)
    {
        $sql="insert into art_articlecolumn (`mainkey`,`name`,`fid`,`type`,`columntype`,`info`) values (:mainkey,:name,:fid,:type,:columntype,:info)";
        $bind=array(":mainkey"=>$mainkey,":name"=>$name,":fid"=>$fid,":type"=>$type,":columntype"=>$columntype,":info"=>$info);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return $this->_lastid;
    }
            
    public function update($id,$mainkey,$name,$fid,$type,$columntype,$info)
    {
        $sql="update art_articlecolumn set `mainkey`=:mainkey,`name`=:name,`fid`=:fid,`type`=:type,`columntype`=:columntype,`info`=:info where id=:id";
        $bind=array(":mainkey"=>$mainkey,":name"=>$name,":fid"=>$fid,":type"=>$type,":columntype"=>$columntype,":info"=>$info,":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
            
    public function delete($id)
    {
        $sql="delete from art_articlecolumn where id=:id";
        $bind=array(":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
            
    public function getLastId(){
        return $this->_lastid;
    }
}