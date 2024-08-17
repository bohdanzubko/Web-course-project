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

$sId = Request::getVar("sId", null);
$sName = Request::getVar("sName", null);
$sURL = Request::getVar("sURL", null);
$sImageId = Request::getVar("sImageId", null);

/** viewMode
 * list - table of objects
 * add - table for creation
 * edit - table for editing
 * del - deletion allow
 */
$viewMode = Array();

$msg = "";

$InfoSections = new InfoSectionModel($db);
$InfoSectionsList = $InfoSections->getList();

$Images = new ImageModel($db);
$ImagesList = $Images->getList();

switch ($action) {
	case "editObject":
		if(intval($sId) == 0)
			break;
		
		$viewMode[] = "edit";

		$sInfo = $InfoSections->getItem($sId);
		break;
	case "deleteObject":
		if(intval($sId) == 0)
			break;
		
		$viewMode[] = "del";

		break;
	case "addObject":
		//Test Name for unique
		foreach($InfoSectionsList as $section){
			if($sName == $section['name']){
				$msg = "This section name is already taken!";
				break;
			}
		}

		if($msg == "This section name is already taken!"){
			break;
		}

		if(!isset($sImageId)){
			$msg = "Choose the category image!";
			break;
		}

		//Create new section
		if(!$InfoSections->addItem($sName, $sImageId, $sURL)){
			$msg = "Unable to create the section!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "updateObject":
		if(intval($sId) == 0)
			break;

		//Test Name for unique
		foreach($InfoSectionsList as $section){
			if($section['id'] == $sId)
				continue;
			if($sName == $section['name']){
				$msg = "This section name is already taken!";
				break;
			}
		}

		if($msg == "This section name is already taken!"){
			break;
		}

		if(!isset($sImageId)){
			$msg = "Choose the category image!";
			break;
		}

		//Create new section and get it ID
		if(!$InfoSections->updateItem($sId, $sName, $sImageId, $sURL)){
			$msg = "Unable to update the section!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "removeObject":
		if(intval($sId) == 0)
			break;
		
		//Remove section
		if(!$InfoSections->deleteItem($sId)){
			$msg = "Unable to remove section!";
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
		<h2>Table of Sections</h2>
		<table border=1>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Images</th>
				<th>URL</th>
				<th>EDIT</th>
				<th>DELETE</th>
			</tr>
			<?php foreach ($InfoSectionsList as $section) : ?>
			<tr>
				<td><?= $section['id'] ?></td>
				<td><?= $section['name'] ?></td>
				<td><?php
					$image = $Images->getItem($section['image_id']);
					echo '<img style="height: 200px;" src="../../'.$image['path'].'" alt="">';
				?></td>
				<td><?= $section['url'] ?></td>
				<td><a href="?action=editObject&sId=<?= $section['id'] ?>">EDIT</a></td>
				<td><a href="?action=deleteObject&sId=<?= $section['id'] ?>">DELETE</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
	else if($viewMode[$iv] == "add"){
		?>
		<h2>Add Section</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="addObject">
			<table border=1>
				<tr>
					<td><label for="sName">Name:</label></td>
					<td><input type="text" name="sName" id="sName" required></td>
				</tr>
				<tr>
					<td><label for="sURL">URL:</label></td>
					<td><input type="text" name="sURL" id="sURL" required></td>
				</tr>
			</table>
			<p><b>Select section image</b></p>
			<table border=1>
				<tr>
					<th>Sections Images:</th>
				</tr>
				<tr>
					<td><div class="img-output">
					<?php
					foreach($ImagesList as $image){
						if(strpos($image['path'], "info_sections")){
							echo '<div>
								<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
								<input type="radio" name="sImageId" id="image'.$image['id'].'" value="'.$image['id'].'">
							</div>';
						}
					}
					?>
					</div></td>
				</tr>
			</table>
			<p><b>Or upload new Image to the <a href="image-galery.php">Image Galery</a></b></p>
			<input type="submit" value="Create">
		</form>
		<?php
	}
	else if($viewMode[$iv] == "edit"){
		?>
		<h2>Edit Section</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="updateObject">
			<table border=1>
				<tr>
					<td><label for="sId">ID:</label></td>
					<td><input type="text" name="sId" id="sId" value="<?= $sInfo['id'] ?>" readonly required></td>
				</tr>
				<tr>
					<td><label for="sName">Name:</label></td>
					<td><input type="text" name="sName" id="sName" value="<?= $sInfo['name'] ?>" required></td>
				</tr>
				<tr>
					<td><label for="sURL">URL:</label></td>
					<td><input type="text" name="sURL" id="sURL" value="<?= $sInfo['url'] ?>" required></td>
				</tr>
			</table>
			<p><b>Select section image</b></p>
			<table border=1>
				<tr>
					<th>Sections Images:</th>
				</tr>
				<tr>
					<td><div class="img-output">
					<?php
					foreach($ImagesList as $image){
						if(strpos($image['path'], "info_sections")){
							echo '<div>
								<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
								<input type="radio" name="sImageId" id="image'.$image['id'].'" value="'.$image['id'].'" '.($image['id'] == $sInfo['image_id'] ? 'checked' : '').'>
							</div>';
						}
					}
					?>
					</div></td>
				</tr>
			</table>
			<p><b>You can also upload new Image to the <a href="image-galery.php">Image Galery</a></b></p>
			<input type="submit" value="Update">
		</form>
		<a href="infosections.php"><button>Back</button></a>
		<?php
	}
	else if($viewMode[$iv] == "del"){
		?>
		<h2>Remove Section</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="removeObject">
			<input type="hidden" name="pId" value="<?= $pId ?>">
			<p>Are you sure you want to remove the section?</p>
			<input type="submit" value="Remove">
		</form>
		<a href="infosections.php"><button>Back</button></a>
		<?php
	}
}
?>

<?php
include("../inc/admin-footer.php");
?>