<?php
include "config/init.php";

$Categories = new CategoryModel($db);

$InfoSections = new InfoSectionModel($db);
$InfoSectionsList = $InfoSections->getList();

$News = new NewsModel($db);
$NewsList = $News->getList();

$Images = new ImageModel($db);

include "inc/header.php"
?>
	<main>
		<div class="slider-container">
			<div class="slider">
			<?php foreach ($NewsList as $news){
				$image = $Images->getItem($news['image_id']);
				echo '<div class="slide">
					<img class="img-bg" src="'.$image['path'].'">
					<h3 class="sld-title">'.$news['title'].'</h3>
					<p class="sld-content">'.$news['description'].'</p>
					<a href="'.$news['url'].'"><button class="wh-btn sld-btn">Learn more</button></a>
				</div>';
			}?>
			</div>
			<button class="prev"><img src="img/svg/prev_arrow.svg"></button>
			<button class="next"><img src="img/svg/next_arrow.svg"></button>
			<div class="dots-container"></div>
		</div>
		<section class="sect">
			<h2>Catalog</h2>
			<div class="sect-container">
			<?php
			$CategoriesList = $Categories->getList(0, 7);
			foreach ($CategoriesList as $category){
				$image = $Images->getItem($category['image_id']);
				echo '<div class="card categ-card">
					<p class="card-name">'.$category['name'].'</p>
					<img class="img-bg card-bg" src="'.$image['path'].'" alt="'.$category['name'].'"> 
					<a href="category.php?id='.$category['id'].'"><button class="wh-btn">View</button></a>
				</div>';
			}?>
			</div>
			<a href="catalog.php"><button class="bl-btn cat-btn">All categories</button></a>
		</section>
		<section class="sect">
			<h2>Additional</h2>
			<div class="sect-container">
			<?php
			foreach ($InfoSectionsList as $section){
				$image = $Images->getItem($section['image_id']);
				echo '<div class="card addit-card">
					<img class="img-bg card-bg" src="'.$image['path'].'" alt="'.$section['name'].'">
					<p class="card-name">'.$section['name'].'</p>
					<a href="'.$section['url'].'"><button class="wh-btn">View</button></a>
				</div>';
			}?>
		</section>
		<script src="js/slider.js"></script>
	</main>
<?php
include "inc/footer.php"
?>
