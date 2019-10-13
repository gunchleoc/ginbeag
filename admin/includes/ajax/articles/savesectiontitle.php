<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "articles"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagecontent/articlepagesmod.php";
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
    print($message);
}
else {

    $articlesection=$_POST['articlesection'];
    $page=$_POST['page'];
    $sectiontitle=fixquotes($_POST['sectiontitle']);

    $success = updatearticlesectiontitle($articlesection, $sectiontitle);


    if($success >=0 && empty($db->error_report)) {
        print('<message error="0">');
        updateeditdata($page);
        print("Updated Section Title for Section ID: ".$articlesection);
    }
    else
    {
        print('<message error="1">');
        print("Error Updating Section Title for Section ID: ".$articlesection
        . "<br />\n" . $db->error_report);
    }
}
print("</message>");
?>
