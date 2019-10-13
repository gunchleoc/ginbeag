<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/technical.php");
include_once($projectroot."includes/functions.php");
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

if($postaction=='savesite' && isset($_POST['submit']))
{
	$newproperties = array();
	$newproperties['Google Keywords'] = fixquotes(trim($_POST['keywords']));
	$newproperties['Server Protocol'] = fixquotes(trim($_POST['serverprotocol']));
	$newproperties['Domain Name'] = fixquotes(trim($_POST['domainname']));
	$newproperties['Local Path'] = fixquotes(trim($_POST['localpath']));
	$newproperties['Cookie Prefix'] = fixquotes(trim($_POST['cookieprefix']));
	$newproperties['Image Upload Path'] = fixquotes(trim($_POST['imagepath']));
	$newproperties['Admin Email Address'] = fixquotes(trim($_POST['email']));
	$newproperties['Email Signature'] = fixquotes(fixquotes(trim($_POST['signature'])));
	$newproperties['Date Time Format'] = trim($_POST['datetime']);
	$newproperties['Date Format'] = trim($_POST['date']);
	$newproperties['Image Width'] = SQLStatement::setinteger(trim($_POST['imagewidth']));
	$newproperties['Thumbnail Size'] = SQLStatement::setinteger(trim($_POST['thumbnailsize']));
	$newproperties['Mobile Thumbnail Size'] = SQLStatement::setinteger(trim($_POST['mobilethumbnailsize']));
	$newproperties['Imagelist Images Per Page'] = SQLStatement::setinteger(trim($_POST['imagesperpage']));

	$message .= updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);

	if (empty($message)) {
		$message = "Technical setup saved";
	} else {
		$message = "Failed to save technical setup:" . $message;
		$error = true;
	}
}

$content = new AdminMain($page, "sitetech", new AdminMessage($message, $error), new SiteTechnical());
print($content->toHTML());
?>
