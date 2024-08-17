<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin</title>
	<link type="text/css" rel="stylesheet" href="../css/admin-style.css">
	<script src="../js/admin-script.js"></script>
</head>
<body>
<div class="wrapper">
	<header>
	<?php
	switch (basename($_SERVER['PHP_SELF'])) {
		case "admin-home.php": echo "<h1>Admin Home</h1>"; break;
		case "categories.php": echo "<h1>Categories CRUD Interface</h1>"; break;
		case "infosections.php": echo "<h1>Info Sections CRUD Interface</h1>"; break;
		case "news.php": echo "<h1>News CRUD Interface</h1>"; break;
		case "products.php": echo "<h1>Products CRUD Interface</h1>"; break;
		case "users.php": echo "<h1>Users CRUD Interface</h1>"; break;
		case "image-galery.php": echo "<h1>Images Galery</h1>"; break;
	}
	?>
	</header>
	<main>
	<table border=1>
		<td>Panel menu: </td>
		<td><a href="../index.php?action=makeLogout">Log Out</a></td>
		<td><a href="../modules/users.php">Users</a></td>
		<td><a href="../modules/products.php">Products</a></td>
		<td><a href="../modules/categories.php">Categories</a></td>
		<td><a href="../modules/infosections.php">Info Sections</a></td>
		<td><a href="../modules/news.php">News</a></td>
		<td><a href="../modules/image-galery.php">Images Galery</a></td>
	</table>