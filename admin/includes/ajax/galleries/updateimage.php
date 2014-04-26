<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"galleries"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/images.php");
include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

checksession();

$filename=getgalleryimage($_POST['galleryitemid']);
$printme = new CaptionedImageAdmin($filename, $_POST['page']);

print($printme->toHTML());
?>