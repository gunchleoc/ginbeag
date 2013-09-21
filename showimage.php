<?php
$projectroot=dirname(__FILE__)."/";
include_once($projectroot."functions/db.php");


// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") $testpath = "";

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."showimage.php") ||
	$_SERVER["PHP_SELF"] == $testpath."/showimage.php"))
{
//	print("test: ".$_SERVER["PHP_SELF"]);
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."functions/images.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/showimage.php");

// print_r($_POST);
 //print_r($_GET);


$sid="";
if (isset($_GET['sid'])) $sid = $_GET['sid'];

$nextitem=0;
$previousitem=0;
$image="";
$item=0;

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['image']))
{
  $image=$_GET['image'];
}
if(isset($_GET['item']))
{
	$item=$_GET['item'];
  // get image from item array
  $image=$_POST[$_GET['item']];
}


$showimage = new Showimage($image,$item,false);

print($showimage->toHTML());

$db->closedb();

?>
