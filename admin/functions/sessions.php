<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/objects/elements.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
// returns array with sid and message
//
function login($username,$password)
{
	global $db;
	$username=$db->setstring($username);
	$password=md5($password);
	
	$user=getuserid($username);
	
	$result=array();
	$proceed=true;
	$retries=getretries($user);
	
	if($retries>=3)
	{
	
		$time=date(DATETIMEFORMAT, strtotime('-15 minutes'));
		$lastlogin=getlastlogin($user);
		if($lastlogin>=$time)
		{
			$result['message']="You have entered the wrong password too often, so your account is locked for now. Please try again later.";
			$proceed=false;
		}
	
	}
	
	if($proceed)
	{
		if(checkpassword($username,$password))
		{
			$result['sid']=createsession($user);
			if($result['sid'])
			{
				$result['message']="login successful";
			}
			else
			{
				$result['message']="Failed to create session";
			}
			updatelogindate($username);
		}
		else
		{
			$result['message']="Wrong username or password";
			updatelogindate($username,true);
		}
	}
	return $result;
}

//
//
//
function checkpassword($username,$md5password)
{
	global $db;
	$username=$db->setstring($username);
	$md5password=$db->setstring($md5password);
	
	$result=false;
	
	$dbpassword=getdbelement("password",USERS_TABLE, "username", $username);
	if($dbpassword===$md5password)
	{
		$result=true;
	}
	return $result;
}


//
// returns sid
//
function logout()
{
	global $db;
	
	$cookieprefix = getproperty("Cookie Prefix");
	$cookiedomain = getproperty("Domain Name");
	$localpath =makecookiepath();

		
	setcookie($cookieprefix."sid", "",1, $localpath, $cookiedomain, 0, 1);
	setcookie($cookieprefix."userid", "", 1, $localpath, $cookiedomain, 0, 1);
	setcookie($cookieprefix."clientip", "", 1, $localpath, $cookiedomain, 0, 1);
	
	unlockuserpages();
  	optimizetable(LOCKS_TABLE);
  	optimizetable(NEWSITEMSECTIONS_TABLE);
  	optimizetable(NEWSITEMS_TABLE);
  	optimizetable(MONTHLYPAGESTATS_TABLE);
  	deleteentry(SESSIONS_TABLE,"session_id='".$db->setstring(getsid())."'");
  	optimizetable(SESSIONS_TABLE);
  	clearoldpagecacheentries();
}


//
//
//
function makecookiepath()
{
	$localpath =getproperty("Local Path");
	$localpath=$localpath."/admin/";
	if(!str_startswith($localpath,"/")) $localpath="/".$localpath;
	return $localpath;
}


//
//
//
function clearoldpagecacheentries()
{
	deleteentry(SESSIONS_TABLE,"lastmodified < '".date(DATETIMEFORMAT, strtotime('-1 day'))."'");
}

//
//
//
function updatelogindate($username,$increasecount=false)
{
	global $db;
	$username=$db->setstring($username);
	
	$now=strtotime('now');
	
	updatefield(USERS_TABLE,"last_login",date(DATETIMEFORMAT, $now),"username = '".$username."'");
	
	if($increasecount)
	{
		$retries=getdbelement("retries",USERS_TABLE, "username", $username);
		$newretry=$retries+1;
		updatefield(USERS_TABLE,"retries",$newretry,"username = '".$username."'");
	}
	else
	{
		updatefield(USERS_TABLE,"retries","0","username = '".$username."'");
	}
}


//
//
//
function createsession($user)
{
	global $db;
	
	
  	$user=$db->setinteger($user);

  	$result="";

  	$ip=getclientip();
  	$now=strtotime('now');

  	mt_srand(make_seed());
  	$sid = md5("".mt_rand());

  	clearsessions();
  
  	$lastsession=getdbelement("session_id",SESSIONS_TABLE, "session_user_id", $user);
  	if($lastsession)
  	{
    	$sql=deleteentry(SESSIONS_TABLE,"session_id='".$lastsession."'");
  	}

	$values=array();
	$values[]=$sid;
	$values[]=$user;
	$values[]=date(DATETIMEFORMAT, $now);
	$values[]=substr($_SERVER["HTTP_USER_AGENT"], 0, 255);

  	$sql=insertentry(SESSIONS_TABLE,$values);
  	
  	$cookieprefix = getproperty("Cookie Prefix");
  	$cookiedomain = getproperty("Domain Name");
	$localpath =makecookiepath();
  	
	setcookie($cookieprefix."userid", $user,0, $localpath, $cookiedomain, 0, 1);
	setcookie($cookieprefix."sid", $sid,0, $localpath, $cookiedomain, 0, 1);
	setcookie($cookieprefix."clientip", $ip,0, $localpath, $cookiedomain, 0, 1);

	//print("testing Domain=".$cookiedomain);
	//print("<br>testing path=".$localpath);
  	return $sid;
}


