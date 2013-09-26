<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"linklists"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

$sid=$_POST['sid'];
checksession($sid);

$contents=getlinkcontents($_POST['linkid']);

$printme = new CaptionedImageAdmin($contents['image'],$_POST['page'],2);

print($printme->toHTML());
?>