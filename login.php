<?php
$projectroot=dirname(__FILE__)."/";

include_once($projectroot."functions/db.php");

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") $testpath = "";

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."login.php") ||
	$_SERVER["PHP_SELF"] == $testpath."/login.php"))
{
//	print("test: ".$_SERVER["PHP_SELF"]);
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."functions/publicusers.php");
//include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/page.php");



//print_r($_GET);
//print_r($_POST);

if(isset($_POST['user']))
{
	$user=trim($_POST['user']);
	$userid=getpublicuserid($user);
	
	if(!$userid)
	{
		$header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")));
		$loginform = new LoginForm($user,getlang("login_error_username"));
	}
	elseif(ispublicuseractive($userid))
	{
		$login=publiclogin($user,trim($_POST['pass']));
		if(array_key_exists('sid',$login))
		{
			$_GET['sid']= $login['sid'];
			$contenturl='index.php'.makelinkparameters($_GET,true);
			$header = new HTMLHeader("Login","Login",$login['message'],$contenturl,getlang("login_enter"),true);
		}
		else
		{
			$header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")));
			$loginform = new LoginForm($user,$login['message']);
		}
	}
	else
	{
		$header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")));
		$loginform = new LoginForm("",getlang("login_error_inactive"));
	}
}
else
{
	$header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")));
  	$loginform = new LoginForm("");
}

print($header->toHTML());
if (isset($loginform)) print($loginform->toHTML());

$footer = new PageFooter();
print($footer->toHTML());

$db->closedb();

?>
