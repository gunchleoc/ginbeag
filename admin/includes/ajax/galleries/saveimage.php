<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "galleries"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagecontent/gallerypagesmod.php";
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
    $page=$_POST['page'];
    $filename=trim($_POST['imagefilename']);

    $success=false;
    $message ="";

    if(imageexists($filename)) {
        $success= changegalleryimage($_POST['galleryitemid'], $filename);
        if(!getthumbnail($filename)) {
            $message = '<br>Please create a thumbnail for this image!';
        }
    }
    else
    {
        $message = '<br>Image <i>'.$filename.'</i> does not exist.';
    }

    if($success >=0 && empty($db->error_report)) {
        print('<message error="0">');
        updateeditdata($page);
        print("Changed Image : ".$filename);
    }
    else
    {
        print('<message error="1">');
        print("Error Changing Image : ".$filename.$message
        . "<br />\n" . $db->error_report);
    }
}
print("</message>");
?>
