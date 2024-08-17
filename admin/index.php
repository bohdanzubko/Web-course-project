<?php
include("../config/init.php");

if(DEBUG_MODE == true){
	echo "Session id: ".$UserSes->getSesId()."<br>";
	echo "User is logged: ".$UserSes->isLogged()."<br><br>";
	
	echo "UserSesion: ";
	var_dump($UserSes);
	echo "<br><br>";
	
	echo "POST params: ";
	var_dump($_POST);
	echo "<br><br>";
	
	echo "GET params: ";
	var_dump($_GET);
	echo "<br><br>";
}

$action = Request::getVar("action");
$msg = "";

if($UserSes->isLogged() && ($action != "makeLogout"))
{
	header("Location: modules/admin-home.php");
	exit();
}

switch ($action) {
	case "makeLogout":
		if($UserSes->isLogged())
			$UserSes->logout();
		
		header("Location: " . $_SERVER['PHP_SELF']);
		
		break;
	case "makeLogin":
		$uLogin = Request::getVar("uLogin");
		$uPass = Request::getVar("uPass");

		if($UserSes->makeUserLogin($uLogin, $uPass))
		{
			header("Location: modules/admin-home.php");
			exit();
		}
		
		$msg = "Login or password is incorrect!";
		break;
}

if($msg != ""){
	echo '<div style="color: red;">'.$msg.'</div>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Authorization</title>
	<link type="text/css" rel="stylesheet" href="css/admin-style.css">
	<script src="js/admin-script.js"></script>
</head>
<body>
<div class="wrapper">
	<header>
		<h1>Authorization</h1>
	</header>
<main>
	<form action="" method="POST">
		<input type="hidden" name="action" value="makeLogin">
		<table>
			<tr>
				<td>Login</td>
				<td><input type="text" name="uLogin"></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="uPass"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Log in"></td>
			</tr>
		</table>
	</form>
</main>

<?php
include("inc/admin-footer.php");
?>