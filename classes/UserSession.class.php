<?php
class UserSession extends Session {
	private $db;
	protected $user_id = 0;
	protected $user_name = "";
	protected $user_login = "";

	public function __construct($dbo, $path = ""){
		parent::__construct($path);
		
		$this->db = $dbo;

		$this->checkUserAuth();
	}

	public function checkUserAuth(){
		$this->user_id = 0;
		$this->user_name = "";
		$this->user_login = "";

		$sql = "SELECT u1.* FROM ".TBL_SESSION." s1
				INNER JOIN ".TBL_USER." u1 ON s1.user_id=u1.id
				WHERE s1.ses_id='".$this->getSesId()."'";

		$res = $this->db->query($sql);

		if(count($res) > 0){
			$row = $res[0];

			$this->user_id = $row['id'];
			$this->user_name = $row['username'];
			$this->user_login = $row['login'];

		}

		return ($this->user_id != 0);
	}

	public function isLogged(){
		return ($this->user_id != 0);
	}

	public function getUserId(){
		return $this->user_id;
	}

	public function getUserName(){
		return $this->user_name;
	}

	public function getUserLogin(){
		return $this->user_login;
	}

	public function logout(){
		$sql = "DELETE FROM ".TBL_SESSION." WHERE ses_id='".$this->getSesId()."'";
		
		$res = $this->db->execute($sql);
		
		$this->user_id = 0;
		$this->user_name = "";
		$this->user_login = "";
	}

	public function makeUserLogin($user_login, $user_password){
		if($this->isLogged()){
			return true;
		}

		$sql = "SELECT id, username, login FROM ".TBL_USER." 
				WHERE login='".addslashes($user_login)."' 
				AND password=PASSWORD('".addslashes($user_password)."')";

		$res = $this->db->query($sql);

		if(count($res) > 0){
			var_dump($res[0]);
			echo "<br>";
			if ($this->addUserSession($res[0]['id'])) {
				$this->user_id = $res[0]['id'];
				$this->user_name = $res[0]['name'];
				$this->user_login = $res[0]['login'];

				return true;
			}
		}

		return false;
	}

	public function checkUserLogin($user_login, $user_password){
		$sql = "SELECT u.id FROM ".TBL_USER." u
				WHERE u.login='".addslashes($user_login)."' 
				AND u.password=PASSWORD('".addslashes($user_password)."')";
		
		$res = $this->db->query($sql);
		
		var_dump($res);
		echo "<br>";

		if(count($res) > 0)
			return $res['id'];

		return false;
	}

	public function addUserSession($user_id) {
		$sql = "INSERT INTO ".TBL_SESSION."(ses_id, user_id, add_date, last_access)
		VALUES('".$this->getSesId()."', '".$user_id."', NOW(), NOW())";

		echo $sql."<br>";
		
		return $this->db->execute($sql);
	}
}