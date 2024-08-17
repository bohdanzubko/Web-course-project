<?php
class ImageModel{
	private $db;

	public function __construct($dbo){
		$this->db = $dbo;
	}

	public function getList(){
		$sql = "SELECT * FROM ".TBL_IMAGE;

		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($iId){
		$iId = intval($iId);

		$sql = "SELECT * FROM ".TBL_IMAGE." WHERE id = $iId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function addItem($iPath){
		$iPath = mysqli_real_escape_string($this->db->getMySQLI(), $iPath);

		$res = $this->db->execute("INSERT INTO ".TBL_IMAGE."(creation_date, path) 
			VALUES(NOW(), '$iPath')");
		
		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function deleteItem($iId){
		$iId = intval($iId);

		$res = $this->db->execute("DELETE FROM ".TBL_IMAGE." WHERE id = $iId");
	
		return $res !== false;
	}
}