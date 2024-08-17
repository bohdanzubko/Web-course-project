<?php
session_start();

define("DEBUG_MODE", false);

// database access parameters
define("DB_HOST", "10.0.0.5");
define("DB_LOGIN", "k503labs_u1");
define("DB_PASS", "ILrZkgUc1S");
define("DB_NAME", "k503labs_db1");

// database tables defines
define("TBL_PREF", "zubko_");

define("TBL_USER", TBL_PREF."user");
define("TBL_SESSION", TBL_PREF."session");
define("TBL_CATEGORY", TBL_PREF."category");
define("TBL_PRODUCT", TBL_PREF."product");
define("TBL_I2P", TBL_PREF."image2product");
define("TBL_C2P", TBL_PREF."color2product");
define("TBL_IMAGE", TBL_PREF."image");
define("TBL_COLOR", TBL_PREF."color");
define("TBL_SIZE", TBL_PREF."size");
define("TBL_NEWS", TBL_PREF."news");
define("TBL_INFOSECT", TBL_PREF."infosection");

spl_autoload_register(function ($className) {
	$classPath = __DIR__.'/../classes/'.$className.'.class.php';
	
	if(strpos($className, "Model") !== false) {
		$classPath = __DIR__.'/../classes/models/'.$className.'.class.php';
	}
	
	if(file_exists($classPath))
	{
		include $classPath;
	}
});

$db = new DB(DB_HOST, DB_LOGIN, DB_PASS, DB_NAME);
$db->setDebug(DEBUG_MODE);

$UserSes = new UserSession($db);