<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "menus"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/edit/menupage.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

$subpageids=getallsubpageids($_POST['page']);
$printme= new MenuMovePageFormContainer($_POST['page'], $subpageids);

if (empty($db->error_report)) {
    print($printme->toHTML());
} else {
    print($db->error_report);
}
?>
