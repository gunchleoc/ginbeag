<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imagelist"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/imagelist.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);
//print_r($_GET);

$db->quiet_mode = true;

checksession();

$filename=$_POST['filename'];
$image=getimage($filename);
$printme= new EditImageFormUsage($filename);

if (empty($db->error_report)) {
	print($printme->toHTML());
} else {
	print($db->error_report);
}
?>
