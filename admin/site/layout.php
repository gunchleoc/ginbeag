<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/layout.php");
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
	$properties['Default Template']=$db->setstring(trim($_POST['defaulttemplate']));

	$properties['Site Name']=$db->setstring(fixquotes(trim($_POST['sitename'])));
	$properties['Site Description']=$db->setstring(fixquotes(trim($_POST['sitedescription'])));
	$properties['Left Header Image']=$db->setstring(trim($_POST['leftimage']));
	$properties['Left Header Link']=$db->setstring(trim($_POST['leftlink']));
	$properties['Right Header Image']=$db->setstring(trim($_POST['rightimage']));
	$properties['Right Header Link']=$db->setstring(trim($_POST['rightlink']));

	$properties['Footer Message']=$db->setstring(fixquotes(trim($_POST['footermessage'])));

	$properties['News Items Per Page']=$db->setinteger(trim($_POST['newsperpage']));
	$properties['Gallery Images Per Page']=$db->setinteger(trim($_POST['galleryimagesperpage']));

	if(isset($_POST['linksonsplashpage']))
		$properties['Links on Splash Page']= implode(",",$_POST['linksonsplashpage']);

	$properties['Show All Links on Splash Page']= $db->setinteger(trim($_POST['alllinksonsplashpage']));
	$properties['Display Site Description on Splash Page']= $db->setinteger(trim($_POST['showsd']));
	$properties['Splash Page Font']= $db->setstring(trim($_POST['spfont']));
	$properties['Splash Page Image']= $db->setstring(trim($_POST['spimage']));

	$success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");

	// Splash page texts go into he specialtexts table
	$success=updatefield(SPECIALTEXTS_TABLE,"text",$db->setstring(fixquotes(trim($_POST['sptext1']))),"id='splashpage1'") && $success;
	$success=updatefield(SPECIALTEXTS_TABLE,"text",$db->setstring(fixquotes(trim($_POST['sptext2']))),"id='splashpage2'") && $success;

	$message = "Layout properties saved";

	if(!$success)
	{
		$message = "Failed to save layout properties: ".$sql;
		$error = true;
	}
}

$content = new AdminMain($page, "sitelayout", new AdminMessage($message, $error), new SiteLayout());
print($content->toHTML());
$db->closedb();

?>
