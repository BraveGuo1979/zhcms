<?php
class zhsearch
{
    public $_pre;
    public $_conn;
    public $_rows;
    /*
     * 分词组件的类型，1是使用php分词使用pscws4.class.php
     * 2是使用系统安装的组件分词
     * */
    public $_fc;
    public $_content;
    public $_keyword;
    public $_wordsNew;
    public $_aid;

    public function __construct($db, $fc="1", $pre = "zhsearch_")
    {
        $this->setConn($db);
        $this->setPre($pre);
        $this->setFc($fc);
    }

    //获取总数
    public function searchNum($keyword)
    {
        //处理关键词
        $first=pinyin($keyword);
        $first=substr($first , 0 , 1);
        $sql="SELECT COUNT(DISTINCT artid) as num FROM `".$this->getPre()."tag_".strtolower($first)."` where `tags` = '".$keyword."'";
        $dbnum=$this->getConn()->getSqlAllNum($sql);
        return $dbnum['num'];
    }
    
    public function searchPage($keyword,$limit='')
    {
        //处理关键词
        $first=pinyin($keyword);
        $first=substr($first , 0 , 1);
        if($limit=="")
        {
            $sql="SELECT DISTINCT artid FROM `".$this->getPre()."tag_".strtolower($first)."` where `tags` = '".$keyword."' order by paixu desc";
        }
        else 
        {
            $sql="SELECT DISTINCT artid FROM `".$this->getPre()."tag_".strtolower($first)."` where `tags` = '".$keyword."' order by paixu desc limit ".$limit;
        }
        $datas=$this->getConn()->getSqlAll($sql);
        $this->setRows($this->getConn()->getRowCount());
        return $datas;
    }
    
    public function fenci()
    {
        if($this->getContent()=="")
        {
            return "0";
            exit;
        }
        
        $reg = "/[[:punct:]]/i";
        $this->setContent(preg_replace($reg, '', $this->getContent()));
        $reg = array(" ","　","\t","\n","\r",',','，','"','“','”','-','&',':','：', '.','。','!','！','~','、','\\','quot','；',';','?','？');
        $this->setContent(str_replace($reg, '', $this->getContent()));
        // var_dump($contents);
        //使用分词器
        if($this->getFc()=="1")
        {
            require_once(ZH_PATH . DS.'mod' . DS.'zhsearch'.DS.'pscws4.class.php');
            $so = new \PSCWS4();
            $so->set_charset('utf8');
            $so->set_dict(ZH_PATH . DS.'mod' . DS.'zhsearch'.DS.'dict.utf8.xdb');
            $so->set_rule(ZH_PATH . DS.'mod' . DS.'zhsearch'.DS.'rules.utf8.ini');
            $so->set_ignore(true);
        }
        else
        {
            $so = scws_new();
            $so->set_charset('utf8');
        }
        $so->send_text($this->getContent());
        $words = array();
        while ($tmp = $so->get_result()){
            foreach($tmp as $v){
                $words[] = $v;
            }
        }
        $keyword_a=$so->get_tops(5,'n,nr,ns,nt,nz,an,Ng');
        $so->close();
        $keyword='';
        for($k=0;$k<count($keyword_a);$k++)
        {
            $keyword.=$keyword_a[$k]['word'].',';
        }
        $keyword=substr($keyword,0,strlen($keyword)-1);
        $this->setKeyword($keyword);
        $wordsNew=array();
        //去除重复【一个字的词语不进行记录】，建索引
        foreach($words as $rows){
            if(mb_strlen($rows['word'], 'utf-8') >= 2){
                //print_r($rows);
                $wordsNew1=array();
                $wordsNew1['word'] = $rows['word'];
                $wordsNew1['idf'] = $rows['idf'];
                $wordsNew1['attr'] = $rows['attr'];
                $wordsNew[]=$wordsNew1;
            }
        }
        $wordsNew1=unique_2d_array_by_key($wordsNew,'word');
        $this->setWordsNew($wordsNew1);
    }

