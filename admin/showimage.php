<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."includes/objects/images.php");

// check legal vars
include_once($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."functions/images.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/showimage.php");
include_once($projectroot."admin/functions/sessions.php");

// print_r($_POST);
 //print_r($_GET);

checksession();

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

$showimage = new Showimage($page,$image,$item,true);
print($showimage->toHTML());
$db->closedb();
?>
