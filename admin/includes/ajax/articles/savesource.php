<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"articles"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/articlepagesmod.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/sessions.php");

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

	$success = updatearticlesource($_POST['page'],fixquotes($_POST['author']),fixquotes($_POST['location']),$_POST['day'],$_POST['month'],$_POST['year'],fixquotes($_POST['source']),$_POST['sourcelink'],$_POST['toc']);

	if($success >=0)
	{
		print('<message error="0">');
		updateeditdata($_POST['page']);
		print("Saved Source Info for Article ID:".$_POST['page']);
	}
	else
	{
		print('<message error="1">');
		print("Error Saving Source Info for Article ID:".$_POST['page']);
	}
}
print("</message>");
?>
