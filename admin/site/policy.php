<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/policy.php");
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

if($postaction=='savesite')
{
	$properties['Display Site Policy']=$db->setinteger($_POST['displaypolicy']);
	$properties['Site Policy Title']=$db->setstring(fixquotes($_POST['policytitle']));
	
	$success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");
	
	if($success="1")
	{
		$message="Site policy settings saved";
	}
	else
	{
		$message="Failed to save site policy".$sql;
	}
}

$content = new AdminMain($page, "sitepolicy", new AdminMessage($message, $error), new SitePolicy());
print($content->toHTML());
$db->closedb();

?>
