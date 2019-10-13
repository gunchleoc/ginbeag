<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"menus"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/menupagesmod.php");
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
	$success=false;
	$message ="";

	//$sisters=$_POST['sisters'];
	if(isset($_POST['sisters'])) $sistersinnavigator=1;
	else $sistersinnavigator=0;

	$success = updatemenunavigation($_POST['page'],$_POST['navlevels'],$_POST['pagelevels'],$sistersinnavigator);

	if($success >=0 && empty($db->error_report))
	{
		print('<message error="0">');
		updateeditdata($_POST['page']);
		print("Saved Menu Options for Page: ".$_POST['page']);
	}
	else
	{
		print('<message error="1">');
		print("Error Saving Menu Options for Page: ".$_POST['page']
			. "<br />\n" . $db->error_report);
	}
}
print("</message>");
?>
