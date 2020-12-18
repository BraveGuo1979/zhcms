<?php
namespace ARTICLE\D;
class Articlecontent extends \ZHMVC\D\DataBase
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
                $sql="select count(*) as num from art_articlecontent";
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
            
                $sql="select count(*) as num from art_articlecontent where ".$tempS;
                $data=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select count(*) as num from art_articlecontent where ".$parameter;
            $data=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select count(*) as num from art_articlecontent";
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
                $sql="select * from art_articlecontent";
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
                $sql="select * from art_articlecontent where ".$tempS;
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from art_articlecontent where ".$parameter."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from art_articlecontent";
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
                $sql="select * from art_articlecontent order by id desc LIMIT ".$limit."";
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
                $sql="select * from art_articlecontent where ".$tempS." order by id desc LIMIT ".$limit."";
                $datas=$this->_db->getAll($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from art_articlecontent where ".$parameter." order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        else
        {
            $sql="select * from art_articlecontent order by id desc LIMIT ".$limit."";
            $datas=$this->_db->getAll($sql);
        }
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function getOne($postid)
    {
        $sql="select * from art_articlecontent where id=:id";
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
                $sql="select * from art_articlecontent";
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
                $sql="select * from art_articlecontent where ".$tempS;
                $datas=$this->_db->getOne($sql,$bind);
            }
        }
        elseif($parameter!="")
        {
            $sql="select * from art_articlecontent where ".$parameter."";
            $datas=$this->_db->getOne($sql);
        }
        else
        {
            $sql="select * from art_articlecontent";
            $datas=$this->_db->getOne($sql);
        }
   
        $this->_rows=$this->_db->getRowCount();
        return $datas;
    }
            
    public function add($mainkey,$columnid,$columnname,$title,$content,$keyword,$fromname,$viewnum,$byte,$tcolor,$author,$img,$isimg,$isdel,$ispass,$istop,$contentlistid,$adminid,$cjroot)
    {
        $sql="insert into art_articlecontent (`mainkey`,`columnid`,`columnname`,`title`,`addtime`,`content`,`keyword`,`fromname`,`viewnum`,`byte`,`tcolor`,`author`,`img`,`isimg`,`isdel`,`ispass`,`istop`,`contentlistid`,`adminid`,`cjroot`) values (:mainkey,:columnid,:columnname,:title,now(),:content,:keyword,:fromname,:viewnum,:byte,:tcolor,:author,:img,:isimg,:isdel,:ispass,:istop,:contentlistid,:adminid,:cjroot)";
        $bind=array(":mainkey"=>$mainkey,":columnid"=>$columnid,":columnname"=>$columnname,":title"=>$title,":content"=>$content,":keyword"=>$keyword,":fromname"=>$fromname,":viewnum"=>$viewnum,":byte"=>$byte,":tcolor"=>$tcolor,":author"=>$author,":img"=>$img,":isimg"=>$isimg,":isdel"=>$isdel,":ispass"=>$ispass,":istop"=>$istop,":contentlistid"=>$contentlistid,":adminid"=>$adminid,":cjroot"=>$cjroot);
        $this->_db->update($sql,$bind);
        $this->_lastid=$this->_db->getLastId();
        return $this->_lastid;
    }
    
    public function update($id,$mainkey,$columnid,$columnname,$title,$content,$keyword,$fromname,$viewnum,$byte,$tcolor,$author,$img,$isimg,$isdel,$ispass,$istop,$contentlistid)
    {
        $sql="update art_articlecontent set `mainkey`=:mainkey,`columnid`=:columnid,`columnname`=:columnname,`title`=:title,`content`=:content,`keyword`=:keyword,`fromname`=:fromname,`viewnum`=:viewnum,`byte`=:byte,`tcolor`=:tcolor,`author`=:author,`img`=:img,`isimg`=:isimg,`isdel`=:isdel,`ispass`=:ispass,`istop`=:istop,`contentlistid`=:contentlistid where id=:id";
        $bind=array(":mainkey"=>$mainkey,":columnid"=>$columnid,":columnname"=>$columnname,":title"=>$title,":content"=>$content,":keyword"=>$keyword,":fromname"=>$fromname,":viewnum"=>$viewnum,":byte"=>$byte,":tcolor"=>$tcolor,":author"=>$author,":img"=>$img,":isimg"=>$isimg,":isdel"=>$isdel,":ispass"=>$ispass,":istop"=>$istop,":contentlistid"=>$contentlistid,":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
            
    public function delete($id)
    {
        $sql="delete from art_articlecontent where id=:id";
        $bind=array(":id"=>$id);
        $this->_db->update($sql,$bind);
        return 1;
    }
            
    public function getLastId(){
        return $this->_lastid;
    }
}