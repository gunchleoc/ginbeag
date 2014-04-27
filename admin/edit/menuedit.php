<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/edit/menupage.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
elseif(isset($_POST['page'])) $page=$_POST['page'];
else $page=0;

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message = getpagelock($page);
if(!$message)
{
	if(isset($_POST['sortsubpages']))
	{
		$message='Sorted subpages from A-Z';
		sortsubpagesbyname($page);
		updateeditdata($page);
	}
	$editpage = new EditMenuSubpages($page);
}
else
{
	$editpage = new DonePage("This page is already being edited","&action=show","admin.php","View this page");
}

$content = new AdminMain($page,"editcontents",$message,$editpage);
print($content->toHTML());
$db->closedb();
?>
