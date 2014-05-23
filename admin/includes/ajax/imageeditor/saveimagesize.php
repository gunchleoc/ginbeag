<?php

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imageeditor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/sessions.php");

checksession();

//print_r($_POST);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$errormessage = getpagelock($_POST['page']);
$message="";

if(!$errormessage)
{

	if(!isset($_POST['page'])) $errormessage .= " :page not defined";
	if(!isset($_POST['item'])) $errormessage .= " :item not defined";
	if(!isset($_POST['elementtype'])) $errormessage .= " :elementtype not defined";
	if(!isset($_POST['autoshrink'])) $errormessage .= " :autoshrink not defined";
	if(!isset($_POST['usethumbnail'])) $errormessage .= " :usethumbnail not defined";
	
	if(!$errormessage)
	{
	
		$page=$_POST['page'];
		$item=$_POST['item'];
		if($_POST['autoshrink'] === "on")
			$autoshrink=1;
		else $autoshrink= 0;
		if($_POST['usethumbnail'] === "on")
			$usethumbnail=1;
		else $usethumbnail= 0;
		$elementtype=$_POST['elementtype'];
	
		$success=false;
	
		if($elementtype=="pageintro")
		{

			$success=updatepageintroimagesize($page,$autoshrink, $usethumbnail);
			if($success) $message.= "Saved synopsis image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail;
			else $errormessage = "Error saving synopsis image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail." for page ".$page;
		}
	
		elseif($elementtype=="articlesection")
		{
			include_once($projectroot."admin/functions/pagecontent/articlepagesmod.php");
		    $success=updatearticlesectionimagesize($item,$autoshrink, $usethumbnail);
		    if($success) $message.="Saved section image size";
		    else $errormessage = "Error saving section image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail." for page ".$page." and section ".$item;
		}
		elseif($elementtype=="newsitemsection")
		{
			include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");
		    $success=updatenewsitemsectionimagesize($item,$autoshrink, $usethumbnail);
		    if($success) $message.="Saved section image size";
		    else $errormessage = "Error saving section image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail." for page ".$page." and section ".$item;
		}
		elseif($elementtype=="link")
		{
			$errormessage = "You can't change the size of images for links in a linklist";
	 	}
	}
}

if($errormessage)
{
	print('<message error="1">');
	print("error ".$errormessage);
}
else
{
	print('<message error="0">');
	updateeditdata($page);
	print("success ".$message);
}
print("</message>");

?>
