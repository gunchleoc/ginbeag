<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"editor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/functions.php");

$db->quiet_mode = true;

$text = utf8_decode(text2html($_POST['previewtext']));

if (empty($db->error_report)) {
	print($text);
} else {
	print($db->error_report);
}
?>
