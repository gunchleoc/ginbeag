<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imagelist"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/imagesmod.php");
include_once($projectroot."admin/includes/objects/imagelist.php");
//include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/sessions.php");
//include_once($projectroot."admin/functions/categoriesmod.php");
//include_once($projectroot."admin/functions/usersmod.php");

//print_r($_POST);
//print_r($_GET);

checksession();

$filename=$_POST['filename'];
$image=getimage($filename);
$thumbnail = getthumbnail($filename);
$printme= new AdminImage($filename, $image['uploaddate'], $image['editor_id'], $thumbnail,true);

print($printme->toHTML());
?>