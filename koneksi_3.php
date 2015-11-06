<?php
require_once 'konfig-setting.php';
class database_3 {
	protected $_link, $_result, $_numRows, $_database;
	
	public function __CONSTRUCT(){
		$this->_database=database_3;
		return $this->_database;
	}
	
	public function connectDatabase(){
		$this->_link  = mysql_connect(server_remote, user_db_remote, password_remote);
		mysql_select_db($this->_database, $this->_link);
	}
	
	public function disconnect(){
		mysql_close($this->_link);	
	}
	
	public function query($sql){
		$this->_result = mysql_query($sql, $this->_link);	
	}
	
	public function numRows(){
		$this->_numRows = mysql_num_rows($this->_result);
		return $this->_numRows;
	}
	
	public function rows(){
		$rows = array();
		for ($x=0; $x<$this->numRows(); $x++){
			$rows[] = mysql_fetch_assoc ($this->_result);
		}
		return $rows;
	}
}
?>