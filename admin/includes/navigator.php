<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/templates/admintopframe.php");

$page=$_GET['page'];
$sid=$_GET['sid'];
//print_r($_GET);
$content = new AdminTopFrame($page);
print($content->toHTML());
?>
