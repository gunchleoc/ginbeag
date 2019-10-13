<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"news"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/newspages.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

$db->quiet_mode = true;

checksession();

$contents=getnewsitemcontents($_POST['newsitem']);

if (empty($db->error_report)) {
	print(formatdatetime($contents['date']));
} else {
	print($db->error_report);
}
?>
