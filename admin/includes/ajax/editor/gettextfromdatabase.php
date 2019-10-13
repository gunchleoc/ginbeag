<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"editor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/editor.php");

$db->quiet_mode = true;

$text = text2html(geteditortext($_POST['page'],$_POST['item'], $_POST['elementtype']));

if (empty($db->error_report)) {
	print($text);
} else {
	print($db->error_report);
}
?>
