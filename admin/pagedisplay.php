<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."includes/templates/page.php");

//print_r($_GET);

$sid=$_GET['sid'];
checksession($sid);

$page = new Page("page",true);
print($page->toHTML());
?>
