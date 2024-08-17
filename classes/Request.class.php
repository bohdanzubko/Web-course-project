<?php
class Request{
	public static function getVar ($name, $defval = ""){
		$val = $defval;
		if(isset($_POST[$name]) && !empty($_POST[$name]))
			$val = $_POST[$name];
		else if(isset($_GET[$name]) && !empty($_GET[$name]))
			$val = $_GET[$name];

		return $val;
	}
}