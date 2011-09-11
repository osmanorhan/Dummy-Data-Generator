<?php


/**
 * Dummy.class.php
 *  
 * @author Osman Orhan 
 * @Date 06-21-2011
 * @version 0.2
 */


class Dummy {
	
	var $db_name, $db, $table_name;
	var $type, $field = 100;
	
	function __construct($table_name) {
		
		$this->table_name = $table_name;
	}
	
	function connect($db_host, $db_user, $db_pass = '', $db_name) {
		
		$this->db = new mysqli ( $db_host, $db_user, $db_pass, $db_name );
	
	}
	
	function get_table_info() {
		
		$mysql_data_types = array (1 => 'tinyint', 2 => 'smallint', 3 => 'int', 4 => 'float', 5 => 'double', 6 => 'null', 7 => 'timestamp', 8 => 'bigint', 9 => 'mediumint', 10 => 'date', 11 => 'time', 12 => 'datetime', 13 => 'year', 14 => 'newdate', 16 => 'bit', 247 => 'enum', 248 => 'set', 249 => 'tinyblob', 250 => 'mediumblob', 251 => 'longblob', 252 => 'blob', 253 => 'varchar', 254 => 'char', 246 => 'decimal' );
		
		$result = $this->db->query ( "SELECT * FROM " . $this->table_name . " " );
		$info = $result->fetch_fields ();
		
		foreach ( $info as $i ) {
			
			$type [] = array ($i->name, $mysql_data_types [$i->type], $i->length );
		}
		return $type;
	}
	
	function get_data($type, $lenght, $name) {
		
		switch ($type) {
			case 'tinyint' :
				return rand ( 0, 255 );
				break;
			case 'smallint' :
				return rand ( 0, 32768 );
				break;
			case 'int' :
				if (preg_match ( "/id/", $name )) {
					return FALSE;
				} else {
					return rand ( 0, 2147483647 );

				}
				break;
			case 'float' :
				$rand = rand ( 1, 358 );
				return $rand * cos ( $rand );
				break;
			case 'double' :
				$rand = rand ( 1, 358 );
				return $rand * sin ( $rand );
				break;
			case 'bigint' :
				return rand ( - 9223372036854775808, 9223372036854775808 );
				break;
			case 'mediumint' :
				return rand ( - 8388608, 8388608 );
				break;
			case 'timestamp' :
				$m = rand ( 1, 12 );
				$d = rand ( 1, 27 );
				$y = rand ( 1970, 2037 );
				$h = rand ( 0, 23 );
				$min = rand ( 0, 59 );
				
				return date ( "Y-m-d h:m:s", mktime ( $h, $min, 0, $m, $d, $y ) );
				break;
			case 'date' :
				$m = rand ( 1, 12 );
				$d = rand ( 1, 27 );
				$y = rand ( 1900, 2100 );
				return date ( "Y-m-d ", mktime ( 0, 0, 0, $m, $d, $y ) );
				break;
			case 'time' :
				$h = rand ( 0, 23 );
				$min = rand ( 0, 59 );
				return $h . ":" . $m . ":00";
				break;
			case 'datetime' :
				$m = rand ( 1, 12 );
				$d = rand ( 1, 27 );
				$y = rand ( 1900, 2100 );
				$h = rand ( 0, 23 );
				$min = rand ( 0, 59 );
				return date ( "Y-m-d h:m:s", mktime ( $h, $min, 0, $m, $d, $y ) );
				break;
			case 'year' :
				return rand ( 1901, 2155 );
				break;
			case 'enum' :
				break;
			case 'tinyblob' :
				return $this->get_blob ( 10 );
				break;
			case 'mediumblob' :
				return $this->get_blob ( 100 );
				break;
			case 'longblob' :
				return $this->get_blob ( 1000 );
				break;
			case 'blob' :
				return $this->get_blob ( 20 );
				break;
			case 'varchar' :
				return $this->get_varchar ( $lenght,$name );
				break;
			case 'char' :
				return $this->get_varchar ( $lenght );
				break;
			case 'decimal' :
				break;
		
		}
	
	}
	
	function get_blob($word) {
		require_once ('LoremIpsum.class.php');
		$generator = new LoremIpsumGenerator ();
		return trim ( $generator->getContent ( $word, 'txt' ) );
	
	}
	
	function get_varchar($lenght,$name ='') {
		
		require_once ('LoremIpsum.class.php');
		$generator = new LoremIpsumGenerator ();
		
		
		
		if ($lenght / 7 < 1) {
			$r = 1;
		} else {
			$r = $lenght / 7;
		}
		if(preg_match('/mail/',$name)){
			$first_part = substr(trim ( $generator->getContent ( round ( $r, 0, PHP_ROUND_HALF_UP ), 'txt', false ) ),0,-15);
			$sign= array('/ /','/,/','/\./');
			$replace = array('','','');
			$first_part =preg_replace($sign, $replace, $first_part);
			$mail_arr = array("@hotmail.com","@gmail.com","@aol.com","@mail.ru","@yahoo.com");
			return $first_part.$mail_arr[array_rand($mail_arr)];
		}else{
			return trim ( $generator->getContent ( round ( $r, 0, PHP_ROUND_HALF_UP ), 'txt', false ) );
		}
	
	}
	
	function insert_data($field_count) {
		
		$data = $this->get_table_info ();
		$values ='';
		for($i = 0; $i < $field_count; $i ++) {
			$b = 0;
			$values .= "(";
			foreach ( $data as $val ) {
				if ($b !== 0) {
					$values .= ',';
				}
				$v = $this->get_data ( $val [1], $val [2], $val [0] );
				if($v === FALSE){
					$values .= "NULL";
				}else{
				$values .= "'" .$v. "'";
					
				}
				$b ++;
			}
			$values .= "),";
		
		}
		$values = substr ( $values, 0, - 1 );
		$query = "INSERT INTO " . $this->table_name . " VALUES " . $values . "";
		$end_query = $this->db->real_query ( $query );
		var_dump($query);
	
	}

}












