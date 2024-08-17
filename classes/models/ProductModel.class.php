<?php
class ProductModel{
	private $db;

	public function __construct($dbo){
		$this->db = $dbo;
	}

	public function getList($pCatId = null, $in_stock = null, $priceRange = null, $orderBy = "id", $ordertDirection = "ASC", $pi = -1, $pn = 10){
		$sql = "SELECT * FROM ".TBL_PRODUCT;

		if(!is_null($pCatId)){
			$sql .= " WHERE category_id = ".intval($pCatId);
		}

		if(!is_null($in_stock)){
			$sql .= " AND in_stock = ".intval($in_stock);
		}

		if(!is_null($priceRange)){
			$sql .= " AND price BETWEEN ".$priceRange[0]." AND ".$priceRange[1];
		}

		$sql .= " ORDER BY $orderBy $ordertDirection";

		if($pi >= 0){
			$sql .= " LIMIT ".($pi * $pn).", $pn";
		}

		$res = $this->db->query($sql);

		return $res;
	}

	public function getItem($pId){
		$pId = intval($pId);

		$sql = "SELECT * FROM ".TBL_PRODUCT." WHERE id = $pId";

		$res = $this->db->query($sql);

		return count($res) > 0 ? $res[0] : null;
	}

	public function addItem($pCatId, $pName, $pPrice, $pDesc, $pSizeId, $pInStock = 0){
		$pCatId = intval($pCatId);
		$pName = mysqli_real_escape_string($this->db->getMySQLI(), $pName);
		$pPrice = floatval($pPrice);
		$pDesc = mysqli_real_escape_string($this->db->getMySQLI(), $pDesc);
		$pSizeId = intval($pSizeId);
		$pInStock = intval($pInStock);

		$res = $this->db->execute("INSERT INTO ".TBL_PRODUCT."(category_id, name, price, description, size_id, in_stock) 
			VALUES($pCatId, '$pName', $pPrice, '$pDesc', $pSizeId, $pInStock)");

		if($res !== false){
			return $this->db->getInsertId();
		}

		return false;
	}

	public function updateItem($pId, $pCatId = null, $pName = null, $pPrice = null, $pDesc = null, $pSizeId = null, $pInStock = null){
		$pId = intval($pId);
		$pCatId = isset($pCatId) ? intval($pCatId) : null;
		$pName = isset($pName) ? mysqli_real_escape_string($this->db->getMySQLI(), $pName) : null;
		// $pPrice does not need to be sanitized as it's a numeric value
		$pDesc = isset($pDesc) ? mysqli_real_escape_string($this->db->getMySQLI(), $pDesc) : null;
		$pSizeId = isset($pSizeId) ? intval($pSizeId) : null;
		$pInStock = isset($pInStock) ? intval($pInStock) : null;

		$updates = [];
		if ($pCatId !== null) {
			$updates[] = "category_id = $pCatId";
		}
		if ($pName !== null) {
			$updates[] = "name = '$pName'";
		}
		if ($pPrice !== null) {
			$updates[] = "price = $pPrice";
		}
		if ($pDesc !== null) {
			$updates[] = "description = '$pDesc'";
		}
		if ($pSizeId !== null) {
			$updates[] = "size_id = $pSizeId";
		}
		if ($pInStock !== null) {
			$updates[] = "in_stock = $pInStock";
		}
		
		if (empty($updates)) {
			return false;
		}

		$updateString = implode(", ", $updates);
		$sql = "UPDATE " . TBL_PRODUCT . " SET " . $updateString . " WHERE id = $pId";

		$res = $this->db->execute($sql);
		return $res !== false;
	}
	

	public function deleteItem($pId){
		$pId = intval($pId);

		$res = $this->db->execute("DELETE FROM ".TBL_PRODUCT." WHERE id = $pId");
	
		return $res !== false;
	}
}