<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "editor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/editor.php";

$db->quiet_mode = true;

$page=0;
if(isset($_POST['page'])) { $page= $_POST['page'];
}

if(isset($_POST['edittext'])) {
    $editor = new EditorContentsExpanded($page, $_POST['item'], $_POST['elementtype'], $_POST['title'], $_POST['edittext']);
}
else
{
    $editor = new EditorContentsExpanded($page, $_POST['item'], $_POST['elementtype'], $_POST['title']);
}

if (empty($db->error_report)) {
    print($editor->toHTML());
} else {
    print($db->error_report);
}

?>
