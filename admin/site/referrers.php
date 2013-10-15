<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/referrersmod.php");
include_once($projectroot."admin/includes/objects/site/referrers.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();
checkadmin();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

// print_r($_POST);
// print_r($_GET);


if(isset($_POST['unblock']))
{
	$referrers= new SiteReferrerUnblockForm($_POST['referrer']);
}
else
{
	if(isset($_POST['confirmunblock']))
	{
  		$message='Unblocked Referrer <i>'.$_POST['referrer'].'</i>';
  		deleteblockedreferrer($_POST['referrer']);
	}
	elseif(isset($_POST['block']))
	{
		$message='Blocked Referrer <i>'.$_POST['referrer'].'</i>';
  		addblockedreferrer(trim($_POST['referrer']));
	}
	$referrers= new SiteReferrers();
}

$content = new AdminMain($page,"sitereferrers","",$referrers);
print($content->toHTML());

$db->closedb();
?>