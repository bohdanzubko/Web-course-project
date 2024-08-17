<?php

class NewsModel{
	private $db;

	public function __construct($dbo) {
		$this->db = $dbo;
	}

	public function getList(){
		$sql = "SELECT * FROM ".TBL_NEWS;

		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($nId){
		$nId = intval($nId);

		$sql = "SELECT * FROM ".TBL_NEWS." WHERE id = $nId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function additem($nTitle, $nDesc, $nImageId, $nURL){
		$nTitle = mysqli_real_escape_string($this->db->getMySQLI(), $nTitle);
		$nDesc = mysqli_real_escape_string($this->db->getMySQLI(), $nDesc);
		$nImageId = intval($nImageId);
		$nURL = mysqli_real_escape_string($this->db->getMySQLI(), $nURL);

		$res = $this->db->execute("INSERT INTO ".TBL_NEWS."(title, description, image_id, url) 
			VALUES('$nTitle', '$nDesc', $nImageId, '$nURL')");

		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($nId, $nTitle = null, $nDesc = null, $nImageId = null, $nURL = null){
		// Sanitize the inputs
		$nId = intval($nId);
		$nTitle = isset($nTitle) ? mysqli_real_escape_string($this->db->getMySQLI(), $nTitle) : null;
		$nDesc = isset($nDesc) ? mysqli_real_escape_string($this->db->getMySQLI(), $nDesc) : null;
		$nImageId = isset($nImageId) ? intval($nImageId) : null;
		$nURL = isset($nURL) ? mysqli_real_escape_string($this->db->getMySQLI(), $nURL) : null;

		$updates = [];
		if ($nTitle !== null) {
			$updates[] = "title = '$nTitle'";
		}
		if ($nDesc !== null) {
			$updates[] = "description = '$nDesc'";
		}
		if ($nImageId !== null) {
			$updates[] = "image_id = $nImageId";
		}
		if ($nURL !== null) {
			$updates[] = "url = '$nURL'";
		}

		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);
		$sql = "UPDATE " . TBL_NEWS . " SET " . $updateString . " WHERE id = $nId";

		$res = $this->db->execute($sql);
		return $res !== false;
	}
	

	public function deleteItem($nId){
		$nId = intval($nId);

		$res = $this->db->execute("DELETE FROM ".TBL_NEWS." WHERE id = $nId");
	
		return $res !== false;
	}
}