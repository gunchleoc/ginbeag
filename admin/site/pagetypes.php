<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/pagetypes.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(!isadmin($sid))
{
	die('<p class="highlight">You have no permission for this area</p>');
}

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['pagetypesettings']))
{
	$allowroot=0;
	if(isset($_POST['allowroot']))
	{
		$allowroot=1;
	}
	$allowsimplemenu=0;
	if(isset($_POST['allowsimplemenu']))
	{
		$allowsimplemenu=1;
	}
	updaterestrictions($_POST['pagetype'],$allowroot,$allowsimplemenu);
	$message=('Changed settings for <i>'.$_POST['pagetype'].'</i>');
}

$content = new AdminMain($page,"sitepagetype",$message,new SitePageTypes());
print($content->toHTML());
$db->closedb();
?>