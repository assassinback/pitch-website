<?php
error_reporting(E_ALL);
ob_start();

session_start();

date_default_timezone_set('Europe/London');
// date_default_timezone_set("Asia/Karachi");

define("SUBDIRECTORY", "");

// define("SITE_URL", "https://77.68.4.68/plesk-site-preview/www.pitchrmt.com/http/77.68.4.68/" . SUBDIRECTORY);
// define("ADMIN_URL", "https://77.68.4.68/plesk-site-preview/www.pitchrmt.com/http/77.68.4.68/" . SUBDIRECTORY . "admin/");
define("SITE_URL", "https://".$_SERVER['SERVER_NAME'] ."/" ."/". SUBDIRECTORY);
define("ADMIN_URL", "https://".$_SERVER['SERVER_NAME'] ."/"."/".  SUBDIRECTORY . "admin/");

define("SITE_PATH", $_SERVER['DOCUMENT_ROOT'] ."/" ."/". SUBDIRECTORY);
define("ADMIN_PATH", $_SERVER['DOCUMENT_ROOT'] ."/" ."/". SUBDIRECTORY . "admin/");

define("APP_ROOT", $_SERVER['DOCUMENT_ROOT'] ."/" ."/". SUBDIRECTORY . "admin/inc/");

define("UPLOAD_PATH", SITE_PATH . "uploads/");
define("UPLOAD_URL", SITE_URL . "uploads/");

define("BLOG_PATH", UPLOAD_PATH . "blog/");
define("BLOG_URL", UPLOAD_URL . "blog/");

define("USER_PATH", UPLOAD_PATH . "user/");
define("USER_URL", UPLOAD_URL . "user/");

define("CONTRIBUTOR_PATH", UPLOAD_PATH . "contributor/");
define("CONTRIBUTOR_URL", UPLOAD_URL . "contributor/");

define("CMS_PATH", UPLOAD_PATH . "cms/");
define("CMS_URL", UPLOAD_URL . "cms/");

// $dbDriver = "mysqli"; 
// $dbPrefix = "pitch_";
// $dbHost = "localhost"; 
// $dbName = "pitchrmt";
// $dbUser = "root";
// $dbPass = ""; 

$dbDriver = "mysqli"; 
$dbPrefix = "pitch_";
$dbHost = "localhost"; 
$dbName = "pitchatnrcom_pitch";
$dbUser = "pitchatnrcom_pitch";
$dbPass = "+?y{dAk~jgRr"; 

@include (ADMIN_PATH . "/inc/database/DB_driver.php");
@include (ADMIN_PATH . "/inc/database/DB_query_builder.php");

if (!@include (ADMIN_PATH . "/inc/database/drivers/".$dbDriver."/".$dbDriver."_driver.php")) {
	echo "<strong>".$dbDriver."</strong> database drivers file could not be found.";
	exit();
}

$driver = 'DATABASE_'.$dbDriver.'_driver';

$params = array
        (
          'hostname' => $dbHost,
          'username' => $dbUser,
          'password' => $dbPass,
          'database' => $dbName,
          'dbdriver' => $dbDriver,
          'char_set' => 'utf8',
          'dbcollat' => 'utf8_general_ci',
        );
		
if (class_exists($driver)) {
	$db = new $driver($params);
	$db->initialize();
} else {
	DIE("ERROR 001: <strong>" . $dbDriver . "</strong> database drivers file could not be found");
}


$result = $db->query('SELECT * FROM ' . $dbPrefix . 'config', array());

foreach ($result->result_array() as $row) {
	if(!defined(strtoupper($row['config_key']))) {
		define(strtoupper($row['config_key']), $row['config_value']);
	}
}

require_once(ADMIN_PATH . "inc/functions.php");
require_once(ADMIN_PATH . "inc/Bcrypt.php");
require_once(ADMIN_PATH . "inc/PHPMailerAutoload.php");

$bcrypt = new Bcrypt();

$clubList = array();
$countryList = array();
$countyList = array();
$positionList = array();

?>