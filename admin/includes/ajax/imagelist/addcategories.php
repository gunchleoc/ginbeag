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

$db->quiet_mode = true;

checksession();

$filename="";
if(isset($_POST['filename'])) $filename=$_POST['filename'];

$selectedcats=array();
if(isset($_POST['selectedcat'])) $selectedcats=$_POST['selectedcat'];

$success = addimagecategories($filename,$selectedcats);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($success !== false && empty($db->error_report))
{
	print('<message error="0">');
	print("Added new Categories to ".$filename.".");
}
else
{
	print('<message error="1">');
	print("Error Adding new Categories to ".$filename."!"
			. "<br />\n" . $db->error_report);
}
print("</message>");
?>
