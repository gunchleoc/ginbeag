<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"linklists"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");
include_once($projectroot."admin/functions/sessions.php");

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
	$success=updatelinkproperties($_POST['linkid'],fixquotes($_POST['title']),$_POST['link']);

	if($success >=0 && empty($db->error_report))
	{
		print('<message error="0">');
		updateeditdata($_POST['page']);
		print("Saved Properties for Link ID : ".$_POST['linkid']);
	}
	else
	{
		print('<message error="1">');
		print("Error Saving Properties for Link ID : ".$_POST['linkid']
			. "<br />\n" . $db->error_report);
	}
}
print("</message>");
?>
