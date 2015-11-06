<?php
require_once 'konfig-setting.php';
class database_2 {
	protected $_link, $_result, $_numRows, $_database;
	
	public function __CONSTRUCT(){
		$this->_database=database_2;
		return $this->_database;
	}
	
	public function connectDatabase_2(){
		$this->_link  = mysql_connect(server_remote, user_db_remote, password_remote);
		mysql_select_db($this->_database, $this->_link);
	}
	
	public function disconnect_2(){
		mysql_close($this->_link);	
	}
	
	public function query_2($sql){
		$this->_result = mysql_query($sql, $this->_link);	
	}
	
	public function numRows_2(){
		$this->_numRows = mysql_num_rows($this->_result);
		return $this->_numRows;
	}
	
	public function rows_2(){
		$rows = array();
		for ($x=0; $x<$this->numRows_2(); $x++){
			$rows[] = mysql_fetch_assoc ($this->_result);
		}
		return $rows;
	}
}
?>