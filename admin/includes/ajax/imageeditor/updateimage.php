<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "imageeditor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/imageeditor.php";
require_once $projectroot."admin/functions/sessions.php";

$db->quiet_mode = true;

//print_r($_POST);

checksession();

$image="";

$elementtype=$_POST["elementtype"];

if($elementtype=="pageintro") {
    include_once $projectroot."functions/pages.php";
    $image=getpageintroimage($_POST['page']);
}
elseif($elementtype=="articlesection") {
    include_once $projectroot."functions/pagecontent/articlepages.php";
    $contents = getarticlesectioncontents($_POST['item']);
    $image = $contents['sectionimage'];
}
elseif($elementtype=="newsitemsection") {
    include_once $projectroot."functions/pagecontent/newspages.php";
    $image = getnewsitemsectionimage($_POST['item']);
}
elseif($elementtype=="link") {
    include_once $projectroot."functions/pagecontent/linklistpages.php";
    $contents=getlinkcontents($_POST['item']);
    $image=$contents["image"];
}
else { print ("Error: Unknown elementtype: ".$elementtype."</br /> for image on page: ".$_POST['page'].", item: ".$_POST['item']);
}
if (!empty($db->error_report)) {
    print($db->error_report);
} else if($image) {
    $printme = new ImageEditorImagePane($_POST['page'], $image);
    print($printme->toHTML());
}

?>
