<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/includes/objects/site/rebuildindices.php");
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

if($postaction==='restrictedpages')
{
	$message = rebuildaccessrestrictionindex();
}

$content = new AdminMain($page, "siteind", new AdminMessage($message, $error), new SiteRebuildIndices($message));
print($content->toHTML());
?>
