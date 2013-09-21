<?php
$projectroot=dirname(__FILE__)."/";

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."functions/images.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/showimage.php");

// print_r($_POST);
 //print_r($_GET);


$sid=$_GET['sid'];

$nextitem=0;
$previousitem=0;
$page=0;
$image="";
$item=0;

if(isset($_GET['page']))
{
  $page=$_GET['page'];
}

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


$showimage = new Showimage($page,$image,$item,false);

print($showimage->toHTML());
?>
