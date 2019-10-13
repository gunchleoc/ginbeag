<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "galleries"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/images.php";
require_once $projectroot."functions/pagecontent/gallerypages.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

$filename=getgalleryimage($_POST['galleryitemid']);
$printme = new CaptionedImageAdmin($filename, $_POST['page']);

if (empty($db->error_report)) {
    print($printme->toHTML());
} else {
    print($db->error_report);
}
?>
