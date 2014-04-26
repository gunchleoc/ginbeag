<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imageeditor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/imageeditor.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

checksession();

$elementtype=$_POST["elementtype"];

if($elementtype=="pageintro")
{
	include_once($projectroot."functions/pages.php");
	$contents = getpageintro($_POST['page']);
	$autoshrink=$contents['imageautoshrink'];
	$usethumbnail=$contents['usethumbnail'];
}
elseif($elementtype=="articlesection")
{
	include_once($projectroot."functions/pagecontent/articlepages.php");
	$contents = getarticlesectioncontents($_POST['item']);
	$autoshrink=$contents['imageautoshrink'];
	$usethumbnail=$contents['usethumbnail'];
}
elseif($elementtype=="newsitemsection")
{
	include_once($projectroot."functions/pagecontent/newspages.php");
	$contents = getnewsitemsectioncontents($_POST['item']);
	$autoshrink=$contents['imageautoshrink'];
	$usethumbnail=$contents['usethumbnail'];
}
elseif($elementtype=="link")
{
	include_once($projectroot."functions/pagecontent/linklistpages.php");
	$contents = getlinkcontents($_POST['item']);
	$autoshrink=$contents['imageautoshrink'];
	$usethumbnail=$contents['usethumbnail'];
}
else print ("Error: Unknown elementtype: ".$elementtype."</br /> for image on page: ".$_POST['page'].", item: ".$_POST['item']);
if(isset($autoshrink))
{
	$printme = new ImageEditorSizePane($_POST["page"],$_POST["item"], $autoshrink, $usethumbnail);
	print($printme->toHTML());
}

?>