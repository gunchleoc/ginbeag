<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"articles"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/categories.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."functions/categories.php");

//print_r($_POST);

checksession();

$printme= new Categorylist(getcategoriesforpage($_POST['page']));

print($printme->toHTML());
?>