//
//
//
function clearsessions()
{
	deleteentry(SESSIONS_TABLE,"session_time<'".date(DATETIMEFORMAT, strtotime('-1 hours'))."'");
}

//
//
//
function timeout($sid)
{
	global $db;
	$sid=$db->setstring($sid);
	
	$result=false;
	
	$sessiontime=getdbelement("session_time",SESSIONS_TABLE, "session_id", $sid);
	
	if(!$sessiontime)
	{
		$result=true;
	}
	else
	{
		$time=date(DATETIMEFORMAT, strtotime('-1 hours'));
		
		if($sessiontime<$time)
		{
			deleteentry(SESSIONS_TABLE,"session_id = '".$sid."'");
			$result=true;
		}
		else
		{
			$now=date(DATETIMEFORMAT, strtotime('now'));
			updatefield(SESSIONS_TABLE,"session_time",$now,"session_id='".$sid."'");
		}
	}
	return $result;
}

//
//
//
function checksession()
{
	global $_GET, $db, $projectroot;
	if(!isloggedin())
	{
		$header = new HTMLHeader("Access restricted","Webpage Building","",getprojectrootlinkpath().'admin/login.php'.makelinkparameters($_GET),'Click or tap here to log in',false);
		print($header->toHTML());
		
		$footer = new HTMLFooter();
		print($footer->toHTML());
		
		$db->closedb();
		
		exit;
	}
}

//
//
//
function isloggedin()
{
	global $_COOKIE, $_SERVER;
	
	$cookieprefix = getproperty("Cookie Prefix");
	if(isset($_COOKIE[$cookieprefix."sid"])) $sid = $_COOKIE[$cookieprefix."sid"];
	else return false;
	
	$userid=getdbelement("session_user_id",SESSIONS_TABLE, "session_id", $sid);
	if(!isset($_COOKIE[$cookieprefix."userid"]) || $_COOKIE[$cookieprefix."userid"]!=$userid) return false;

	$clientip=getclientip();
	if(!isset($_COOKIE[$cookieprefix."clientip"]) || substr($_COOKIE[$cookieprefix."clientip"],0,6)!=substr($clientip,0,6)) return false;

	if(timeout($sid)) return false;
	
	if(!checkagent($sid, $userid, $_SERVER["HTTP_USER_AGENT"])) return false;

	return true;
}



//
//
//
function getsid()
{
	global $_COOKIE;
	
	$cookieprefix = getproperty("Cookie Prefix");
	if(isset($_COOKIE[$cookieprefix."sid"])) return $_COOKIE[$cookieprefix."sid"];
	else return "";
}


//
//
//
function isadmin()
{
	$userlevel=getdbelement("userlevel", USERS_TABLE, "user_id",getsiduser());
	return $userlevel==USERLEVEL_ADMIN;
}


//
//
//
function checkadmin()
{
	if(!isadmin())
	{
		die('<p class="highlight">You have no permission for this area</p>');
	}
}


//
// compares browser agent to entry in the sessions table
//
function checkagent($sid, $userid, $browseragent)
{
	global $db;
	$result = true;

	if($browseragent)
	{
		$sessionagent=getdbelement("browseragent",SESSIONS_TABLE, "session_id", $db->setstring($sid));
		$result = (substr($sessionagent,0,255)===substr($browseragent,0,255));
	}
	return $result;
}


//
//
//
function getsiduser()
{
	global $db;
	return getdbelement("session_user_id",SESSIONS_TABLE, "session_id", $db->setstring(getsid()));
}



//
//
//
function getloggedinusers()
{
	$query="select username from ";
	$query.=USERS_TABLE." as users, ";
	$query.=SESSIONS_TABLE." as sessions";
	$query.=" where users.user_id = sessions.session_user_id";
	$query.=" order by users.username ASC";
	return getdbresultcolumn($query);
}

//
//
//
function isactive($user)
{
	global $db;
	return getdbelement("user_active",USERS_TABLE, "user_id", $db->setinteger($user));
}

//
//
//
function getretries($user)
{
	global $db;
	return getdbelement("retries",USERS_TABLE, "user_id", $db->setinteger($user));
}

//
//
//
function getlastlogin($user)
{
	global $db;
	return getdbelement("last_login",USERS_TABLE, "user_id", $db->setinteger($user));
}
?>