<?php
$projectroot=dirname(__FILE__)."/";

include_once($projectroot."functions/db.php");

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") $testpath = "";

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."index.php") ||
	$_SERVER["PHP_SELF"] == $testpath."/index.php"))
{
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

// check legal vars
include_once($projectroot."includes/legalvars.php");

// get includes
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."language/languages.php");

if(isset($_GET['sid'])) $sid = $_GET['sid'];
elseif(isset($_POST['sid'])) $sid = $_POST['sid'];
else $sid="";

if(isset($_GET['page'])) $page = $_GET['page'];
elseif(isset($_POST['page'])) $page = $_POST['page'];
elseif(isset($_GET['newsitem']))
{
	include_once($projectroot."functions/pagecontent/newspages.php");
	$page = getnewsitempage($_GET['newsitem']);
	$_GET['page']=$page;
}
else $page=0;

$wasloggedout = false;

// logout public user
if(isset($_GET['logout']))
{
	publiclogout($_GET['sid']);
	unset($_GET['sid']);
	unset($_GET['logout']);
	$wasloggedout = true;
}
// mobile style
if(isset($_GET['m']))
{
	if(!isset($_GET['page']) || ($page == 0 && $wasloggedout))
	{
		$page = new Page("splashpagemobile",false);
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
			$page = new Page("mobile");
		}
	}
}
else
{
	// show splash page
	if(!isset($_GET['page']) || ($page == 0 && $wasloggedout))
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
}
print($page->toHTML());

$db->closedb();

?>