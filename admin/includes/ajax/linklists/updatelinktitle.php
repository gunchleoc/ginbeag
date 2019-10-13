<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"linklists"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

$db->quiet_mode = true;

checksession();

$contents=getlinkcontents($_POST['linkid']);

if(strlen($contents['title'])>0)
	$sectionheader=title2html($contents['title']);
else
	$sectionheader="Link ID ".$_POST['linkid'];

if (empty($db->error_report)) {
	print($sectionheader);
} else {
	print($db->error_report);
}
?>
