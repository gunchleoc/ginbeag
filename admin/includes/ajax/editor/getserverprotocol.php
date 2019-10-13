<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "editor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";

$db->quiet_mode = true;

$protocol = getproperties()["Server Protocol"];

if (empty($db->error_report)) {
    print($protocol);
} else {
    print($db->error_report);
}
?>
