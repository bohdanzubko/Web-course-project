<?php
include "config/init.php";

$Categories = new CategoryModel($db);

$Images = new ImageModel($db);

include "inc/header.php"
?>
	<main>
	<div class="cat-bg">
			<h2 class="cat-title">Leather Goods™ catalog</h2>
			<img class="img-bg" src="img/jpg/title-bg/catalog.jpg">
		</div>
		<section class="sect">
			<h2>Backpacks and Bags</h2>
			<div class="sect-container">
			<?php
			$CategoriesList = $Categories->getList(-1, 10, 1);
			foreach ($CategoriesList as $category){
				$image = $Images->getItem($category['image_id']);
				echo '<div class="card categ-card">
					<p class="card-name">'.$category['name'].'</p>
					<img class="img-bg card-bg" src="'.$image['path'].'" alt="'.$category['name'].'"> 
					<a href="category.php?id='.$category['id'].'"><button class="wh-btn">View</button></a>
				</div>';
			}?>
			</div>
		</section>
		<section class="sect">
			<h2>For technology</h2>
			<div class="sect-container">
			<?php
			$CategoriesList = $Categories->getList(-1, 10, 2);
			foreach ($CategoriesList as $category){
				$image = $Images->getItem($category['image_id']);
				echo '<div class="card categ-card">
					<p class="card-name">'.$category['name'].'</p>
					<img class="img-bg card-bg" src="'.$image['path'].'" alt="'.$category['name'].'"> 
					<a href="category.php?id='.$category['id'].'"><button class="wh-btn">View</button></a>
				</div>';
			}?>
			</div>
		</section>
		<section class="sect">
			<h2>Others</h2>
			<div class="sect-container">
			<?php
			$CategoriesList = $Categories->getList(-1, 10, 3);
			foreach ($CategoriesList as $category){
				$image = $Images->getItem($category['image_id']);
				echo '<div class="card categ-card">
					<p class="card-name">'.$category['name'].'</p>
					<img class="img-bg card-bg" src="'.$image['path'].'" alt="'.$category['name'].'"> 
					<a href="category.php?id='.$category['id'].'"><button class="wh-btn">View</button></a>
				</div>';
			}?>
			</div>
		</section>
	</main>
<?php
include "inc/footer.php"
?>