<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imagelist"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/categoriesmod.php");

//print_r($_POST);
//print_r($_GET);

checksession();

$filename="";
if(isset($_POST['filename'])) $filename=$_POST['filename'];

$selectedcats=array();
if(isset($_POST['selectedcat'])) $selectedcats=$_POST['selectedcat'];

$success = removeimagecategories($filename,$selectedcats);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($success)
{
	print('<message error="0">');
	print("Removed Categories from ".$filename.".");
}
else
{
	print('<message error="1">');
	print("Error Removing Categories from ".$filename."!");
}
print("</message>");
?>
