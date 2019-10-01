<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/guestbookmod.php");
include_once($projectroot."admin/includes/objects/site/guestbook.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();
checkadmin();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$postaction="";
if(isset($_GET['postaction'])) $postaction=$_GET['postaction'];
unset($_GET['postaction']);


$message = "";
$error = false;

if($postaction ==='saveproperties')
{
	$guestbookproperties = array();

	$guestbookproperties['Enable Guestbook']=$db->setinteger($_POST['enableguestbook']);
	$guestbookproperties['Guestbook Entries Per Page']=$db->setinteger($_POST['guestbookperpage']);

	$success=updateentries(SITEPROPERTIES_TABLE,$guestbookproperties,"property_name","property_value");

	if ($success) {
		$message="Guestbook properties saved.";
		// sync global properties array with database changes
		$properties['Enable Guestbook']=$db->setinteger($_POST['enableguestbook']);
		$properties['Guestbook Entries Per Page']=$db->setinteger($_POST['guestbookperpage']);
	} else {
		$message = "Failed to save Guestbook properties";
		$error = true;
	}
}


$itemsperpage=getproperty('Guestbook Entries Per Page');

if(isset($_POST["deleteentry"]))
{
	$contents = new AdminGuestbookDeleteConfirmForm($_POST['messageid']);
}
elseif(isset($_POST["deleteconfirm"]))
{
	$message='Entry #'.$_POST['messageid'].' deleted.';
	deleteguestbookentry($_POST['messageid']);
}
if(!isset($_POST["deleteentry"]))
{
	if(isset($_GET['offset'])) $offset=$_GET['offset'];
	else $offset=0;

	$contents = new AdminGuestbookEntryList($itemsperpage, $offset);
}

$content = new AdminMain($page, "siteguest", new AdminMessage($message, $error), $contents);
print($content->toHTML());
$db->closedb();
?>
