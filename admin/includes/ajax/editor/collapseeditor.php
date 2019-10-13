<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "editor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/editor.php";

$db->quiet_mode = true;

$editor = new EditorContentsCollapsed($_POST['page'], $_POST['item'], $_POST['elementtype'], $_POST['title']);

if (empty($db->error_report)) {
    print($editor->toHTML());
} else {
    print($db->error_report);
}
?>
