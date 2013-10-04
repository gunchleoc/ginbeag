<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/users.php");
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

$message="";

$ref="";
if(isset($_GET['ref'])) $ref=$_GET['ref'];

// print_r($_POST);
// print_r($_GET);


$content = new AdminMain($page,"siteuserlist",$message,new SiteUserlist($ref));
print($content->toHTML());
$db->closedb();
?>