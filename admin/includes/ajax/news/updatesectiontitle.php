<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"news"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/newspages.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);
//print_r($_GET);

checksession();

$contents=getnewsitemsectioncontents($_POST['newsitemsection']);
if(strlen($contents['sectiontitle'])>0)
	$sectionheader=title2html($contents['sectiontitle']);
else
	$sectionheader="Section ID ".$_POST['newsitemsection'];

print($sectionheader);
?>
