<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/includes/objects/site/users.php");
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


// print_r($_POST);
// print_r($_GET);

$message="";
$register=-1;

if(isset($_POST['username'])) $username=fixquotes(trim($_POST['username']));
else $username="";

if(isset($_POST['pass'])) $pass=$_POST['pass'];
else $pass="";

if(isset($_POST['passconfirm'])) $passconf=$_POST['passconfirm'];
else $passconf="";


if($username && $pass===$passconf)
{
	if(publicuserexists($username))
	{
		$message='Username already exists!';
	}
	elseif(!$pass)
	{
		$message='Please specify a password!';
	}
	else
	{
		$register=addpublicuser($username,$pass);
		
		if($register)
		{
			$message='Created user <em>'.$username.'</em> successfully';
			$username="";
		}
		else
		{
			$message='Error creating user';
		}
	}
}
elseif($username && $pass!=$passconf)
{
	$message='Passwords did not match!';
}

$content = new AdminMain($page,"siteusercreate","",new SiteCreatePublicUser($username, $message, $register));
print($content->toHTML());

$db->closedb();

?>
