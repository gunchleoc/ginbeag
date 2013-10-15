<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/navigator.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$pagelist = new PageList();
$content = new AdminMain($page,"show","Please choose a page to return to the admin panel",$pagelist);
print($content->toHTML());
//print($pagelist->toHTML());
$db->closedb();
?>
