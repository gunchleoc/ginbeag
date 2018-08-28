<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));
include_once($projectroot."functions/db.php");

//
//
//
function makecookiepath($isadmin)
{
	$localpath =getproperty("Local Path");
	if($isadmin) $localpath=$localpath."/admin/";
	if(!str_startswith($localpath,"/")) $localpath="/".$localpath;
	return $localpath;
}

//
//
//
function set_session_cookie($isadmin,$sid,$userid)
{
	$cookieprefix = getproperty("Cookie Prefix");
	$cookiedomain = getproperty("Domain Name");
	$localpath = makecookiepath($isadmin);
	$cookiesecure = getproperty("Server Protocol") == "https://";

	// **PREVENTING SESSION HIJACKING**
	// Prevents javascript XSS attacks aimed to steal the session ID
	ini_set('session.cookie_httponly', 1);

	// **PREVENTING SESSION FIXATION**
	// Session ID cannot be passed through URLs
	ini_set('session.use_only_cookies', 1);

	// More session security stuff
	ini_set('session.use_strict_mode', 1);
	ini_set('session.referer_check', $cookiedomain);

	if($cookiesecure)
	{
		// Uses a secure connection (HTTPS) if possible
		ini_set('session.cookie_secure', 1);
	}

	setcookie($cookieprefix."sid", $sid,0, $localpath, $cookiedomain, $cookiesecure, 1);
	setcookie($cookieprefix."userid", $userid, 0, $localpath, $cookiedomain, $cookiesecure, 1);
}
?>
