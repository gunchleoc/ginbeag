<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/profile.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

// HTTP-vars
if(isset($_POST['oldpass'])) $oldpass=trim($_POST['oldpass']);
else $oldpass="";

if(isset($_POST['pass'])) $pass=trim($_POST['pass']);
else $pass="";

if(isset($_POST['passconfirm'])) $passconf=trim($_POST['passconfirm']);
else $passconf="";

if(isset($_POST['email'])) $email=trim($_POST['email']);
else $email="";


$userid=getsiduser();
$message = "";
$error = false;

if(isset($_POST['contact']))
{
	$message='Changed contact page options';

	if(isset($_POST['iscontact']))
	{
		changeiscontact($userid,1);
	}
	else
	{
		changeiscontact($userid,0);
	}
	changecontactfunction($userid,fixquotes($_POST['contactfunction']));
}
else
{
	if($pass)
	{
		$passresult = changeuserpassword($userid,$oldpass,$pass,$passconf);
		if($passresult["error"])
		{
			$error = true;
			$message = 'Failed to change password: '.$passresult["message"]." ";
		}
		else
		{
			$message = 'Changed password.';
		}
	}
	if($email)
	{
		if(emailexists($email,$userid))
		{
			$message.=' E-mail <i>'.$email.'</i> already exists!';
			$error = true;
		}
		else
		{
			changeuseremail($userid,$email);
			$message.= 'Changed e-mail address.';
		}
	}
}

$content = new AdminMain($page, "profile", new AdminMessage($message, $error), new ProfilePage($userid));

print($content->toHTML());
$db->closedb();
?>
