<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "articles"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/categoriesmod.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if($message) {
    print('<message error="1">');
    print($message);
}
else {

    $selectedcats=$_POST['selectedcat'];
    $success = removearticlecategories($_POST['page'], $selectedcats);

    if($success >=0 && empty($db->error_report)) {
        print('<message error="0">');
        updateeditdata($_POST['page']);
        print("Removed Categories from Page ID: ".$_POST['page'].".");
    }
    else
    {
        print('<message error="1">');
        print("Error Removing Categories from Page ID: ".$_POST['page']."!"
        . "<br />\n" . $db->error_report);
    }
}
print("</message>");
?>
