<?php
include("../../config/init.php");
  
if(!$UserSes->isLogged()){
	header("Location: ../index.php");
	exit();
}

if(DEBUG_MODE == true){
	echo "Session id: ".$UserSes->getSesId()."<br>";
	echo "You are logged as: ".$UserSes->getUserName()."<br><br>";

	echo '<pre> POST params: ';
	var_dump($_POST);
	echo "</pre>";

	echo '<pre> GET params: ';
	var_dump($_GET);
	echo "</pre>";
}
/** action
 * addObject - creation
 * editObject - editing allow
 * updateObject - update
 * deleteObject - deletion allow
 * removeObject - removing
 */
$action = Request::getVar("action", "");

$uId = Request::getVar("uId", null);
$uLogin = Request::getVar("uLogin", null);
$uName = Request::getVar("uName", null);
$uPass = Request::getVar("uPass", null);

/** viewMode
 * list - table of objects
 * add - table for creation
 * edit - table for editing
 * del - deletion allow
 */
$viewMode = Array();

$msg = "";

$Users = new UserModel($db);
$UsersList = $Users->getList();

switch ($action) {
	case "editObject":
		if(intval($uId) == 0)
			break;
		
		$viewMode[] = "edit";

		$uInfo = $Users->getItem($uId);
		break;
	case "deleteObject":
		if(intval($uId) == 0)
			break;
		
		$viewMode[] = "del";

		break;
	case "addObject":
		foreach($UsersList as $user){
			if($uLogin == $user['login']){
				$msg = "This login is already taken!";
				break;
			}
		}

		if($msg == "This login is already taken!"){
			break;
		}

		if($uLogin == ""){
			$msg = "User login can't be empty!";
			break;
		}

		if(!$Users->addItem($uName, $uLogin, $uPass)){
			$msg = "Unable to create user!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "updateObject":
		if(intval($uId) == 0)
			break;

		foreach($UsersList as $user){
			if($user['id'] == $uId)
				continue;
			if($uLogin == $user['login']){
				$msg = "This login is already taken!";
				break;
			}
		}

		if($msg == "This login is already taken!"){
			break;
		}

		if(!$Users->updateItem($uId, $uName, $uLogin, $uPass)){
			$msg = "Unable to update user!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "removeObject":
		if(intval($uId) == 0)
			break;

		if(!$Users->deleteItem($uId)){
			$msg = "Unable to remove user!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
}

if(count($viewMode) == 0){
	$viewMode[] = "list";
	$viewMode[] = "add";
}

if(DEBUG_MODE == true){
	echo "<pre>ViewMode params:";
	var_dump($viewMode);
	echo "</pre>";
}

include("../inc/admin-header.php");

if($msg != ""){
	echo '<div style="color: red;">'.$msg.'</div>';
}
?>
<?php
for($iv = 0; $iv < count($viewMode); $iv++){
	if ($viewMode[$iv] == "list"){
		?>
		<h2>Table of Users</h2>
		<table border=1>
			<tr>
				<th>ID</th>
				<th>Usernme</th>
				<th>Login</th>
				<th>EDIT</th>
				<th>DELETE</th>
			</tr>
			<?php foreach ($UsersList as $user) : ?>
			<tr>
				<td><?= $user['id'] ?></td>
				<td><?= $user['username'] ?></td>
				<td><?= $user['login'] ?></td>
				<td><a href="?action=editObject&uId=<?= $user['id'] ?>">EDIT</a></td>
				<td><a href="?action=deleteObject&uId=<?= $user['id'] ?>">DELETE</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
	else if($viewMode[$iv] == "add"){
		?>
		<h2>Add User</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="addObject">
			<table>
				<tr>
					<td><label for="uLogin">Login:</label></td>
					<td><input type="text" name="uLogin" id="uLogin" required></td>
				</tr>
				<tr>
					<td><label for="uName">Username:</label></td>
					<td><input type="text" name="uName" id="uName"></td>
				</tr>
				<tr>
					<td><label for="uPass">Password:</label></td>
					<td><input type="password" name="uPass" id="uPass" required></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Create"></td>
				</tr>
			</table>
		</form>
		<?php
	}
	else if($viewMode[$iv] == "edit"){
		?>
		<h2>Edit User</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="updateObject">
			<table>
				<tr>
					<td><label for="uId">Id:</label></td>
					<td><input type="text" name="uId" id="uId" value="<?= $uInfo['id'] ?>" readonly></td>
				</tr>
				<tr>
					<td><label for="uLogin">Login:</label></td>
					<td><input type="text" name="uLogin" id="uLogin" value="<?= $uInfo['login'] ?>"></td>
				</tr>
				<tr>
					<td><label for="uName">Username:</label></td>
					<td><input type="text" name="uName" id="uName" value="<?= $uInfo['username'] ?>" ></td>
				</tr>
				<tr>
					<td><label for="uPass">Password:</label></td>
					<td><input type="password" name="uPass" id="uPass"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Update"></td>
				</tr>
			</table>
		</form>
		<a href="users.php"><button>Back</button></a>
		<?php
	}
	else if($viewMode[$iv] == "del"){
		?>
		<h2>Remove User</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="removeObject">
			<input type="hidden" name="uId" value="<?= $uId ?>">
			<p>Are you sure you want to remove the user?</p>
			<input type="submit" value="Remove">
		</form>
		<a href="users.php"><button>Back</button></a>
		<?php
	}
}

include("../inc/admin-footer.php");
?>