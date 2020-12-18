<?php
namespace ZHMVC\D;
use PDO;
use PDOException;
class ZhPdo 
{
	private static $stmt = null;
	private static $DB = null;
	public $rowcount = 0;
	private $querycount = 0;
	private static $debug = false;
	private $dbdsn;
	private $dbusername;
	private $dbpassword;
	private $dboptions;
	private $sqlargs = array(); //创建一个空的绑定变量的集合
	
	public function __construct() {
		
	}
	
	public function setDbDsn($idsn)
	{
		$this->dbdsn=$idsn;
	}
	
	public function getDbDsn()
	{
		return $this->dbdsn;
	}
	
	public function setDbUsername($iusername)
	{
		$this->dbusername=$iusername;
	}
	
	public function getDbUsername()
	{
		return $this->dbusername;
	}
	
	public function setDbPassword($ipassword)
	{
		$this->dbpassword=$ipassword;
	}
	
	public function getDbPassword()
	{
		return $this->dbpassword;
	}
	
	public function setDbOptions($ioptions)
	{
		$this->dboptions=$ioptions;
	}
	
	public function getDbOptions()
	{
		return $this->dboptions;
	}
	
	public function setSqlArgs($ikey,$ivalue)
	{
		
		if(array_key_exists($ikey,$this->sqlargs))
		{
			$this->sqlargs[$ikey]=$ivalue;
		}
		else
		{
			$tempA=array($ikey=>$ivalue);
			$this->sqlargs = array_merge($this->sqlargs, $tempA);
		}
	}
	
	public function getSqlArgs()
	{
		return $this->sqlargs;
	}
	
	public function connect(){
		try{
		    self::$DB = new PDO($this->dbdsn, $this->dbusername, $this->dbpassword, $this->dboptions);
		    self::$DB->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
		    self::$DB->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
		    self::$DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		    self::$DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		    self::$DB->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
		    if(self::$debug==true){
			    $attributes = array(
				    "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS","ORACLE_NULLS", "PERSISTENT", "PREFETCH", "SERVER_INFO", "SERVER_VERSION","TIMEOUT"
				);
				foreach ($attributes as $val) {
				    echo "PDO::ATTR_$val: ";
				    //echo self::$DB->getAttribute(constant("PDO::ATTR_$val")) . "\n";
				}
		    }
		    self::$DB->query('set names utf8');
		}catch (PDOException $e){
		    //print_r($e);
			self::sql_error($e->getMessage());
		}
	}
	
	/********************************************
	 * 作用:获取库的所有表名
	 * 返回:库的所有表名
	 * 类型:数组
	 *********************************************/
	public function getTablesName($dbname) {
		self::$stmt = self::$DB->query ( 'SHOW TABLES FROM ' .$dbname );
		self::sql_error('error');
		$result = self::$stmt->fetchAll ( PDO::FETCH_BOTH );
		self::$stmt = null;
		return $result;
	}

	/********************************************
	 * 作用:获取数据表里的字段
	 * 返回:表字段结构
	 * 类型:数组
	 *********************************************/
	public function getFields($table) {
		self::$stmt = self::$DB->query ( "DESCRIBE $table" );
		self::getPDOError("DESCRIBE $table");
		$result = self::$stmt->fetchAll ( PDO::FETCH_BOTH );
		self::$stmt = null;
		return $result;
	}
	
	public function test(){
		if(is_array($this->sqlargs)) {
			print_r($this->sqlargs);
		}
	}
	
	/********************************************
	 * 作用:获取单行数据
	 * 返回:表内记录
	 * 类型:数组
	 * 参数:$db->getOne($table,$condition = null)
	 *********************************************/
	public function getOne($sql,$condition = null) {
		try {
			if (self::$debug) {
				echo $sql.'<br />';
			}
			self::$stmt = self::$DB->prepare($sql);
			self::getPDOError($sql);
			if(is_array($condition)) {
				self::$stmt->execute($condition);
			}
			else
			{
				self::$stmt->execute();
			}
			$result = self::$stmt->fetch();
			if (self::$stmt) {
				$this->rowcount = self::$stmt->rowCount();
			} else {
				$this->rowcount = 0;
			}
			self::$stmt = null;
			return $result;
		}
		catch (PDOException $e) {
		    self::sql_error($e->getMessage());
		}
	}
	
	
	/********************************************
	 * 作用:获取所有数据
	 * 返回:表内记录
	 * 类型:数组
	 * 参数:$db->getAll($table,$condition = null)
	 *********************************************/
	public function getAll($sql,$condition = null) {
		try {
			if (self::$debug) {
				echo $sql.'<br />';
			}
			//print_r($sql);
			self::$stmt = self::$DB->prepare($sql);
			self::getPDOError($sql);
			//print_r($condition);
			if(is_array($condition)) {
				self::$stmt->execute($condition);
			}
			else
			{
				self::$stmt->execute();
			}
			$result = self::$stmt->fetchAll();
			//print_r($result);
			if (self::$stmt) {
				$this->rowcount = self::$stmt->rowCount ();
			} else {
				$this->rowcount = 0;
			}
			//self::$stmt->debugDumpParams();
			self::$stmt = null;
			return $result;
		}
		catch (PDOException $e) {
		    self::sql_error($e->getMessage());
		}
	}
	
