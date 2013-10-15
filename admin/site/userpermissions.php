<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/includes/objects/site/userpermissions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();
checkadmin();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

if(isset($_GET['userid'])) $userid=$_GET['userid'];
elseif(isset($_POST['userid'])) $userid=$_POST['userid'];
else $userid=-1;

if(isset($_GET['username'])) $username=$_GET['username'];
else $username="";

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['searchuser']))
{
	$userid=getuserid($_POST['username']);
}
elseif(isset($_POST['searchpublicuser']))
{
	$userid=getpublicuserid($_POST['username']);
}
if((isset($_POST['searchuser']) || isset($_POST['searchpublicuser'])) && !$userid)
{
	$message='User <i>'.$_POST['username'].'</i> not found.';
}
// public users for restricted areas
elseif(isset($_GET['changeaccess']) && $_GET['changeaccess']==="removepage")
{
	removepageaccess(array(0 => $userid),$_POST["pageid"]);
	$message='Removed Page';
}
elseif(isset($_GET['changeaccess']) && $_GET['changeaccess']==="addpage")
{
	addpageaccess(array(0 => $userid),$_POST["pageid"]);
	$message='Added Page';
}
// webpage editors
elseif(isset($_POST['changelevel']) || isset($_GET['changelevel']))
{
	setuserlevel($userid,$_POST['userlevel']);
	if($_POST['userlevel']==USERLEVEL_USER)
	{
		$message='Userlevel for <i>'.getusername($userid).'</i> set to <i>User</i>';
	}
	elseif($_POST['userlevel']==USERLEVEL_ADMIN)
	{
		$message='Userlevel for <i>'.getusername($userid).'</i> set to <i>Administrator</i>';
	}
}
if($userid>0)
{
	if(isset($_GET['type']) && $_GET['type']==="public" || isset($_POST['searchpublicuser']))
	{
		$contents= new SitePublicUserAccessForm($userid);
	}
	else
	{
		$contents= new SiteUserLevelForm($userid);
	}
}
else
{
	$contents= new SiteSelectUserPermissionsForm($username);
}

$content = new AdminMain($page,"siteuserperm",$message,$contents);
print($content->toHTML());
$db->closedb();
?>