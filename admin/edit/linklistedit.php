<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");
include_once($projectroot."admin/includes/objects/edit/linklistpage.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message="";
$message = getpagelock($page);
if(!$message)
{
	// update linklist
	if(isset($_POST['addlink']))
	{
		$message='Added new link';
		addlink($page,fixquotes($_POST['title']),$_POST['link'],$_POST['imagefilename'],fixquotes($_POST['description']));
	}
	elseif(isset($_POST['deletelink']))
	{
		$message='Deleted link <i>'.title2html(getlinktitle($_GET['link'])).'</i>';
		if(isset($_POST['deletelinkconfirm']))
		{
			deletelink($_GET['link']);
			updateeditdata($page);
		}
		else
		{
			$message='In order to delete a link, you have to check "Confirm delete".';
		}
	}
	elseif(isset($_POST['movelinkup']))
	{
		$message='Moved link up';
		movelink($_GET['link'], "up", $_POST['positions']);
		updateeditdata($page);
	}
	elseif(isset($_POST['movelinkdown']))
	{
		$message='Moved link down';
		movelink($_GET['link'], "down", $_POST['positions']);
		updateeditdata($page);
	}
	elseif(isset($_POST['sortlinks']))
	{
		$message='Sorted links from A-Z';
		sortlinksbyname($page);
		updateeditdata($page);
	}
	$editpage = new EditLinklist($page);
}
else
{
	$editpage = new DonePage("This page is already being edited","&action=show","admin.php","View this page");
}

$content = new AdminMain($page,"editcontents",$message,$editpage);
print($content->toHTML());
$db->closedb();
?>
