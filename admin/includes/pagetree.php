<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));
include_once($projectroot."functions/pages.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/templates/adminnavigator.php");
include_once($projectroot."includes/includes.php");

$sid=$_GET['sid'];
$page=$_GET['page'];

checksession($sid);

$pagetree= new PageTree($page);
print($pagetree->toHTML());

?>
