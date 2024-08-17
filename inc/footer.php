<footer>
		<div class="f-content">
			<div class="f-about-us">
				<h3><a href="about-us.php">About us</a></h3>
				<p>
					Leather Goods™ is a Ukrainian brand of genuine leather products. Own production. Handmade.<br><br>
					Our products are an original and pleasant gift for loved ones of any age. A leather product will serve its owner for a long time.
				</p>
				<h3>We are in the socials:</h3>
				<ul class="f-socials">
					<li><a href=""><img class="icon" src="img/svg/facebook_icon.svg" alt=""></a></li>
					<li><a href=""><img class="icon" src="img/svg/instagram_icon.svg" alt=""></a></li>
					<li><a href=""><img class="icon" src="img/svg/telegram_icon.svg" alt=""></a></li>
					<li><a href=""><img class="icon" src="img/svg/twitter_icon.svg" alt=""></a></li>
					<li><a href=""><img class="icon" src="img/svg/linkedIn_icon.svg" alt=""></a></li>
					<li><a href=""><img class="icon" src="img/svg/youtube_icon.svg" alt=""></a></li>
				</ul>
			</div>
			<div class="f-add-info">
				<h3>Additional information</h3>
				<nav>
					<ul class="f-links">
					<?php foreach ($InfoSectionsList as $section){
						if($section['name'] == "About us")
							continue;
						echo '<li><a class="link" href="'.$section['url'].'">'.$section['name'].'</a></li>';
					}?>
					</ul>
				</nav>
			</div>
			<div class="f-contacts">
				<h3>Contacts and work schedule</h3>
				<p>Monday - Friday | from 10:00 to 20:00</p>
				<p>Address: Kharkiv</p>
				<p>Phone number:<a href="tel:+380 95 123 45 67"> +380 95 123 45 67</a></p>
				<p>Mail:<a href="mailto:leathergoodsua@gmail.com"> leathergoodsua@gmail.com</a></p>
			</div>
		</div>
		<p id="copyright">Copyright &copy;2024 leathergoods</p>
	</footer>
</div>
</body>

</html>