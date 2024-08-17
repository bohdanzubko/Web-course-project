<?php
include("init.php");
/*
//DROP TABLES
echo "Tables deletion...<br>";
$res = $db->execute("DROP TABLE ".TBL_SESSION);
$res = $db->execute("DROP TABLE ".TBL_USER);
$res = $db->execute("DROP TABLE ".TBL_I2P);
$res = $db->execute("DROP TABLE ".TBL_C2P);
$res = $db->execute("DROP TABLE ".TBL_PRODUCT);
$res = $db->execute("DROP TABLE ".TBL_CATEGORY);
$res = $db->execute("DROP TABLE ".TBL_NEWS);
$res = $db->execute("DROP TABLE ".TBL_INFOSECT);
$res = $db->execute("DROP TABLE ".TBL_SIZE);
$res = $db->execute("DROP TABLE ".TBL_COLOR);
$res = $db->execute("DROP TABLE ".TBL_IMAGE);
echo "Done!<br>";
*/
//CREATE TABLES IF NOT EXISTS
echo "Tables creating...<br>";
$res = $db->execute("CREATE TABLE IF NOT EXISTS".TBL_USER."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(30) DEFAULT '',
	login VARCHAR(30) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_SESSION."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	ses_id VARCHAR(80) NOT NULL UNIQUE,
	user_id INT NOT NULL,
	add_date DATETIME,
	last_access DATETIME,
	FOREIGN KEY (user_id) REFERENCES ".TBL_USER."(id)
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_IMAGE."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	creation_date DATETIME NOT NULL,
	path VARCHAR(200) NOT NULL UNIQUE
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_COLOR."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL UNIQUE,
	hex VARCHAR(10) NOT NULL UNIQUE,
	path VARCHAR(200) NOT NULL UNIQUE
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_SIZE."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	width DECIMAL(6,2) NOT NULL DEFAULT '0.00',
	height DECIMAL(6,2) NOT NULL DEFAULT '0.00',
	bottom_width DECIMAL(6,2) DEFAULT '0.00'
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_INFOSECT."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL UNIQUE,
	image_id INT,
	url VARCHAR(50) NOT NULL,
	FOREIGN KEY (image_id) REFERENCES ".TBL_IMAGE."(id)
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_CATEGORY."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL UNIQUE,
	type INT NOT NULL,
	image_id INT NOT NULL,
	FOREIGN KEY (image_id) REFERENCES ".TBL_IMAGE."(id)
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_PRODUCT."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	category_id INT NOT NULL,
	name VARCHAR(30) NOT NULL UNIQUE,
	price DECIMAL(9,2) NOT NULL,
	description TEXT,
	size_id INT,
	in_stock TINYINT(1) DEFAULT '1',
	FOREIGN KEY (category_id) REFERENCES ".TBL_CATEGORY."(id),
	FOREIGN KEY (size_id) REFERENCES ".TBL_SIZE."(id)
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_I2P."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	image_id INT NOT NULL,
	product_id INT NOT NULL,
	FOREIGN KEY (image_id) REFERENCES ".TBL_IMAGE."(id),
	FOREIGN KEY (product_id) REFERENCES ".TBL_PRODUCT."(id)
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_C2P."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	color_id INT NOT NULL,
	product_id INT NOT NULL,
	FOREIGN KEY (color_id) REFERENCES ".TBL_COLOR."(id),
	FOREIGN KEY (product_id) REFERENCES ".TBL_PRODUCT."(id)
);");

$res = $db->execute("CREATE TABLE IF NOT EXISTS ".TBL_NEWS."(
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	description TEXT,
	image_id INT NOT NULL,
	url VARCHAR(50) NOT NULL,
	FOREIGN KEY (image_id) REFERENCES ".TBL_IMAGE."(id)
);");

echo "All tables were created!<br>";

//User creating
echo "Creating the new user...<br>";
$res = $db->query("INSERT INTO ".TBL_USER."(username, login, password) VALUES('Tester', 'admin', PASSWORD('mypass123'))");
echo "User was created!<br>";