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

$align="";

$elementtype=$_POST["elementtype"];

if($elementtype=="pageintro")
{
	include_once($projectroot."functions/pages.php");
	$align=getpageintrohalign($_POST['page']);
}
elseif($elementtype=="articlesection")
{
	include_once($projectroot."functions/pagecontent/articlepages.php");
	$contents = getarticlesectioncontents($_POST['item']);
	$align = $contents['imagealign'];
}
elseif($elementtype=="newsitemsection")
{
	include_once($projectroot."functions/pagecontent/newspages.php");
	$align = getnewsitemsectionimagealign($_POST['item']);
}
elseif($elementtype=="link")
{
	$printme="";
}
else print ("Error: Unknown elementtype: ".$elementtype."</br /> for image on page: ".$_POST['page'].", item: ".$_POST['item']);
if($align)
{
	$printme = new ImageEditorPropertiesPane($_POST["page"],$_POST["item"], $align);
	print($printme->toHTML());
}

?>