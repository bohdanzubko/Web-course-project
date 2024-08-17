<?php
class SizeModel{
	private $db;

	public function __construct($dbo){
		$this->db = $dbo;
	}

	public function getItem($sId){
		$sId = intval($sId);

		$sql = "SELECT * FROM ".TBL_SIZE." WHERE id = $sId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function addItem($sWidth, $sHeight, $sBottomWidth){
		$sWidth = floatval($sWidth);
		$sHeight = floatval($sHeight);
		$sBottomWidth = floatval($sBottomWidth);

		$res = $this->db->execute("INSERT INTO ".TBL_SIZE."(width, height, bottom_width) 
			VALUES($sWidth, $sHeight, $sBottomWidth)");
		
		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function deleteItem($sId){
		$sId = intval($sId);

		$res = $this->db->execute("DELETE FROM ".TBL_SIZE." WHERE id = $sId");
	
		return $res !== false;
	}
}