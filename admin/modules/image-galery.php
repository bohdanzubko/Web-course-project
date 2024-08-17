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
 * addImage - photo creation
 * deleteImage - photo deletion
 */
$action = Request::getVar("action", "");
$iCat = Request::getVar("iCat", "");


$msg = "";

$Images = new ImageModel($db);
$ImagesList = $Images->getList();

$Products = new ProductModel($db);
$ProductsList = $Products->getList();

$Categories = new CategoryModel($db);
$CategoriesList = $Categories->getList();

$InfoSections = new InfoSectionModel($db);
$InfoSectionsList = $InfoSections->getList();

$News = new NewsModel($db);
$NewsList = $News->getList();

switch ($action) {
	case "addImage":
		// Print the $_FILES array to verify contents
		if(DEBUG_MODE == true){
			echo '<pre> File info:';
			var_dump($_FILES);
			echo '</pre>';
		}
		
		switch($iCat){
			case 'products':
				$fullPath = __DIR__ . "/../../img/jpg/products/";
				$imageDirectory = "img/jpg/products/";
				break;
			case 'categories':
				$fullPath = __DIR__ . "/../../img/jpg/categories/";
				$imageDirectory = "img/jpg/categories/";
				break;
			case 'info_sections':
				$fullPath = __DIR__ . "/../../img/jpg/info_sections/";
				$imageDirectory = "img/jpg/info_sections/";
				break;
			case 'news':
				$fullPath = __DIR__ . "/../../img/jpg/news/";
				$imageDirectory = "img/jpg/news/";
				break;
		}

		// Get the full path
		$fullPath .= basename($_FILES['iPath']['name']);

		//Get the relative path
		$iPath = $imageDirectory . basename($_FILES['iPath']['name']);

		//Get the file type
		$fileType = strtolower(pathinfo($iPath, PATHINFO_EXTENSION));

		// Check if the path exists
		if ($fullPath === false) {
			$msg = "Folder not found or invalid path.<br>";
			break;
		}

		// Check if file already exists
		if (file_exists($fullPath)) {
			$msg = "File already exists!";
			break;
		}

		// Check file size
		if ($_FILES['iPath']['size'] > 1000000) {
			$msg = "File is too large!";
			break;
		}

		// Allow certain file formats
		if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
			$msg = "Only JPG, JPEG, PNG & GIF files are allowed!";
			break;
		}
		
		if (!move_uploaded_file($_FILES['iPath']['tmp_name'], $fullPath)) {
			$msg = "Error occurred during file upload.";
			break;
		}

		$Images->addItem($iPath);
		
		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "deleteImage":
		//Get ID of all selected images
		$imageId = Array();
		$lastImage = end($ImagesList);
		
		for($i = 1; $i <= $lastImage['id']; $i++){
			$image = Request::getVar("image".$i, null);

			if($image != null)
				$imageId[] = intval($image);
		}

		foreach($ProductsList as $product){
			if(in_array($product['image_id'], $imageId))
				$msg = "!";
		}

		foreach($CategoriesList as $category){
			if(in_array($category['image_id'], $imageId))
				$msg = "!";
		}

		foreach($InfoSectionsList as $section){
			if(in_array($section['image_id'], $imageId))
				$msg = "!";
		}		
		
		foreach($NewsList as $news){
			if(in_array($news['image_id'], $imageId))
				$msg = "!";
		}

		if($msg == "!"){
			$msg = "First you need to delete the object with the image you selected!";
			break;
		}

		for($iId = 0; $iId < count($imageId); $iId++){			
			//Remove Image from server folder
			$image = $Images->getItem($imageId[$iId]);
			$fileToRemove = __DIR__ . "/../../" . $image['path'];

			// Check if the file exists before attempting to delete it
			if (file_exists($fileToRemove)) {
				if (!unlink($fileToRemove)) {
					$msg = "Error deleting file.";
				}
			} 
			else {
				$msg = "File does not exist.";
			}

			//Delete Image with this ID
			$Images->deleteItem($imageId[$iId]);
		}
		
		header("Location: " . $_SERVER['PHP_SELF']);
		break;
}

include("../inc/admin-header.php");

if($msg != ""){
	echo '<div style="color: red;">'.$msg.'</div>';
}
?>
	<h2 id="add-img">Add Image</h2>
	<form action="<?= $PHP_SELF; ?>" method="POST"  enctype="multipart/form-data">
		<input type="hidden" name="action" value="addImage">
		<table border="1">
			<tr>
				<td><label for="iPath">Select Image:</label></td>
				<td><input type="file" name="iPath" id="iPath"></td>
			</tr>
			<tr>
				<td><label for="iCat">Select Image type:</label></td>
				<td>
					<select name="iCat" id="iCat" value="" required>
						<option value=""></option>
						<option value="products">Products</option>
						<option value="categories">Categories</option>
						<option value="info_sections">Info Sections</option>
						<option value="news">News</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="Upload Image"></td>
			</tr>
		</table>
	</form>
	<h2>Images list</h2>
	<p>Choose any images you want to delete</p>
	<form action="<?= $PHP_SELF; ?>" method="POST">
		<input type="hidden" name="action" value="deleteImage">
		<table border=1>
			<tr>
				<th>Images:</th>
			</tr>
			<tr>
				<td>
				<p>Products Images:</p>
				<div class="img-output">
				<?php
				foreach($ImagesList as $image){
					if(strpos($image['path'], "products")){
						echo '<div>
							<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
							<input type="checkbox" name="image'.$image['id'].'" id="image'.$image['id'].'" value="'.$image['id'].'">
						</div>';
					}
				}
				?>
				</div>
			</tr>
			<tr>
				<td>
				<p>Categories Images:</p>
				<div class="img-output">
				<?php
				foreach($ImagesList as $image){
					if(strpos($image['path'], "categories")){
						echo '<div>
							<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
							<input type="checkbox" name="image'.$image['id'].'" id="image'.$image['id'].'" value="'.$image['id'].'">
						</div>';
					}
				}
				?>
				</div>
			</tr>
			<tr>
				<td>
				<p>Info Sections Images:</p>
				<div class="img-output">
				<?php
				foreach($ImagesList as $image){
					if(strpos($image['path'], "info_sections")){
						echo '<div>
							<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
							<input type="checkbox" name="image'.$image['id'].'" id="image'.$image['id'].'" value="'.$image['id'].'">
						</div>';
					}
				}
				?>
				</div>
			</tr>
			<tr>
				<td>
				<p>News Images:</p>
				<div class="img-output">
				<?php
				foreach($ImagesList as $image){
					if(strpos($image['path'], "news")){
						echo '<div>
							<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
							<input type="checkbox" name="image'.$image['id'].'" id="image'.$image['id'].'" value="'.$image['id'].'">
						</div>';
					}
				}
				?>
				</div>
			</tr>
		</table>
		<input type="submit" value="Remove">
	</form>
<?php
include("../inc/admin-footer.php");
?>