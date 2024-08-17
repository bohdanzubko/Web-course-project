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

$nId = Request::getVar("nId", null);
$nTitle = Request::getVar("nTitle", null);
$nDesc = Request::getVar("nDesc", null);
$nURL = Request::getVar("nURL", null);
$nImageId = Request::getVar("nImageId", null);

/** viewMode
 * list - table of objects
 * add - table for creation
 * edit - table for editing
 * del - deletion allow
 */
$viewMode = Array();

$msg = "";

$News = new NewsModel($db);
$NewsList = $News->getList();

$Images = new ImageModel($db);
$ImagesList = $Images->getList();

switch ($action) {
	case "editObject":
		if(intval($nId) == 0)
			break;
		
		$viewMode[] = "edit";

		$nInfo = $News->getItem($nId);
		break;
	case "deleteObject":
		if(intval($nId) == 0)
			break;
		
		$viewMode[] = "del";

		break;
	case "addObject":
		if(!isset($nImageId)){
			$msg = "Choose the category image!";
			break;
		}

		//Create new news
		if(!$News->addItem($nTitle, $nDesc, $nImageId, $nURL)){
			$msg = "Unable to create the news!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "updateObject":
		if(intval($nId) == 0)
			break;

		if(!isset($nImageId)){
			$msg = "Choose the category image!";
			break;
		}

		//Update news
		if(!$News->updateItem($nId, $nTitle, $nDesc, $nImageId, $nURL)){
			$msg = "Unable to update the news!";
			break;
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "removeObject":
		if(intval($nId) == 0)
			break;
		
		//Remove news
		if(!$News->deleteItem($nId)){
			$msg = "Unable to remove news!";
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
		<h2>Table of News</h2>
		<table border=1>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Description</th>
				<th>Images</th>
				<th>URL</th>
				<th>EDIT</th>
				<th>DELETE</th>
			</tr>
			<?php foreach ($NewsList as $news) : ?>
			<tr>
				<td><?= $news['id'] ?></td>
				<td><?= $news['title'] ?></td>
				<td style="max-width: 300px;"><?= $news['description'] ?></td>
				<td><?php
					$image = $Images->getItem($news['image_id']);
					echo '<img style="height: 200px;" src="../../'.$image['path'].'" alt="">';
				?></td>
				<td><?= $news['url'] ?></td>
				<td><a href="?action=editObject&nId=<?= $news['id'] ?>">EDIT</a></td>
				<td><a href="?action=deleteObject&nId=<?= $news['id'] ?>">DELETE</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
	else if($viewMode[$iv] == "add"){
		?>
		<h2>Add News</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="addObject">
			<table border=1>
				<tr>
					<td><label for="nTitle">Title:</label></td>
					<td><input type="text" name="nTitle" id="nTitle" required></td>
				</tr>
				<tr>
					<td><label for="nDesc">Description:</label></td>
					<td><textarea name="nDesc" id="nDesc" style="width: 400px; height: 100px;"><?=$nInfo['description']?></textarea></td>
				</tr>
				<tr>
					<td><label for="nURL">URL:</label></td>
					<td><input type="text" name="nURL" id="nURL" required></td>
				</tr>
			</table>
			<p><b>Select news image</b></p>
			<table border=1>
				<tr>
					<th>News Images:</th>
				</tr>
				<tr>
					<td><div class="img-output">
					<?php
					foreach($ImagesList as $image){
						if(strpos($image['path'], "news")){
							echo '<div>
								<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
								<input type="radio" name="nImageId" id="image'.$image['id'].'" value="'.$image['id'].'">
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
		<h2>Edit News</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="updateObject">
			<table border=1>
				<tr>
					<td><label for="nId">ID:</label></td>
					<td><input type="text" name="nId" id="nId" value="<?= $nInfo['id'] ?>" readonly required></td>
				</tr>
				<tr>
					<td><label for="nTitle">Title:</label></td>
					<td><input type="text" name="nTitle" id="nTitle" value="<?= $nInfo['title'] ?>" required></td>
				</tr>
				<tr>
					<td><label for="nDesc">Description:</label></td>
					<td><textarea name="nDesc" id="nDesc" style="width: 400px; height: 100px;"><?=$nInfo['description']?></textarea></td>
				</tr>
				<tr>
					<td><label for="nURL">URL:</label></td>
					<td><input type="text" name="nURL" id="nURL" value="<?= $nInfo['url'] ?>" required></td>
				</tr>
			</table>
			<p><b>Select news image</b></p>
			<table border=1>
				<tr>
					<th>News Images:</th>
				</tr>
				<tr>
					<td><div class="img-output">
					<?php
					foreach($ImagesList as $image){
						if(strpos($image['path'], "news")){
							echo '<div>
								<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
								<input type="radio" name="nImageId" id="image'.$image['id'].'" value="'.$image['id'].'" '.($image['id'] == $nInfo['image_id'] ? 'checked' : '').'>
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
		<a href="news.php"><button>Back</button></a>
		<?php
	}
	else if($viewMode[$iv] == "del"){
		?>
		<h2>Remove News</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="removeObject">
			<input type="hidden" name="pId" value="<?= $pId ?>">
			<p>Are you sure you want to remove the news?</p>
			<input type="submit" value="Remove">
		</form>
		<a href="news.php"><button>Back</button></a>
		<?php
	}
}
?>

<?php
include("../inc/admin-footer.php");
?>