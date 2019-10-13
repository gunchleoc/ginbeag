<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "news"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagecontent/newspagesmod.php";
require_once $projectroot."functions/db.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
$message = getpagelock($_POST['page']);
if($message) {
    print('<message error="1">');
    print($message . $db->error_report);
}
else {
    $success = updatenewsitemtitle($_POST['newsitem'], fixquotes($_POST['title']));

    if($success >=0 && empty($db->error_report)) {
        print('<message error="0">');
        updateeditdata($_POST['page']);
        print("Saved  Title for Newsitem ID: ".$_POST['newsitem']);
    }
    else
    {
        print('<message error="1">');
        print("Error Saving Title for Newsitem ID: " . $_POST['newsitem']
        . "<br />\n" . $db->error_report);
    }
}
print("</message>");
?>
