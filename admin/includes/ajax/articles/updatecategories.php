<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "articles"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/categories.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/categories.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

$printme= new Categorylist(getcategoriesforpage($_POST['page']), CATEGORY_ARTICLE);

if (empty($db->error_report)) {
    print($printme->toHTML());
} else {
    print($db->error_report);
}
?>
