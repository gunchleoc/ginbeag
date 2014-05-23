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
	$properties['Google Keywords']=$db->setstring(trim($_POST['keywords']));
	$properties['Domain Name']=$db->setstring(trim($_POST['domainname']));
	$properties['Local Path']=$db->setstring(trim($_POST['localpath']));
	$properties['Cookie Prefix']=$db->setstring(trim($_POST['cookieprefix']));
	$properties['Image Upload Path']=$db->setstring(trim($_POST['imagepath']));
	$properties['Admin Email Address']=$db->setstring(trim($_POST['email']));
	$properties['Email Signature']= $db->setstring(fixquotes(trim($_POST['signature'])));
	$properties['Date Time Format']=$db->setstring(trim($_POST['datetime']));
	$properties['Date Format']=$db->setstring(trim($_POST['date']));
	$properties['Thumbnail Size']=$db->setinteger(trim($_POST['thumbnailsize']));
	$properties['Mobile Thumbnail Size']=$db->setinteger(trim($_POST['mobilethumbnailsize']));
	$properties['Imagelist Images Per Page']=$db->setinteger(trim($_POST['imagesperpage']));
	
	$success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");
	
	if($success="1")
	{
		$message="Technical setup saved";
	}
	else
	{
		$message = "Failed to save technical setup".$sql;
		$error = true;
	}
}

$content = new AdminMain($page, "sitetech", new AdminMessage($message, $error), new SiteTechnical());
print($content->toHTML());
$db->closedb();
?>
