<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/navigator.php");

$sid=$_GET['sid'];

checksession($sid);

$pagelist = new PageList();
print($pagelist->toHTML());
$db->closedb();
?>
