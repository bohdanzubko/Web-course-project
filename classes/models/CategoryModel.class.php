<?php
class CategoryModel{
	private $db;

	public function __construct($dbo) {
		$this->db = $dbo;
	}

	public function getList($pi = -1, $pn = 10, $cType = null){
		$sql = "SELECT * FROM ".TBL_CATEGORY;

		if(!is_null($cType)){
			$sql .= " WHERE type = $cType";
		}

		if($pi >= 0){
			$sql .= " LIMIT ".($pi * $pn).", $pn";
		}

		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($cId){
		$cId = intval($cId);

		$sql = "SELECT * FROM ".TBL_CATEGORY." WHERE id = $cId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function additem($cName, $cType, $cImageId){
		$cName = mysqli_real_escape_string($this->db->getMySQLI(), $cName);
		$cType = intval($cType);
		$cImageId = intval($cImageId);

		$res = $this->db->execute("INSERT INTO ".TBL_CATEGORY."(name, type, image_id) 
		VALUES('$cName', $cType, $cImageId)");

		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($cId, $cName = null, $cType = null, $cImageId = null){
		$cId = intval($cId);
		$cName = isset($cName) ? mysqli_real_escape_string($this->db->getMySQLI(), $cName) : null;
		$cType = isset($cType) ? intval($cType) : null;
		$cImageId = isset($cImageId) ? intval($cImageId) : null;
	
		$updates = [];
		if ($cName !== null) {
			$updates[] = "name = '$cName'";
		}
		if ($cType !== null) {
			$updates[] = "type = $cType";
		}
		if ($cImageId !== null) {
			$updates[] = "image_id = $cImageId";
		}

		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);	
		$sql = "UPDATE " . TBL_CATEGORY . " SET " . $updateString . " WHERE id = $cId";
	
		$res = $this->db->execute($sql);	
		return $res !== false;
	}
	

	public function deleteItem($cId){
		$cId = intval($cId);
		
		$res = $this->db->execute("DELETE FROM ".TBL_CATEGORY." WHERE id = $cId");
	
		return $res !== false;
	}
}