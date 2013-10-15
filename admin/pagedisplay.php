<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/legalvars.php");
include_once($projectroot."admin/functions/sessions.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

include_once($projectroot."includes/objects/page.php");

//print_r($_GET);

$page = new Page("page",true);
print($page->toHTML());
$db->closedb();
?>
