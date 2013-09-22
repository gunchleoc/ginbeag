<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"editor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/pagecontent/articlepagesmod.php");
include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");
include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");

//print_r($_POST);


$sid=$_POST['sid'];
checksession($sid);


header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if($message)
{
	print('<message error="1">');
	print($message);
}
else {

	$page=$_POST['page'];
	$item=$_POST['item'];
	$elementtype=$_POST['elementtype'];
	$text=$_POST['savetext'];

	$success=false;

	if($elementtype=="articlesynopsis" || $elementtype=="gallery" || $elementtype=="linklist" || $elementtype=="menu")
	{
		$success=updatepageintro($page, $text);
		if($success) $message= "Saved page intro / synopsis";
	}
	elseif($elementtype=="articlesection")
	{
		$success=updatearticlesectiontext($item, $text);
		if($success) $message= "Saved article section";
	}
	elseif($elementtype=="link")
	{
		$success=updatelinkdescription($item, $text);
		if($success) $message= "Saved link description";
	}
	elseif($elementtype=="newsitemsynopsis")
	{
		$success=updatenewsitemsynopsistext($item, $text);
		if($success) $message= "Saved newsitem synopsis";
	}
	elseif($elementtype=="newsitemsection")
	{
		$success=updatenewsitemsectiontext($item, $text);
		if($success) $message= "Saved newsitem section text";
	}
	elseif($elementtype=="sitepolicy")
	{
		$success=updatefield(SITEPOLICY_TABLE,"sitepolicytext",addslashes(utf8_decode($text)),"policy_id = '0'");
		if($success) $message= "Saved sitepolicy text";
	}

	if($success >=0)
	{
		print('<message error="0">');
		updateeditdata($page, $sid);
		print($message);
	}
	else
	{
		print('<message error="1">');
		print("Error saving ".$elementtype." text: ".$message);
	}
}
print("</message>");


?>