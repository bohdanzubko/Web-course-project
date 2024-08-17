<?php
include "config/init.php";

$Products = new ProductModel($db);
$Categories = new CategoryModel($db);
$Sizes = new SizeModel($db);
$Images = new ImageModel($db);
$Colors = new ColorModel($db);
$I2P = new I2PModel($db);
$C2P = new C2PModel($db);

$InfoSections = new InfoSectionModel($db);
$InfoSectionsList = $InfoSections->getList();

$pId = Request::getVar("id", null);
$product = $Products->getItem($pId);
$pSizes = $Sizes->getItem($product['size_id']);
$iIds = $I2P->getImage($pId);
$iArray = Array();
foreach($iIds as $id){
	$iArray[] = $Images->getItem($id['image_id']); 
}

$cIds = $C2P->getImage($pId);
$cArray = Array();
foreach($cIds as $id){
	$cArray[] = $Colors->getItem($id['color_id']); 
}

include "inc/header.php"
?>
	<main>
	<section class="sect prod-section">
			<div class="prod-gallery">
				<img id="main-image" src="<?=$iArray[0]['path']?>" alt="">
				<div class="thumbnails">
				<?php for ($i = 0; $i < sizeof($iArray); $i++): ?>
					<img src="<?=$iArray[$i]['path']?>" alt="" class="thumbnail" onclick="changeImage('<?=$iArray[$i]['path']?>')">
				<?php endfor; ?>
				</div>
			</div>
			<div class="prod-info">
				<p class="prod-name"><?=$product['name']?></p>
				<p class="prod-price">₴<?=$product['price']?></p>
				<hr>
				<p class="color-name"><?=$cArray[0]['name']?></p>
				<div class="color-container">
				<?php for ($i = 0; $i < sizeof($cArray); $i++): ?>
					<a href=""><img src="<?=$cArray[$i]['path']?>" alt="<?=$cArray[$i]['name']?>"></a>
				<?php endfor; ?>
				</div>
				<a href=""><button class="bl-btn">Add to Basket</button></a>
				<p class="prod-desc"><?=$product['description']?></p>
				<p>Sizes:</p>
				<ul class="prod-sizes">
					<li>Width: <?=$pSizes['width']?> cm</li>
					<li>Height: <?=$pSizes['height']?> cm</li>
					<?php
					if($pSizes['bottom_width'] != 0)
						echo '<li>Bottom width: '.$pSizes['bottom_width'].' cm</li>';
					?>
				</ul>
			</div>
		</section>
		<section class="sect">
			<h2>Additional</h2>
			<div class="sect-container">
			<?php
			for($i = 5; $i < sizeof($InfoSectionsList) ; $i++){
				$image = $Images->getItem($InfoSectionsList[$i]['image_id']);
				echo '<div class="card addit-card">
					<img class="img-bg card-bg" src="'.$image['path'].'" alt="'.$InfoSectionsList[$i]['name'].'">
					<p class="card-name">'.$InfoSectionsList[$i]['name'].'</p>
					<a href="'.$InfoSectionsList[$i]['url'].'"><button class="wh-btn">View</button></a>
				</div>';
			}?>
			</div>
		</section>
		<script src="js/prod-gallery.js"></script>
	</main>
<?php
include "inc/footer.php"
?>
