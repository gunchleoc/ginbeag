<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars

include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesdelete.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/page.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

// print_r($_POST);
// print_r($_GET);
$message = "";
$error = false;

// *************************** actions ************************************** //

if($page<=0)
{
	$editpage = noPageSelectedNotice();
	$message = "Please select a page first";
	$error = true;
}
elseif($action==="delete")
{
    $editpage = new DeletePageConfirmForm();
}
elseif(isset($_POST["executedelete"]))
{
	$pagename = title2html(getpagetitle($page));
	$parent=getparent($page);
	$deletepage=deletepage($page);
	$deletepage--;
	unlockpage($page);
	$message='Deleted the following page(s): "'.title2html($pagename).'"<br />'.$deletepage.' subpages were included in delete.';
	$editpage = new DoneRedirect($parent, "Page Deleted", array("action" => "show"), "admin.php", "View parent page");
}
elseif(isset($_POST["nodelete"]))
{
	unlockpage($page);
	$message="Deleting aborted: ".title2html(getpagetitle($page));
	$editpage = new DoneRedirect($page, "Delete Page Aborted", array("action" => "show"), "admin.php", "View the page");
}

$content = new AdminMain($page, "pagedelete", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
$db->closedb();
?>
