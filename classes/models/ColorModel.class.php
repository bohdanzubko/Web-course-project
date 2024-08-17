<?php
class ColorModel{
	private $db;

	public function __construct($dbo){
		$this->db = $dbo;
	}

	public function getList(){
		$sql = "SELECT * FROM ".TBL_COLOR;
		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($clId){
		$clId = intval($clId);

		$sql = "SELECT * FROM ".TBL_COLOR." WHERE id = $clId";
		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}
}