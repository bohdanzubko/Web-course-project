<?php
include("../../config/init.php");

if(!$UserSes->isLogged()){
	header("Location: index.php");
	exit();
}

if(DEBUG_MODE == true){
	echo "Session id: ".$UserSes->getSesId()."<br>";
	echo "You are logged as: ".$UserSes->getUserName()."<br>";

	echo "UserSesion: ";
	var_dump($UserSes);
	echo "<br>";

	echo "POST params: ";
	var_dump($_POST);
	echo "<br>";

	echo "GET params: ";
	var_dump($_GET);
	echo "<br>";
}

$action = Request::getVar("action");
$msg = "";

include("../inc/admin-header.php");
?>

<?php
include("../inc/admin-footer.php");
?>