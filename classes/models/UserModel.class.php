<?php
class UserModel{
	private $db;

	public function __construct($dbo) {
		$this->db = $dbo;
	}

	public function getList($pi = -1, $pn = 10){
		$sql = "SELECT * FROM ".TBL_USER;

		if($pi >= 0){
			$sql .= " LIMIT ".($pi * $pn).", $pn";
		}

		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($uId){
		$uId = intval($uId);

		$sql = "SELECT * FROM ".TBL_USER." WHERE id = $uId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function addItem($uName, $uLogin, $uPass){
		$uName = mysqli_real_escape_string($this->db->getMySQLI(), $uName);
		$uLogin = mysqli_real_escape_string($this->db->getMySQLI(), $uLogin);
		$uPass = mysqli_real_escape_string($this->db->getMySQLI(), $uPass);

		$res = $this->db->execute("INSERT INTO ".TBL_USER."(username, login, password) 
		VALUES('$uName', '$uLogin', PASSWORD('$uPass'))");
	
		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($uId, $uName = null, $uLogin = null, $uPass = null) {
		$uId = intval($uId);
		$uName = isset($uName) ? mysqli_real_escape_string($this->db->getMySQLI(), $uName) : null;
		$uLogin = isset($uLogin) ? mysqli_real_escape_string($this->db->getMySQLI(), $uLogin) : null;
		$uPass = isset($uPass) ? mysqli_real_escape_string($this->db->getMySQLI(), $uPass) : null;

		$updates = [];

		if ($uName !== null) {
			$updates[] = "username = '$uName'";
		}

		if ($uLogin !== null) {
			$updates[] = "login = '$uLogin'";
		}

		if ($uPass !== null) {
			$updates[] = "password = PASSWORD('$uPass')";
		}

		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);
		$sql = "UPDATE " . TBL_USER . " SET " . $updateString . " WHERE id = $uId";

		$res = $this->db->execute($sql);
		return $res !== false;
	}
	

	public function deleteItem($uId){
		$uId = intval($uId);

		$res = $this->db->execute("DELETE FROM ".TBL_USER." WHERE id = $uId");
	
		return $res !== false;
	}
}