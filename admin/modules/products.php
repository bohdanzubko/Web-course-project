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

$pId = Request::getVar("pId", null);
$pCatId = Request::getVar("pCatId", null);
$pName = Request::getVar("pName", null);
$pPrice = Request::getVar("pPrice", null);
$pDesc = Request::getVar("pDesc", null);
$pInStock = Request::getVar("pInStock", null);

/** viewMode
 * list - table of objects
 * add - table for creation
 * edit - table for editing
 * del - deletion allow
 */
$viewMode = Array();

$msg = "";

$Products = new ProductModel($db);
$ProductsList = $Products->getList();

$Categories = new CategoryModel($db);
$CategoriesList = $Categories->getList();

$Sizes = new SizeModel($db);

$Images = new ImageModel($db);
$ImagesList = $Images->getList();

$Colors = new ColorModel($db);
$ColorsList = $Colors->getList();

$I2P = new I2PModel($db);
$I2PList = $I2P->getList();

$C2P = new C2PModel($db);
$C2PList = $C2P->getList();

switch ($action) {
	case "editObject":
		if(intval($pId) == 0)
			break;
		
		$viewMode[] = "edit";

		$pInfo = $Products->getItem($pId);
		break;
	case "deleteObject":
		if(intval($pId) == 0)
			break;
		
		$viewMode[] = "del";

		break;
	case "addObject":
		//Test Name for unique
		foreach($ProductsList as $product){
			if($pName == $product['name']){
				$msg = "This product name is already taken!";
				break;
			}
		}

		if($msg == "This product name is already taken!"){
			break;
		}

		//Get Size parameters
		$sWidth = Request::getVar("sWidth", null);
		$sHeight = Request::getVar("sHeight", null);
		$sBottomHeight = Request::getVar("sBottomHeight", null);

		//Create Sizes for Product
		$pSizeId = $Sizes->addItem($sWidth, $sHeight, $sBottomWidth);

		//Create new Product and get it ID
		$pId = $Products->addItem($pCatId, $pName, $pPrice, $pDesc, $pSizeId, $pInStock);

		if(!$pId){
			$msg = "Unable to create the product!";
			break;
		}

		//Get ID of all selected colors
		$colorId = Array();

		for($i = 1; $i <= count($ColorsList); $i++){
			$color = Request::getVar("color".$i, null);
			
			if($color != null)
				$colorId[] = intval($color);
		}
		//Create connnections Color to Product
		foreach($colorId as $cId){
			$C2P->addItem($cId, $pId);
		}

		//Get ID of all selected images
		$imageId = Array();
		$lastImage = end($ImagesList);
		
		for($i = 1; $i <= $lastImage['id']; $i++){
			$image = Request::getVar("image".$i, null);

			if($image != null)
				$imageId[] = intval($image);
		}
		//Create connnections Image to Product
		foreach($imageId as $iId){
			$I2P->addItem($iId, $pId);
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "updateObject":
		if(intval($pId) == 0)
			break;
		//Test Name for unique
		foreach($ProductsList as $product){
			if($product['id'] == $pId)
				continue;
			if($pName == $product['name']){
				$msg = "This product name is already taken!";
				break;
			}
		}

		if($msg == "This product name is already taken!"){
			break;
		}

		if($pInStock == null){
			$pInStock = 0;
		}

		$product = $Products->getItem($pId);
		$Sizes->deleteItem($product['size_id']);

		//Get Size parameters
		$sWidth = Request::getVar("sWidth", null);
		$sHeight = Request::getVar("sHeight", null);
		$sBottomHeight = Request::getVar("sBottomHeight", null);

		//Create Sizes for Product
		$pSizeId = $Sizes->addItem($sWidth, $sHeight, $sBottomWidth);

		//Create new Product and get it ID
		if(!$Products->updateItem($pId, $pCatId, $pName, $pPrice, $pDesc, $pSizeId, $pInStock)){
			$msg = "Unable to update the product!";
			break;
		}

		//Remove connection Color to Product
		foreach($C2PList as $c2p){
			if($c2p['product_id'] == $pId){
				$C2P->deleteItem($c2p['id']);
			}
		}

		//Get ID of all selected colors
		$colorId = Array();

		for($i = 1; $i <= count($ColorsList); $i++){
			$color = Request::getVar("color".$i, null);
			
			if($color != null)
				$colorId[] = intval($color);
		}
		//Create connnections Color to Product
		foreach($colorId as $cId){
			$C2P->addItem($cId, $pId);
		}
		
		//Remove connection Image to Product
		foreach($I2PList as $i2p){
			if($i2p['product_id'] == $pId){
				$I2P->deleteItem($i2p['id']);
			}
		}

		//Get ID of all selected images
		$imageId = Array();
		$lastImage = end($ImagesList);
		
		for($i = 1; $i <= $lastImage['id']; $i++){
			$image = Request::getVar("image".$i, null);

			if($image != null)
				$imageId[] = intval($image);
		}
		//Create connnections Image to Product
		foreach($imageId as $iId){
			$I2P->addItem($iId, $pId);
		}

		header("Location: " . $_SERVER['PHP_SELF']);
		break;
	case "removeObject":
		if(intval($pId) == 0)
			break;
		$product = $Products->getItem($pId);

		//Remove connection Color to Product
		foreach($C2PList as $c2p){
			if($c2p['product_id'] == $product['id']){
				$C2P->deleteItem($c2p['id']);
			}
		}
		
		//Remove connection Image to Product
		foreach($I2PList as $i2p){
			if($i2p['product_id'] == $product['id']){
				$I2P->deleteItem($i2p['id']);
			}
		}
		
		//Remove Product
		if(!$Products->deleteItem($pId)){
			$msg = "Unable to remove product!";
			break;
		}
		
		//Remove sizes of this product
		$Sizes->deleteItem($product['size_id']);

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
		<h2>Table of Products</h2>
		<table border=1>
			<tr>
				<th>ID</th>
				<th>Category</th>
				<th>Name</th>
				<th>Price</th>
				<th>Description</th>
				<th>Sizes</th>
				<th>Colors</th>
				<th>Images</th>
				<th>Available</th>
				<th>EDIT</th>
				<th>DELETE</th>
			</tr>
			<?php foreach ($ProductsList as $product) : ?>
			<tr>
				<td><?= $product['id'] ?></td>
				<td><?php 
					$category = $Categories->getItem($product['category_id']);
					echo "<span>".$category['name']."</span>";
				?></td>
				<td><?= $product['name'] ?></td>
				<td><?= $product['price'] ?></td>
				<td style="max-width: 300px;"><?= $product['description'] ?></td>
				<td><?php 
					$size = $Sizes->getItem($product['size_id']);
					echo "<span>Width: ".$size['width']."<br>
						Height: ".$size['height']."<br>
						Bottom width: ".$size['bottom_width']."</span>";
				?></td>
				<td><?php
					foreach($C2PList as $c2p){
						if($c2p['product_id'] == $product['id']){
							$color = $Colors->getItem($c2p['color_id']);
							echo "<span style='background-color: #".$color['hex'] ."; color: white;'>
									<b>".$color['name']."</b>
								</span><br>";
						}
					}
				?></td>
				<td><div class="img-output" style="grid-template-columns: repeat(4, 1fr);"><?php
					foreach($I2PList as $i2p){
						if($i2p['product_id'] == $product['id']){
							$image = $Images->getItem($i2p['image_id']);
							echo '<img style="width: 100px;" src="../../'.$image['path'].'" alt="">';
						}
					}
				?></div></td>
				<td><?php 
					switch($product['in_stock']){
						case 0: echo '<span>Out of stock</span>'; break;
						case 1: echo '<span>In stock</span>'; break;
					}
				?></td>
				<td><a href="?action=editObject&pId=<?= $product['id'] ?>">EDIT</a></td>
				<td><a href="?action=deleteObject&pId=<?= $product['id'] ?>">DELETE</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
	else if($viewMode[$iv] == "add"){
		?>
		<h2>Add Product</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="addObject">
			<table border=1>
				<tr>
					<th colspan="2">General:</th>
				</tr>
				<tr>
					<td>Category:</td>
					<td><select name="pCatId" id="pCatId" required>
					<option value=""></option>
					<?php
						foreach($CategoriesList as $category){
							echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
					?>
					</select></td>
				</tr>
				<tr>
					<td><label for="pName">Name:</label></td>
					<td><input type="text" name="pName" id="pName" required></td>
				</tr>
				<tr>
					<td><label for="pPrice">Price:</label></td>
					<td><input type="number" name="pPrice" id="pPrice" step="0.01" min="0.00" placeholder="0.00" required></td>
				</tr>
				<tr>
					<td><label for="pDesc">Description:</label></td>
					<td><textarea name="pDesc" id="pDesc"></textarea></td>
				</tr>
				<tr>
					<td colspan="2">
						<label for="pInStock">Available </label>
						<input type="checkbox" name="pInStock" id="pInStock" value="1">
					</td>
				</tr>
			</table>
			<table border=1>
				<tr>
					<th colspan="2">Size:</th>
				</tr>
				<tr>
					<td><label for="sWidth">Width:</label></td>
					<td><input type="text" name="sWidth" id="sWidth" required></td>
				</tr>
				<tr>
					<td><label for="sHeight">Height:</label></td>
					<td><input type="text" name="sHeight" id="sHeight" required></td>
				</tr>
				<tr>
					<td><label for="sBottomHeight">Bottom height:</label></td>
					<td><input type="text" name="sBottomHeight" id="sBottomHeight"></td>
				</tr>
			</table>
			<table border=1>
				<tr>
					<th>Colors:</th>
				</tr>
				<tr>
					<td><?php
					foreach($ColorsList as $color){
						echo '<label for="color'.$color['id'].'" style="background-color: #'.$color['hex'].'; color: white;">'.$color['name'].'</label>';
						echo '<input type="checkbox" name="color'.$color['id'].'" id="color'.$color['id'].'" value="'.$color['id'].'"><br>';
					}
					?>
					</td>
				</tr>
			</table>
			<p><b>Select any images you want to add to Product</b></p>
			<table border=1>
				<tr>
					<th>Products Images:</th>
				</tr>
				<tr>
					<td>
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
			</table>
			<p><b>Or upload new Image to the <a href="image-galery.php">Image Galery</a></b></p>
			<input type="submit" value="Create">
		</form>
		<?php
	}
	else if($viewMode[$iv] == "edit"){
		?>
		<h2>Edit Product</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="updateObject">
			<table border=1>
				<tr>
					<th colspan="2">General:</th>
				</tr>
				<tr>
					<td><label for="pId">ID:</label></td>
					<td><input type="text" name="pId" id="pId" value="<?=$pInfo['id']?>" readonly required></td>
				</tr>
				<tr>
					<td>Category:</td>
					<td><select name="pCatId" id="pCatId" value="<?=$pInfo['category_id']?>" required>
					<option value=""></option>
					<?php
						foreach($CategoriesList as $category){
							echo '<option value="'.$category['id'].'"'.($category['id'] == $pInfo['category_id'] ? ' selected' : '').'>'.$category['name'].'</option>';
						}
					?>
					</select></td>
				</tr>
				<tr>
					<td><label for="pName">Name:</label></td>
					<td><input type="text" name="pName" id="pName" value="<?=$pInfo['name']?>" required></td>
				</tr>
				<tr>
					<td><label for="pPrice">Price:</label></td>
					<td><input type="number" name="pPrice" id="pPrice" step="0.01" min="0.00" placeholder="0.00" value="<?=$pInfo['price']?>" required></td>
				</tr>
				<tr>
					<td><label for="pDesc">Description:</label></td>
					<td><textarea name="pDesc" id="pDesc" style="width: 400px; height: 100px;"><?=$pInfo['description']?></textarea></td>
				</tr>
				<tr>
					<td colspan="2">
						<label for="pInStock">Available </label>
						<input type="checkbox" name="pInStock" id="pInStock" value="1" <?php echo $pInfo['in_stock'] ? "checked" : ""; ?>>
					</td>
				</tr>
			</table>
			<table border=1>
				<?php
					$pSize = $Sizes->getItem($pInfo['size_id']);
				?>
				<tr>
					<th colspan="2">Size:</th>
				</tr>
				<tr>
					<td><label for="sWidth">Width:</label></td>
					<td><input type="text" name="sWidth" id="sWidth" value="<?= $pSize['width'] ?>" required></td>
				</tr>
				<tr>
					<td><label for="sHeight">Height:</label></td>
					<td><input type="text" name="sHeight" id="sHeight" value="<?= $pSize['height'] ?>"  required></td>
				</tr>
				<tr>
					<td><label for="sBottomHeight">Bottom height:</label></td>
					<td><input type="text" name="sBottomHeight" id="sBottomHeight" value="<?= $pSize['bottom_width'] ?>" ></td>
				</tr>
			</table>
			<table border=1> 
				<tr>
					<th>Colors:</th>
				</tr>
				<tr>
					<td><?php
					$colorsId = Array();
					foreach($C2PList as $c2p){
						if($c2p['product_id'] == $pInfo['id'])
							$colorsId[] = $c2p['color_id'];
					}
					foreach($ColorsList as $color){
						echo '<label for="color'.$color['id'].'" style="background-color: #'.$color['hex'].'; color: white;">'.$color['name'].'</label>';
						echo '<input type="checkbox" name="color'.$color['id'].'" id="color'.$color['id'].'" value="'.$color['id'].'" '.(in_array($color['id'], $colorsId) ? "checked" : "").'><br>';
					}
					?>
					</td>
				</tr>
			</table>
			<p><b>Select or unselect any images you want to change for Product</b></p>
			<table border=1>
				<tr>
					<th>Products Images:</th>
				</tr>
				<tr>
					<td>
					<div class="img-output">
					<?php
					$colorsId = Array();
					foreach($I2PList as $i2p){
						if($i2p['product_id'] == $pInfo['id'])
							$imagesId[] = $i2p['image_id'];
					}
					$iId = 0;
					foreach($ImagesList as $image){
						if(strpos($image['path'], "products")){
						echo '<div>
							<img style="width: 200px;" src="../../'.$image['path'].'" alt="">
							<input type="checkbox" name="image'.$image['id'].'" id="image'.$image['id'].'" value="'.$image['id'].'" '.(in_array($image['id'], $imagesId) ? "checked" : "").'>
						</div>';
						}
						$iId++;
					}
					?>
					</div>
				</tr>
			</table>
			<p><b>You can also upload new Image to the <a href="image-galery.php">Image Galery</a></b></p>
			<input type="submit" value="Update">
		</form>
		<a href="products.php"><button>Back</button></a>
		<?php
	}
	else if($viewMode[$iv] == "del"){
		?>
		<h2>Remove Product</h2>
		<form action="<?= $PHP_SELF; ?>" method="POST">
			<input type="hidden" name="action" value="removeObject">
			<input type="hidden" name="pId" value="<?= $pId ?>">
			<p>Are you sure you want to remove the Product?</p>
			<input type="submit" value="Remove">
		</form>
		<a href="products.php"><button>Back</button></a>
		<?php
	}
}

include("../inc/admin-footer.php");
?>