<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"articles"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/functions/pagesmod.php");

//print_r($_POST);

checksession();

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if($message)
{
	print('<message error="1">');
	print($message);
}
else {
	$selectedcats=$_POST['selectedcat'];
	$success = addpagecategories($_POST['page'],$selectedcats);
	if($success >=0)
	{
		print('<message error="0">');
		updateeditdata($_POST['page']);
		print("Added new Categories to Page ID: ".$_POST['page'].".");
	}
	else
	{
		print('<message error="1">');
		print("Error Adding new Categories to Page ID: ".$_POST['page']."!");
	}
}
print("</message>");
?>