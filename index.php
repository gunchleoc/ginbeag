<?php
$projectroot=dirname(__FILE__)."/";

include_once($projectroot."functions/db.php");


// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") $testpath = "";

if(!($_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."index.php" ||
	$_SERVER["PHP_SELF"] == $testpath."/index.php"))
{
//	print("test: ".$_SERVER["PHP_SELF"]);
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

// check legal vars
include_once($projectroot."includes/legalvars.php");

// get includes
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."language/languages.php");


// logout public user
if(isset($_GET['logout']))
{
	publiclogout($_GET['sid']);
	unset($_GET['sid']);
}
// show splash page
if(!isset($_GET['page']))
{
   	$page = new Page("splashpage",false);
}
// show normal page
else
{
	if(isset($_GET['printview']))
	{
   		$page = new Printview();
	}
	else
	{
		$page = new Page("page");
	}
}
print($page->toHTML());

?>

