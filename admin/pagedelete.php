<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars

include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesdelete.php");
include_once($projectroot."admin/includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/page.php");
include_once($projectroot."admin/includes/objects/adminmain.php");


$sid=$_GET['sid'];
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

// print_r($_POST);
// print_r($_GET);
$message="";
 
// *************************** actions ************************************** //

if($page<=0)
{
  $editpage = new DonePage("No Page Selected","&action=show","admin.php","Admin home");
  $message="Please select a page first";
}
elseif($action==="delete")
{
    $editpage = new DeletePageConfirmForm();
}
elseif(isset($_POST["executedelete"]))
{
	$pagename = title2html(getpagetitle($page));
	$parent=getparent($page);
	$deletepage=deletepage($page,$sid);
	$deletepage--;
	unlockpage($page);
	$message='Deleted the following page(s): "'.title2html($pagename).'"<br />'.$deletepage.' subpages were included in delete.';
	$editpage = new DoneRedirect($parent,"Page Deleted","&action=show","admin.php","View parent page");
}
elseif(isset($_POST["nodelete"]))
{
  unlockpage($page);
  $message="Deleting aborted: ".title2html(getpagetitle($page));
  $editpage = new DoneRedirect($page,"Delete Page Aborted","&action=show","admin.php","View the page");
}


$content = new AdminMain($page,"pagedelete",$message,$editpage);
	print($content->toHTML());  

  //print($editpage->toHTML());


$db->closedb();
?>
