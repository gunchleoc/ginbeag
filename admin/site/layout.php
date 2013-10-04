<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/layout.php");
include_once($projectroot."includes/functions.php");
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

$postaction="";
if(isset($_GET['postaction'])) $postaction=$_GET['postaction'];
unset($_GET['postaction']);

$message="";


if($postaction=='savesite' && isset($_POST['submit']))
{
  	$message=savesitelayout();
}


$content = new AdminMain($page,"sitelayout",$message,new SiteLayout());
print($content->toHTML());
$db->closedb();


function savesitelayout()
{
	global $sid, $_POST, $db;
	
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

	$sptext= $db->setstring(fixquotes(trim($_POST['sptext1'])));
	if(strlen($sptext)>255)
	{
		$cutindex=255;
		$properties['Splash Page Text 1 - 1']= substr($sptext,0,$cutindex);
		while(str_endswith($properties['Splash Page Text 1 - 1'],chr(92)) && $cutindex>0)
		{
			$cutindex--;
			$properties['Splash Page Text 1 - 1']= substr($sptext,0,$cutindex);
		}
		$properties['Splash Page Text 1 - 2']= substr($sptext,$cutindex);
	}
	else
	{
		$properties['Splash Page Text 1 - 1']= $sptext;
		$properties['Splash Page Text 1 - 2']= "";
	}
	
	$sptext= $db->setstring(fixquotes(trim($_POST['sptext2'])));
	if(strlen($sptext)>255)
	{
		$cutindex=255;
		$properties['Splash Page Text 2 - 1']= substr($sptext,0,$cutindex);
		while(str_endswith($properties['Splash Page Text 2 - 1'],chr(92)) && $cutindex>0)
		{
			$cutindex--;
			$properties['Splash Page Text 2 - 1']= substr($sptext,0,$cutindex);
		}
		$properties['Splash Page Text 2 - 2']= substr($sptext,$cutindex);
	}
	else
	{
		$properties['Splash Page Text 2 - 1']= $sptext;
		$properties['Splash Page Text 2 - 2']= "";
	}
	
	$success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");
	
	$result = "Layout properties saved";
	
	if(!$success)
	{
		$result = "Failed to save layout properties: ".$sql;
	}

	return $result;
}

?>
