<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imagelist"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/categories.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."functions/categories.php");

//print_r($_POST);
//print_r($_GET);

$sid=$_POST['sid'];
checksession($sid);

$filename=$_POST['filename'];

$printme= new Categorylist(getcategoriesforimage($filename));

print($printme->toHTML());
?>