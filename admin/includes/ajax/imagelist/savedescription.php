<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imagelist"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/imagesmod.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/sessions.php");
//include_once($projectroot."admin/functions/categoriesmod.php");
//include_once($projectroot."admin/functions/usersmod.php");

//print_r($_POST);
//print_r($_GET);

$sid=$_POST['sid'];
checksession($sid);


$filename="";
if(isset($_POST['filename'])) $filename=$_POST['filename'];

$caption="";
if(isset($_POST['caption'])) $caption=fixquotes($_POST['caption']);

$source="";
if(isset($_POST['source'])) $source=fixquotes($_POST['source']);

$sourcelink="";
if(isset($_POST['sourcelink'])) $sourcelink=$_POST['sourcelink'];

$copyright="";
if(isset($_POST['copyright'])) $copyright=fixquotes($_POST['copyright']);

$permission=NO_PERMISSION;
if(isset($_POST['permission'])) $permission=$_POST['permission'];

$success = savedescription($filename,$caption,$source,$sourcelink,$copyright,$permission);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($success >=0)
{
	print('<message error="0">');
	print("Saved Description for ".$filename.".");
}
else
{
	print('<message error="1">');
	print("Error Saving Description for ".$filename."!");
}
print("</message>");
?>