<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"linklists"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

$sid=$_POST['sid'];
checksession($sid);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if($message)
{
	print('<message error="1">');
	print($message);
}
else {
	$success=false;
	$message ="";
	
	
	if(isset($_POST['removeconfirm']) && $_POST['removeconfirm'] != "undefined")
	{
		$success=updatelinkimage($_POST['linkid'],"");
	}
	else
	{
		$message='<br>In order to remove an image, you have to check "Confirm remove".';
	}
	
	if($success>=0)
	{
		print('<message error="0">');
		updateeditdata($_POST['page'], $sid);
		print("Removed Image from Link ID : ".$_POST['linkid']);
	}
	else
	{
		print('<message error="1">');
		print("Error Removing Image from Link ID : ".$_POST['linkid'].$message);
	}
}
print("</message>");
?>