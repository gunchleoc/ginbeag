<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"menus"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/menupagesmod.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

checksession();


$success=false;
$message ="";

if(isset($_POST['moveup']))
{
	$message = " Up";
  	$success = movepage($_POST['moveid'], "up", $_POST['positions']);
  	updateeditdata($_POST['page']);
}
elseif(isset($_POST['movedown']))
{
  	$message = " Down";
  	$success = movepage($_POST['moveid'], "down", $_POST['positions']);
  	updateeditdata($_POST['page']);
}
elseif(isset($_POST['movetop']))
{
  	$message = " to the Top";
  	$success = movepage($_POST['moveid'], "top");
  	updateeditdata($_POST['page']);
}
else
{
  	$message = " to the Bottom";
  	$success = movepage($_POST['moveid'], "bottom");
  	updateeditdata($_POST['page']);
}


header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($success >=0)
{
	print('<message error="0">');
	updateeditdata($_POST['page']);
	print("Moved Subpage".$message);
}
else
{
	print('<message error="1">');
	print("Error Moving Subpage".$message);
}
print("</message>");

?>
