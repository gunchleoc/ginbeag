<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/ipban.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();
checkadmin();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

if(isset($_POST['banipallrestricted']))
{
	$ip=trim($_POST['ip']);
	if($ip === long2ip(ip2long($ip)))
	{
		addbannedipforrestrictedpages($ip);
	}
	else
	{
		$message='<i>'.$ip.'</i> is not a valid IP address.';
	}
}
elseif(isset($_POST['unbanipallrestricted']))
{
	removebannedipforrestrictedpageas($_POST['ip']);
	$message='<i>'.$_POST['ip'].'</i> has been unbanned.';
}


$content = new AdminMain($page,"siteipban",$message,new SiteIPBan());
print($content->toHTML());

$db->closedb();
?>