    public function add()
    {
        // 清除缓存
        $this->del($this->getAid());
        //总词数
        $wordnum=count($this->getWordsNew());
        //echo "id:".$wordnum."\n";
        //echo "contents:".$contents."\n";
        foreach($this->getWordsNew() as $rows1){
            //这个词在文章中出现的次数
            $wfnum=substr_count($this->getContent(),$rows1['word']);
            //获取词频
            $itf=$wfnum / $wordnum;
            //处理这个词决定进入那个索引表
            $first=pinyin($rows1['word']);
            $first=substr($first , 0 , 1);
            if($first!="")
            {
                $sqli_word="insert into `".$this->getPre()."tag_".strtolower($first)."`(tags,artid,isok,arrt,idf,cishu,itf,paixu) values('".$rows1['word']."','".$this->getAid()."','1','".$rows1['attr']."','".$rows1['idf']."','".$wfnum."','".$itf."','".(double)$rows1['idf']*(double)$itf."')";
                $this->getConn()->SqlUpdate($sqli_word);
            }
        }
        return "1";
    }

    // 删除所有文章编号为$aid的索引
    public function del($aid)
    {
        $sql_del = "delete from `" . $this->getPre() . "tag_a` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_b` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_c` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_d` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_e` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_f` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_g` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_h` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_i` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_j` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_k` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_l` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_m` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_n` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_o` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_p` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_q` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_r` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_s` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_t` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_u` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_v` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_w` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_x` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_y` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_z` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_0` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_1` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_2` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_3` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_4` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_5` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_6` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_7` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_8` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        $sql_del = "delete from `" . $this->getPre() . "tag_9` where artid='" . $aid . "';";
        $this->getConn()->SqlUpdate($sql_del);
        return 1;
    }
    
    public function mkTables()
    {
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_a` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
        ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_b` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_c` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_d` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_e` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_f` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_g` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_h` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_i` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_j` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_k` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_l` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_m` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_n` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_o` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_p` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
         ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_q` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
          ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_r` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_s` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_t` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_u` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_v` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_w` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_x` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_y` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_z` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_0` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_1` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_2` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_3` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_4` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_5` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_6` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_7` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_8` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_9` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tags` varchar(255) DEFAULT NULL,
              `artid` int(11) DEFAULT NULL,
              `isok` enum('0','1') DEFAULT '0',
              `arrt` char(10) DEFAULT NULL,
              `idf` decimal(16,13) DEFAULT NULL,
              `cishu` int(11) DEFAULT NULL,
              `itf` decimal(16,13) DEFAULT NULL,
              `paixu` decimal(16,13) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `ta` (`tags`,`artid`),
              KEY `tags` (`tags`),
              KEY `artid` (`artid`),
              KEY `arrt` (`arrt`),
              KEY `idf` (`idf`),
              KEY `cishu` (`cishu`),
              KEY `itf` (`itf`),
              KEY `paixu` (`paixu`)
            );
           ";
        $this->getConn()->SqlUpdate($sql);
        $sql = "CREATE TABLE `" . $this->getPre() . "tag_info` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `letter` char(10) DEFAULT NULL,
              `biao` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id`)
            );
            ";
        $this->getConn()->SqlUpdate($sql);
    }

    public function getConn()
    {
        return $this->_conn;
    }

    public function setConn($conn)
    {
        $this->_conn = $conn;
    }

    public function getPre()
    {
        return $this->_pre;
    }

    public function setPre($_pre)
    {
        $this->_pre = $_pre;
    }

    public function getRows()
    {
        return $this->_rows;
    }
    
    public function setRows($_rows)
    {
        $this->_rows = $_rows;
    }
    
    public function getFc()
    {
        return $this->_fc;
    }
    
    public function setFc($_fc)
    {
        $this->_fc = $_fc;
    }
    
    public function getWordsNew()
    {
        return $this->_wordsNew;
    }
    
    public function setWordsNew($_wordsNew)
    {
        $this->_wordsNew = $_wordsNew;
    }
    
    public function getKeyword()
    {
        return $this->_keyword;
    }
    
    public function setKeyword($_keyword)
    {
        $this->_keyword = $_keyword;
    }
    
    public function getContent()
    {
        return $this->_content;
    }
    
    public function setContent($_content)
    {
        $this->_content = $_content;
    }
    
    public function getAid()
    {
        return $this->_aid;
    }
    
    public function setAid($_aid)
    {
        $this->_aid = $_aid;
    }
    
    public function close()
    {
        $this->_aid=null;
        $this->_content=null;
        $this->_fc=null;
        $this->_keyword=null;
        $this->_pre=null;
        $this->_rows=null;
        $this->_wordsNew=null;
        $this->_conn=null;
    }
}