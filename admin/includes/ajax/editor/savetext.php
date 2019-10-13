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

$db->quiet_mode = true;

checksession();


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

	if($elementtype=="pageintro")
	{
		$success=updatepageintro($page, $text);
		if($success) $message= "Saved synopsis";
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
		$sql = new SQLUpdateStatement(SPECIALTEXTS_TABLE,
			array('text'), array('id'),
			array(addslashes(utf8_decode($text)), 'sitepolicy'), 'ss');
		$success = $sql->run();
		if($success) $message= "Saved sitepolicy text";
	}
	elseif($elementtype=="guestbook")
	{
		// TODO editing guestbook intro is broken
		$sql = new SQLUpdateStatement(SPECIALTEXTS_TABLE,
			array('text'), array('id'),
			array(addslashes(utf8_decode($text)), 'guestbook'), 'ss');
		$success = $sql->run();
		if($success) $message= "Saved guestbook text";
	}
	elseif($elementtype=="contact")
	{
		$sql = new SQLUpdateStatement(SPECIALTEXTS_TABLE,
			array('text'), array('id'),
			array(addslashes(utf8_decode($text)), 'contact'), 'ss');
		$success = $sql->run();
		if($success) $message= "Saved contact form intro text";
	}


	if($success && empty($db->error_report))
	{
		print('<message error="0">');
		updateeditdata($page);
		print($message);
	}
	else
	{
		print('<message error="1">');
		print("Error saving ".$elementtype." text: ".$message
			. "<br />\n" . $db->error_report);
	}
}
print("</message>");


?>
