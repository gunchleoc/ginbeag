<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"articles"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/articlepages.php");
//include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);
//print_r($_GET);

checksession();

$contents=getarticlesectioncontents($_POST['articlesection']);
if(strlen($contents['sectiontitle'])>0)
	$sectionheader=title2html($contents['sectiontitle']);
else
	$sectionheader="Section ID ".$_POST['articlesection'];

print($sectionheader);
?>