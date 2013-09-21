<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/preview.php");


$sid=$_GET['sid'];
checksession($sid);


$type="";
$newsitem=0;
$text="";
$linkparams="";

$page=$_GET['page'];
$newsitem=$_GET['newsitem'];


$contents= new Preview($newsitem, $linkparams);
print($contents->toHTML());
$db->closedb();
?>