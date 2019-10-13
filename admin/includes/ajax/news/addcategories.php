<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"news"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/categoriesmod.php");

//print_r($_POST);

$db->quiet_mode = true;

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
	$success = addnewsitemcategories($_POST['newsitem'],$selectedcats);

	if($success >=0 && empty($db->error_report))
	{
		print('<message error="0">');
		updateeditdata($_POST['page']);
		print("Added new Categories to Newsitem ID: ".$_POST['newsitem'].".");
	}
	else
	{
		print('<message error="1">');
		print("Error Adding new Categories to Newsitem ID: ".$_POST['newsitem']."!"
			. "<br />\n" . $db->error_report);
	}
}
print("</message>");
?>
