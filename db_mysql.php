<?php
/************
* mysqli db
*************/
class c_db {
	var $connection = false;
	var $query_string;
	var $result;
	var $error = '';

	function __construct($_db_config){
		if($_db_config){
			$host = $_db_config['host'];
			$db_name = $_db_config['db_name'];
			$db_table = $_db_config['db_table'];
			$login = $_db_config['login'];
			$password = $_db_config['password'];
			$this->connection = mysqli_connect($host, $login, $password, $db_name);
			if(!$this->connection){
				$this->error = mysqli_connect_error();
			}
		} else {
			$this->error .= 'DATABASE: No _db_config!!!<br>';
		}
	}

	function exec_query(){
		if($this->connection && $this->query_string){
			$this->result = mysqli_query($this->connection, $this->query_string);
			$this->error .= mysqli_error($this->connection);
			if(!$this->error){
				return true;
			}
		}
		return false;
	}

	function select($fields=' * ', $tables, $where='1', $limit='0, 30'){
		$this->query_string = "select $fields from $tables where $where limit $limit;";
		return $this->exec_query();
	}

	function fetch($type = MYSQLI_BOTH){
		return mysqli_fetch_array($this->result, $type);
	}

	function num_rows(){
		return mysqli_num_rows($this->result);
	}

	function free(){
		mysqli_free_result($this->result);
	}

	function insert($table, $values){
		$this->query_string = "insert into $table values ($values);";
		return $this->exec_query();
	}

	function delete($table, $where){
		$this->query_string = "delete from $table where $where;";
		return $this->exec_query();
	}

	function update($table, $set, $where){
		$this->query_string = "update $table set $set where $where;";
		return $this->exec_query();
	}

	function close(){
		if($this->connection){
			mysqli_close($this->connection);
		}
	}
}

?>