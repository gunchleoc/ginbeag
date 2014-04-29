<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

/*
print("Get: ");
print_r($_GET);
print("<br />Post: ");
print_r($_POST);
*/

if(isset($_GET['action']))
{
	$action=$_GET['action'];
}
else $action="";
unset($_GET['action']);
//unset($_POST['action']);

include_once($projectroot."admin/includes/objects/adminmain.php");

if(issiteaction($action))
{
	include($projectroot."admin/includes/legalsitevars.php");
}
else
{
	include($projectroot."admin/includes/legaladminvars.php");
}

include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/sessions.php");

if(isset($_GET['logout']))
{
	unset($_GET['logout']);
  	logout();
 	unlockuserpages();
}

checksession();

include_once($projectroot."admin/includes/actions.php");

if(isset($_GET['jumppage']))
{
	$_GET['page']=$_GET['jumppage'];
	unset($_GET['jumppage']);
}

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['unlock']))
{
	unlockpage($page);
}
unset($_GET['unlock']);

/**************** separate handling for main scripts *******************/
/**************** site *******************/
if($action=="site")
{
	include_once($projectroot."admin/includes/objects/site/stats.php");
	$admin = new AdminMain($page,$action,"", new SiteStatsTable(20,date("Y",strtotime('now')), date("m",strtotime('now')), "month"));
	print($admin->toHTML());
}
elseif($action=="sitestats")
{
	include_once($projectroot."admin/site/stats.php");
}
elseif($action=="sitereferrers")
{
	include_once($projectroot."admin/site/referrers.php");
}
elseif($action=="sitepagetype")
{
	include_once($projectroot."admin/site/pagetypes.php");
}
elseif($action=="sitepagerestrict")
{
	include_once($projectroot."admin/site/restrictedpages.php");
}
elseif($action=="sitelayout")
{
	include_once($projectroot."admin/site/layout.php");
}
elseif($action=="siteiotd")
{
	include_once($projectroot."admin/site/iotd.php");
}
elseif($action=="sitespam")
{
	include_once($projectroot."admin/site/antispam.php");
}
elseif($action=="siteguest")
{
	include_once($projectroot."admin/site/guestbookadmin.php");
}
elseif($action=="sitepolicy")
{
	include_once($projectroot."admin/site/policy.php");
}
elseif($action=="sitebanner")
{
	include_once($projectroot."admin/site/bannersadmin.php");
}
elseif($action=="sitetech")
{
	include_once($projectroot."admin/site/technical.php");
}
elseif($action=="sitedb")
{
	include_once($projectroot."admin/site/dbutils.php");
}
elseif($action=="siteind")
{
	include_once($projectroot."admin/site/rebuild.php");
}
elseif($action=="siteuserman")
{
	include_once($projectroot."admin/site/usermanagement.php");
}
elseif($action=="siteuserperm")
{
	include_once($projectroot."admin/site/userpermissions.php");
}
elseif($action=="siteuserlist")
{
	include_once($projectroot."admin/site/userlist.php");
}
elseif($action=="siteusercreate")
{
	include_once($projectroot."admin/site/createpublicuser.php");
}
elseif($action=="siteipban")
{
	include_once($projectroot."admin/site/ipban.php");
}
elseif($action=="siteonline")
{
	include_once($projectroot."admin/site/whosonline.php");
}
/**************** edit *******************/ 
elseif($action=="edit")
{
	include_once($projectroot."admin/pageedit.php");
}
else
{
	$admin = new AdminMain($page,$action);
	print($admin->toHTML());
}
?>