<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "news"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/pagecontent/newspages.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

$contents=getnewsitemcontents($_POST['newsitem']);

if(strlen($contents['title'])>0) {
    $header=title2html($contents['title']);
} else {
    $header="New Newsitem";
}

if (empty($db->error_report)) {
    print($header);
} else {
    print($db->error_report);
}
?>
