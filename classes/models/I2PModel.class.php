<?php
class I2PModel{
	private $db;

	public function __construct($dbo){
		$this->db = $dbo;
	}

	public function getList(){
		$sql = "SELECT * FROM ".TBL_I2P;

		$res = $this->db->query($sql);

		return $res;
	}
    
    public function getImage($pId){
		$pId = intval($pId);

		$sql = "SELECT image_id FROM ".TBL_I2P." WHERE product_id = $pId";

		$res = $this->db->query($sql);

		return $res;
	}
    /*
	public function getProduct($iId){
		$iId = intval($iId);

		$sql = "SELECT product_id FROM ".TBL_I2P." WHERE image_id = $iId";

		$res = $this->db->query($sql);

		return $res;
	}
    */
	public function addItem($iId, $pId){
		$iId = intval($iId);
		$pId = intval($pId);

		$res = $this->db->execute("INSERT INTO ".TBL_I2P."(image_id, product_id)
			VALUES($iId, $pId)");
		
		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($iId = null, $pId = null){
		$iId = isset($iId) ? intval($iId) : null;
		$pId = isset($pId) ? intval($pId) : null;
	
		$updates = [];
		if ($iId !== null) {
			$updates[] = "image_id = $iId";
		}
		if ($pId !== null) {
			$updates[] = "product_id = $pId";
		}

		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);	
		$sql = "UPDATE " . TBL_I2P . " SET " . $updateString;	

		$res = $this->db->execute($sql);	
		return $res !== false;
	}
	

	public function deleteItem($iId){
		$iId = intval($iId);

		$res = $this->db->execute("DELETE FROM ".TBL_I2P." WHERE id = $iId");
	
		return $res !== false;
	}
}