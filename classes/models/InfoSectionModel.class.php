<?php
class InfoSectionModel{
	private $db;

	public function __construct($dbo) {
		$this->db = $dbo;
	}

	public function getList($pi = -1, $pn = 10){
		$sql = "SELECT * FROM ".TBL_INFOSECT;

		if($pi >= 0){
			$sql .= " LIMIT ".($pi * $pn).", $pn";
		}

		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($sId){
		$sId = intval($sId);

		$sql = "SELECT * FROM ".TBL_INFOSECT." WHERE id = $sId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function additem($sName, $sImageId, $sURL){
		$sName = mysqli_real_escape_string($this->db->getMySQLI(), $sName);
		$sImageId = intval($sImageId);
		$sURL = mysqli_real_escape_string($this->db->getMySQLI(), $sURL);

		$res = $this->db->execute("INSERT INTO ".TBL_INFOSECT."(name, image_id, url) 
		VALUES('$sName', $sImageId, '$sURL')");

		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($sId, $sName = null, $sImageId = null, $sURL = null){
		$sId = intval($sId);
		$sName = isset($sName) ? mysqli_real_escape_string($this->db->getMySQLI(), $sName) : null;
		$sImageId = isset($sImageId) ? intval($sImageId) : null;
		$sURL = isset($sURL) ? mysqli_real_escape_string($this->db->getMySQLI(), $sURL) : null;

		$updates = [];
		if ($sName !== null) {
			$updates[] = "name = '$sName'";
		}
		if ($sImageId !== null) {
			$updates[] = "image_id = $sImageId";
		}
		if ($sURL !== null) {
			$updates[] = "url = '$sURL'";
		}

		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);
		$sql = "UPDATE " . TBL_INFOSECT . " SET " . $updateString . " WHERE id = $sId";

		$res = $this->db->execute($sql);
		return $res !== false;
	}
	

	public function deleteItem($sId){
		$sId = intval($sId);

		$res = $this->db->execute("DELETE FROM ".TBL_INFOSECT." WHERE id = $sId");
	
		return $res !== false;
	}
}