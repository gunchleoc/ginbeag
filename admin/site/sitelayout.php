<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");


include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."admin/includes/templates/site.php");

$sid=$_GET['sid'];
checksession($sid);

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

//  print_r($_GET);
//  print_r($_POST);


if($action=='site')
{
	$sitelayout = new SiteLayout("");
}

if($action=='savesite')
{
  	savesitelayout();
  	
  	$sitelayout = new SiteLayout("Saving Site Layout");
}

print($sitelayout->toHTML());


function savesitelayout()
{
  global $sid, $_POST;
  
  $properties['Site Name']=setstring(trim($_POST['sitename']));
  $properties['Site Description']=setstring(trim($_POST['sitedescription']));
  $properties['Left Header Image']=setstring(trim($_POST['leftimage']));
  $properties['Left Header Link']=setstring(trim($_POST['leftlink']));
  $properties['Right Header Image']=setstring(trim($_POST['rightimage']));
  $properties['Right Header Link']=setstring(trim($_POST['rightlink']));
  
  $properties['Footer Message']=setstring(trim($_POST['footermessage']));

  $properties['Links Per Page']=setinteger(trim($_POST['linksperpage']));
  $properties['News Items Per Page']=setinteger(trim($_POST['newsperpage']));

	if(isset($_POST['linksonsplashpage']))
  		$properties['Links on Splash Page']= implode(",",$_POST['linksonsplashpage']);
  $properties['Show All Links on Splash Page']= setinteger(trim($_POST['alllinksonsplashpage']));

  $properties['Display Site Description on Splash Page']= setinteger(trim($_POST['showsd']));
  $properties['Splash Page Font']= setstring(trim($_POST['spfont']));
  $properties['Splash Page Image']= setstring(trim($_POST['spimage']));
//  $properties['Splash Page Text 2']= setstring(trim($_POST['sptext2']));

  $sptext= setstring(trim($_POST['sptext1']));
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


  $sptext= setstring(trim($_POST['sptext2']));
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
