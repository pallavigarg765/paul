<?php
class DBConn{

	var $qry_var;

	/********************* Db Connection Portion Starts *********************/

	function DBConn() {
			$host = 'db';
			$db = 'planitlo_crm';
			$user = 'root';
			// $pass = 'Securel0gi!12aa';
			$pass = 'Root765%^&';
		$this->link = mysql_connect($host, $user, $pass) or die('Could not connect to the server: '.mysql_error());
		mysql_select_db($db) or die('Could not connect to the database: '.mysql_error());
	} 

	/********************* Db Connection Function Ends *********************/
	
	/********************* Execute Db Query Function Starts *********************/

	function query($query) {
		$this->qry_var = $query;
	return mysql_query($query, $this->link);
	}
	
	/********************* Execute Db Query Function Ends *********************/

	/********************* Get Total Number Of Rows Function Starts *********************/

	function getNumRows($result){
		return mysql_num_rows($result);
	}

	/********************* Get Total Number Of Rows Function Ends *********************/

	/********************* Get Results in Array Function Starts *********************/

	function fetchArray($result) {
		return mysql_fetch_array($result);
	}

	/********************* Get Results in Array Function Ends *********************/

	/********************* Get Field Function Starts *********************/
	function getField($table,$fieldName,$filter)
	{
			$sql = 'SELECT * FROM '.$table.' where '.$filter;
			$res =& $this->query($sql);
			$row = $this->fetchArray($res);
			if($row)
				return $row[$fieldName] ;
				
			return '';
	}
	/********************* Get Field Function Ends *********************/
	
	/********************* Close Db Connection Function Starts *********************/

	function insert_id() {
		mysql_insert_id($this->link);
	}

	/********************* Close Db Connection Function Ends *********************/

	/********************* Close Db Connection Function Starts *********************/

	function close() {
		mysql_close($this->link);
	}

	/********************* Close Db Connection Function Ends *********************/
}
?>
