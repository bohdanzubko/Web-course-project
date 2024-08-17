<?php
class Session{
	private $ses_id = "";

	public function __construct($path = ""){
		if($path != ""){
			session_save_path($path);
		}
		session_start();
		$this->ses_id = session_id();
	}

	public function getSesId(){
		return $this->ses_id;
	}

	public function getSesVar($name){
		if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		
		return null;
	}

	public function setSesVar($name, $value){
		$_SESSION[$name] = $value;
	}
}