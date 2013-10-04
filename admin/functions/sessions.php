<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
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
	
	$user_id=getuserid($username);
	
	$result=array();
	$proceed=true;
	$retries=getretries($user_id);
	
	if($retries>=3)
	{
	
		$time=date(DATETIMEFORMAT, strtotime('-15 minutes'));
		$lastlogin=getlastlogin($user_id);
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
			$result['sid']=createsession($user_id);
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
function logout($sid)
{
	global $db;
	
	$cookieprefix = getproperty("Cookie Prefix");
	$cookiedomain = getproperty("Domain Name");
	$localpath =makecookiepath();

		
	setcookie($cookieprefix."sid", "",1, $localpath, $cookiedomain, 0, 1);
	setcookie($cookieprefix."userid", "", 1, $localpath, $cookiedomain, 0, 1);
	setcookie($cookieprefix."clientip", "", 1, $localpath, $cookiedomain, 0, 1);
	
	unlockuserpages($sid);
  	optimizetable(LOCKS_TABLE);
  	optimizetable(NEWSITEMSECTIONS_TABLE);
  	optimizetable(NEWSITEMS_TABLE);
  	optimizetable(MONTHLYPAGESTATS_TABLE);
  	deleteentry(SESSIONS_TABLE,"session_id='".$db->setstring($sid)."'");
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
	global $db;
	// create date
	$adayago=date(DATETIMEFORMAT, strtotime('-1 day'));
	$query="DELETE FROM ".PAGECACHE_TABLE." where lastmodified < '".$adayago."';";
	$sql=$db->singlequery($query);
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
function createsession($user_id)
{
	global $db;
	
	
  	$user_id=$db->setinteger($user_id);

  	$result="";

  	$ip=getclientip();
  	$now=strtotime('now');

  	mt_srand(make_seed());
  	$sid = md5("".mt_rand());

  	clearsessions();
  
  	$lastsession=getdbelement("session_id",SESSIONS_TABLE, "session_user_id", $user_id);
  	if($lastsession)
  	{
    	$sql=deleteentry(SESSIONS_TABLE,"session_id='".$lastsession."'");
  	}

  	$values=array();
  	$values[]=$sid;
  	$values[]=$user_id;
  	$values[]=date(DATETIMEFORMAT, $now);
  	$values[]=$ip;

  	$sql=insertentry(SESSIONS_TABLE,$values);
  	
  	$cookieprefix = getproperty("Cookie Prefix");
  	$cookiedomain = getproperty("Domain Name");
	$localpath =makecookiepath();
  	
	setcookie($cookieprefix."userid", $user_id,0, $localpath, $cookiedomain, 0, 1);
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
	$result=false;
	$time=strtotime('-1 hours');
	
	$sql=deleteentry(SESSIONS_TABLE,"session_time<'".date(DATETIMEFORMAT, $time)."'");
	
	return $result;
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
function checksession($sid)
{
	global $_GET, $db, $projectroot;
	if(!isloggedin($sid))
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
function isloggedin($sid)
{
	global $sid, $_COOKIE;
	
	$result=true;
	
	//print_r($_COOKIE);
	
	$cookieprefix = getproperty("Cookie Prefix");
	
	$userid=getdbelement("session_user_id",SESSIONS_TABLE, "session_id", $sid);
	$clientip=getclientip();
	
	if(!isset($_COOKIE[$cookieprefix."sid"]) || $_COOKIE[$cookieprefix."sid"]!=$sid) $result=false;
	if(!isset($_COOKIE[$cookieprefix."userid"]) || $_COOKIE[$cookieprefix."userid"]!=$userid) $result=false;
	if(!isset($_COOKIE[$cookieprefix."clientip"]) || substr($_COOKIE[$cookieprefix."clientip"],0,6)!=substr($clientip,0,6)) $result=false;

	if(timeout($sid)) $result=false;
	
	if(!checkip($sid, $userid, $clientip)) $result=false;

	return $result;
}

//
//
//
function isadmin($sid)
{
	$userlevel=getdbelement("userlevel", USERS_TABLE, "user_id",getsiduser($sid));
	return $userlevel==USERLEVEL_ADMIN;
}


//
//
//
function checkip($sid,$userid,$clientip)
{
	global $db;
  	$sid=$db->setstring($sid);
  
  	// quick fix for Sue + pplaskka who can't get in
  	if($userid==7 || $userid==13 || $userid==19) return true;
  
  	if($clientip)
  	{
    	$sessionip=getdbelement("session_ip",SESSIONS_TABLE, "session_id", $sid);
    	$clientprefix=(substr($clientip,0,6));
    	$sessionprefix=(substr($sessionip,0,6));
    	return($clientprefix===$sessionprefix);
  	}
  	else return false;
}

//
//
//
function getsiduser($sid)
{
	global $db;
	return getdbelement("session_user_id",SESSIONS_TABLE, "session_id", $db->setstring($sid));
}


//
//
//
function getusersid($user)
{
	global $db;
	return getdbelement("session_id",SESSIONS_TABLE, "session_user_id", $db->setstring($user));
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
function isactive($user_id)
{
	global $db;
	return getdbelement("user_active",USERS_TABLE, "user_id", $db->setinteger($user_id));
}

//
//
//
function getretries($user_id)
{
	global $db;
	return getdbelement("retries",USERS_TABLE, "user_id", $db->setinteger($user_id));
}

//
//
//
function getlastlogin($user_id)
{
	global $db;
	return getdbelement("last_login",USERS_TABLE, "user_id", $db->setinteger($user_id));
}
?>