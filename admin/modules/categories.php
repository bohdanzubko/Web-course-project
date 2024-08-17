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

$cId = Request::getVar("cId", null);
$cName = Request::getVar("cName", null);
$cType = Request::getVar("cType", null);
$cImageId = Request::getVar("cImageId", null);

/** viewMode
 * list - table of objects
 * add - table for creation
 * edit - table for editing
 * del - deletion allow
 */
$viewMode = Array();

$msg = "";

$Categories = new CategoryModel($db);
$CategoriesList = $Categories->getList();

$Images = new ImageModel($db);
$ImagesList = $Images->getList();

switch ($action) {
	case "editObject":
		if(intval($cId) == 0)
			break;
		
		$viewMode[] = "edit";

		$cInfo = $Categories->getItem($cId);
		break;
	case "deleteObject":
		if(intval($cId) == 0)
			break;
		
		$viewMode[] = "del";

		break;
	case "addObject":
		//Test Name for unique
		foreach($CategoriesList as $category){
			if($cName == $category['name']){
				$msg = "This category name is already taken!";
				break;
			}
		}

		if($msg == "This category name is already taken!"){
			break;
		}

		if(!isset($cImageId)){
			$msg = "Choose the category image!";
			break;
		}

		//Create new Category
		if(!$Categories->addItem($cName, $cType, $cImageId)){
			$msg = "Unable to create the category!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "updateObject":
		if(intval($cId) == 0)
			break;

		//Test Name for unique
		foreach($CategoriesList as $category){
			if($category['id'] == $cId)
				continue;
			if($cName == $category['name']){
				$msg = "This category name is already taken!";
				break;
			}
		}

		if($msg == "This category name is already taken!"){
			break;
		}

		//Create new Category and get it ID
		if(!$Categories->updateItem($cId, $cName, $cType, $cImageId)){
			$msg = "Unable to update the category!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "removeObject":
		if(intval($cId) == 0)
			break;
		
		//Remove Category
		if(!$Categories->deleteItem($cId)){
			$msg = "Unable to remove category!";
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
		<h2>Table of Categories</h2>
		<table border=1>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Type</th>
				<th>Images</th>
				<th>EDIT</th>
				<th>DELETE</th>
			</tr>
			<?php foreach ($CategoriesList as $category) : ?>
			<tr>
				<td><?= $category['id'] ?></td>
				<td><?= $category['name'] ?></td>
				<td><?php 
					switch($category['type']){ 	
						case 1: echo "1 - Backpags and bags"; break;
						case 2: echo "2 - For technology"; break;
						case 3: echo "3 - Others"; break;
					}
				?></td>
				<td><?php
					$image = $Images->getItem($category['image_id']);
					echo '<img style="height: 200px;" src="../../'.$image['path'].'" alt="">';
				?></td>
				<td><a href="?action=editObject&cId=<?= $category['id'] ?>">EDIT</a></td>
				<td><a href="?action=deleteObject&cId=<?= $category['id'] ?>">DELETE</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
	else if($viewMode[$iv] == "add"){
		?>
		<h2>Add Category</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="addObject">
			<table border=1>
				<tr>
					<td><label for="cName">Name:</label></td>
					<td><input type="text" name="cName" id="cName" required></td>
				</tr>
				<tr>
					<td>Type:</td>
					<td><select name="cType" id="cType" required>
						<option value=""></option>
						<option value="1">1 - Backpack and bags</option>
						<option value="2">2 - For technology</option>
						<option value="3">3 - Others</option>
					</select></tr>
			</table>
			<p><b>Select Category image</b></p>
			<table border=1>
				<tr>
					<th>Categories Images:</th>
				</tr>
				<tr>
					<td><div class="img-output">
					<?php
					foreach($ImagesList as $image){
						if(strpos($image['path'], "categories")){
							echo '<div>
								<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
								<input type="radio" name="cImageId" id="image'.$image['id'].'" value="'.$image['id'].'">
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
		<h2>Edit Category</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="updateObject">
			<table border=1>
				<tr>
					<td><label for="cId">ID:</label></td>
					<td><input type="text" name="cId" id="cId" value="<?= $cInfo['id'] ?>" readonly required></td>
				</tr>
				<tr>
					<td><label for="cName">Name:</label></td>
					<td><input type="text" name="cName" id="cName" value="<?= $cInfo['name'] ?>" required></td>
				</tr>
				<tr>
					<td>Type:</td>
					<td><select name="cType" id="cType" required>
						<option value=""></option>
						<option value="1" <?php echo $cInfo['type'] == 1 ? "selected" : "" ?> >1 - Backpack and bags</option>
						<option value="2" <?php echo $cInfo['type'] == 2 ? "selected" : "" ?> >2 - For technology</option>
						<option value="3" <?php echo $cInfo['type'] == 3 ? "selected" : "" ?> >3 - Others</option>
					</select></tr>
			</table>
			<p><b>Select Category image</b></p>
			<table border=1>
				<tr>
					<th>Categories Images:</th>
				</tr>
				<tr>
					<td><div class="img-output">
					<?php
					foreach($ImagesList as $image){
						if(strpos($image['path'], "categories")){
							echo '<div>
								<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
								<input type="radio" name="cImageId" id="image'.$image['id'].'" value="'.$image['id'].'" '.($image['id'] == $cInfo['image_id'] ? 'checked' : '').'>
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
		<a href="categories.php"><button>Back</button></a>
		<?php
	}
	else if($viewMode[$iv] == "del"){
		?>
		<h2>Remove Category</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="removeObject">
			<input type="hidden" name="pId" value="<?= $pId ?>">
			<p>Are you sure you want to remove the Category?</p>
			<input type="submit" value="Remove">
		</form>
		<a href="categories.php"><button>Back</button></a>
		<?php
	}
}
?>

<?php
include("../inc/admin-footer.php");
?>