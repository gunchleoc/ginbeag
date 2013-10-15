<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"news"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");
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
	$success = fakethedate($_POST['newsitem'],$_POST['day'],$_POST['month'],$_POST['year'],$_POST['hours'],$_POST['minutes'],$_POST['seconds']);
	
	if($success >=0)
	{
		print('<message error="0">');
		updateeditdata($_POST['page']);
		print("Saved Date for Newsitem ID:".$_POST['newsitem']);
	}
	else
	{
		print('<message error="1">');
		print("Error Saving Date for Newsitem ID:".$_POST['newsitem']);
	}
}
print("</message>");
?>