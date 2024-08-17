<?php
class C2PModel{
	private $db;

	public function __construct($dbo){
		$this->db = $dbo;
	}

	public function getList(){
		$sql = "SELECT * FROM ".TBL_C2P;

		$res = $this->db->query($sql);

		return $res;
	}
    
    public function getImage($pId){
		$pId = intval($pId);

		$sql = "SELECT color_id FROM ".TBL_C2P." WHERE product_id = $pId";

		$res = $this->db->query($sql);

		return $res;
	}
    /*
	public function getProduct($cId){
		$cId = intval($cId);

		$sql = "SELECT product_id FROM ".TBL_C2P." WHERE color_id = $cId";

		$res = $this->db->query($sql);

		return $res;
	}
    */
	public function addItem($cId, $pId){
		$cId = intval($cId);
		$pId = intval($pId);

		$res = $this->db->execute("INSERT INTO ".TBL_C2P."(color_id, product_id)
			VALUES($cId, $pId)");
		
		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($cId = null, $pId = null){
		$cId = isset($cId) ? intval($cId) : null;
		$pId = isset($pId) ? intval($pId) : null;
	
		$updates = [];
		if ($cId !== null) {
			$updates[] = "color_id = $cId";
		}
		if ($pId !== null) {
			$updates[] = "product_id = $pId";
		}

		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);	
		$sql = "UPDATE " . TBL_C2P . " SET " . $updateString;

		$res = $this->db->execute($sql);
		return $res !== false;
	}
	

	public function deleteItem($cId){
		$cId = intval($cId);

		$res = $this->db->execute("DELETE FROM ".TBL_C2P." WHERE id = $cId");
	
		return $res !== false;
	}
}