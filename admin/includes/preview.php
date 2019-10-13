<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/preview.php";

checksession();
$page = $_GET['page'];

$contents= new Preview($_GET['newsitem']);
print($contents->toHTML());
?>