	/********************************************
	 * 作用:获得记录集数目
	 * 返回:记录集数目
	 * 类型:数字
	 *********************************************/
	public function getRowCount()
	{
		return $this->rowcount;
	}
	
	
	/********************************************
	 * 作用:插入、修改、删除数据
	 * 返回:表内记录
	 * 类型:数组
	 * 参数:$db->update('$table',array('title'=>'Zxsv'))
	 *********************************************/
	public function update($sql, $condition = null) {
		if (self::$debug) {
			echo $sql.'<br />';
		}
		
		//使用事务
		try {
			self::$stmt = self::$DB->prepare($sql);
			self::getPDOError($sql);
			if(is_array($condition)) {
				//print_r($condition);
				$result=self::$stmt->execute($condition);
			}
			else
			{
				$result=self::$stmt->execute();
			}
			self::$stmt = null;
			//print_r($result);
			return $result;
		}
		catch (PDOException $e) {
		    self::sql_error($e->getMessage());
		}
	}

	
	/********************************************
	 * 作用:获得最后INSERT的主键ID
	 * 返回:最后INSERT的主键ID
	 * 类型:数字
	 *********************************************/
	public function getLastId() {
	    try {
	        $sql="select last_insert_id() as id";
	        self::$stmt = self::$DB->prepare($sql);
	        self::getPDOError($sql);
	        self::$stmt->execute();
	        $result = self::$stmt->fetch();
	        self::$stmt = null;
	        return $result['id'];
	    }
	    catch (PDOException $e) {
	        self::sql_error($e->getMessage());
	    }
		//return self::$DB->lastInsertId ();
	}
	
	/********************************************
	 * 捕获PDO错误信息
	 *********************************************/
	private function getPDOError($sql) {
		if (self::$DB->errorCode() != '00000') {
			$info = (self::$stmt) ? self::$stmt->errorInfo () : self::$DB->errorInfo ();
			self::sql_error('mySQL Query Error', $info[2], $sql);
		}
	}
	
	/********************************************
	 *设置是否为调试模式
	 *********************************************/
	public function setDebugMode($mode = TRUE) {
		$return = ($mode == TRUE) ? self::$debug = TRUE : self::$debug = FALSE;
		return $return;
	}
        
     /********************************************
	 * 捕获错误信息
	 *********************************************/
	private function getError($sql="") {
	    //print_r(self::$DB->errorCode());
		if (self::$DB->errorCode() != '00000') {
			$info = (self::$stmt) ? self::$stmt->errorInfo () : self::$DB->errorInfo ();
			
			self::sql_error('mySQL Query Error', $info[2], $sql);
		}
	}
	/********************************************
	 *事务开始
	 *********************************************/
	public function autocommit() {
		self::$DB->beginTransaction ();
	}

	/********************************************
	 *事务提交
	 *********************************************/
	public function commit() {
		self::$DB->commit ();
	}

	/********************************************
	 *事务回滚
	 *********************************************/
	public function rollback() {
		self::$DB->rollback ();
	}
	
	/********************************************
	 * 作用:运行错误信息
	 * 返回:运行错误信息和SQL语句
	 * 类型:字符
	 *********************************************/
	function sql_error($message = '', $info = '', $sql = '') {
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		$html .= '<head><title>mySQL Message</title><style type="text/css">body {margin:0px;color:#555555;font-size:12px;background-color:#efefef;font-family:Verdana} ol {margin:0px;padding:0px;} .w {width:800px;margin:100px auto;padding:0px;border:1px solid #cccccc;background-color:#ffffff;} .h {padding:8px;background-color:#ffffcc;} li {height:auto;padding:5px;line-height:22px;border-top:1px solid #efefef;list-style:none;overflow:hidden;}</style></head>';
		$html .= '<body><div class="w"><ol>';
		
		if ($message) {
			$html .= '<div class="h">'.$message.'</div>';
		}
		$html .= '<li>Date: ' . date ( 'Y-n-j H:i:s', time () ) . '</li>';
		if($sql) {
			$html .= '<li>SQLID: ' . $info . '</li>';
		}
		if($sql) {
			$html .= '<li>Error: ' . $sql . '</li>';
		}
		$html .= '</ol></div></body></html>';
		echo $html;
		exit;
	}

	/********************************************
	*关闭数据连接
	*********************************************/
	public function close() {
		self::$DB = null;
	}

	/********************************************
	*析构函数
	*********************************************/
	public function __destruct() {
	}
	
}