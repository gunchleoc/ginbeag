<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/preview.php");

checksession();

$contents= new Preview($_GET['newsitem']);
print($contents->toHTML());
$db->closedb();
?>
