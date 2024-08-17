<?php
class DB{
	private $db;
	private $is_debug;
	
	public function __construct($host, $login, $pass, $dbname)
	{
		$this->is_debug;
		
		$this->db = new mysqli($host, $login, $pass, $dbname);
		
		if($this->db->connect_error){
			echo $this->db->connect_error;
		}
	}
	
	public function setDebug($is_debug = false)
	{
		$this->is_debug = $is_debug;
	}
	
	public function query($sql)
	{
		$data = Array();
		
		$res = $this->db->query($sql);
		if( $res )
		{
			while($row = $res->fetch_assoc())
			{
				$data[] = $row;
			}
		}
		elseif( $this->is_debug )
		{
			echo $this->db->error."<br>";
			echo $sql."<br><br>";
		}

		if( $this->is_debug )
		{
			echo $sql."<br>";
			echo "DATA from query:";
			var_dump($data);
			echo "<br><br>";
		}
		
		return $data;
	}
	
	public function execute($sql)
	{
		$res = $this->db->query($sql);

		if( !$res ){
			if( $this->is_debug ){
				echo $sql."<br>";
				echo $this->db->error."<br>";
			}
			return false;
		}
		else if( $this->is_debug )
		{
			echo $sql."<br>";
		}

		return true;
	}

	public function close(){
		$this->db->close();
		$this->db = null;
	}

	public function getInsertId()
	{
		return $this->db->insert_id;
	}

	public function getMySQLI()
	{
		return $this->db;
	}
}
