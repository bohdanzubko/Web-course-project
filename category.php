<?php
include "config/init.php";

$Products = new ProductModel($db);
$Categories = new CategoryModel($db);
$Images = new ImageModel($db);
$I2P = new I2PModel($db);

$pCatId = Request::getVar("id", null);
$ProductsList = $Products->getList($pCatId);

$category = $Categories->getItem($pCatId);
$cImage = $Images->getItem($category['image_id']);

include "inc/header.php"
?>
	<main>
	<div class="cat-bg">
			<h2 class="cat-title"><?=$category['name']?></h2>
			<img class="img-bg" src="<?=$cImage['path']?>" alt="<?=$category['name']?>">
		</div>
		<section class="sect">
			<div class="sect-container">
				<div class="filters-container">
					<div>
						Filters
					</div>
					<form class="filters-form">
						<div class="price-container">
							<label for="min-price">Price:&nbsp;</label>
							<input type="number" id="min-price" name="min-price" step="0.01" min="0.00" placeholder="0.00">
							<span>&nbsp;-&nbsp;</span>
							<input type="number" id="max-price" name="max-price" step="0.01" min="0.00" placeholder="0.00">
						</div>
						<select name="type">
							<option value="">Type</option>
							<option value="wallets">Wallets</option>
							<option value="cardholders">Cardholders</option>
						</select>
						<select name="colors">
							<option value="">Color</option>
							<option value="red">Red</option>
							<option value="green">Green</option>
							<option value="blue">Blue</option>
							<option value="black">Black</option>
						</select>
						<select name="furniture">
							<option value="">Steel furniture</option>
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</form>
				</div>
				<?php
				foreach ($ProductsList as $product){
					$iIds = $I2P->getImage($product['id']);
					$image = $Images->getItem($iIds[0]['image_id']);
					echo '<a href="product.php?id='.$product['id'].'">
					<div class="card prod-card">
						<p class="card-name">'.$product['name'].'</p>
						<img class="img-bg" src="'.$image['path'].'" alt="'.$product['name'].'"> 
						<p class="prod-price">₴'.$product['price'].'</p>
					</div>
				</a>';
				}?>
			</div>
		</section>
	</main>
<?php
include "inc/footer.php"
?>
