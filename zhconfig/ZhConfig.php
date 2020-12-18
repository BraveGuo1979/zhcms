<?php
namespace ZHCONFIG;
class ZhConfig{
	private $db_host='';//MYSQL数据库服务器IP
	private $db_user='';//用户名
	private $db_pass='';//密码
	private $db_database='';//使用库
	private $db_charset="";//mysql数据采集模式,在建立表的时候可定义模式
	private $db_pre="";//mysql数据采集模式,在建立表的时候可定义模式
	
	/*
	public function __construct(){
		$this->db_host='xbrc3306.mysql.rds.aliyuncs.com';//MYSQL数据库服务器IP
		$this->db_user='dba_xbrc';//用户名
		$this->db_pass='1q2w3e4r5t6Y7U8I9O0P';//密码
		$this->db_database='gongan';//使用库
		$this->db_charset="utf8";//mysql数据采集模式,在建立表的时候可定义模式
		$this->db_pre="zhmvc_";//mysql数据采集模式,在建立表的时候可定义模式
	}
	*/
	public function __construct(){
	    $this->db_host='localhost';//MYSQL数据库服务器IP
	    $this->db_user='root';//用户名
	    $this->db_pass='123456';//密码
	    $this->db_database='yigecms';//使用库
	    $this->db_charset="utf8";//mysql数据采集模式,在建立表的时候可定义模式
	    $this->db_pre="zhmvc_";//mysql数据采集模式,在建立表的时候可定义模式
	}
	public function getDbPre(){
	    return $this->db_pre;
	}
	
	public function getHost(){
		return $this->db_host;
	}
	
	public function getUser(){
		return $this->db_user;
	}
	
	public function getPassWord(){
		return $this->db_pass;
	}
	
	public function getDatabase(){
		return $this->db_database;
	}
	
	public function getCharset(){
		return $this->db_charset;
	}
}
