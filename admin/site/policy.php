<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/policy.php");
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

if($postaction=='savesite')
{
  	$message=savesitefeatures();
}

$content = new AdminMain($page,"sitepolicy",$message,new SitePolicy());
print($content->toHTML());
$db->closedb();

//
//
//
function savesitefeatures()
{
  global $_POST, $db;
  
  $message="";

  $properties['Display Site Policy']=$db->setinteger($_POST['displaypolicy']);
  $properties['Site Policy Title']=$db->setstring(fixquotes($_POST['policytitle']));
  
  $success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");

  if($success="1")
  {
    $message="Site policy saved";
  }
  else
  {
    $message="Failed to save site policy".$sql;
  }
  return $message;
}

?>
