<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/treefunctions.php");
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
// todo check if retires are created properly
//
function publiclogin($username,$password)
{
	global $db;
	$username=$db->setstring($username);
	$password=md5($password);
	$ip=getclientip();
	
	if(!ispublicuseripbanned($ip))
	{
		$user=getpublicuserid($username);
		$result=array();
		$proceed=true;
		$retries=getpublicretries($user,$ip);
		
		if($retries>=3)
		{
			$time=date(DATETIMEFORMAT, strtotime('-15 minutes'));
			$lastlogin=getlastpubliclogin($user,$ip);
			if($lastlogin>=$time)
			{
				$result['message']=getlang("login_passwordcount");
				$proceed=false;
			}
		}
		if($proceed)
		{
			if(checkpublicpassword($username,$password))
			{
				$result['sid']=createpublicsession($user,$ip,1);
				if($result['sid']) $result['message']=getlang("login_success");
				else $result['message']=getlang("login_error_sessionfail");
			}
			else
			{
			$result['message']=getlang("login_error_username");
			updatepubliclogindate($user,$ip);
			}
		}
	}
	else $result['message']=getlang("login_error_ipban");
	return $result;
}

//
//
//
function checkpublicpassword($username,$md5password)
{
	global $db;
	$username=$db->setstring($username);
	$md5password=$db->setstring($md5password);
	
	$result=false;
	$dbpassword=getdbelement("password",PUBLICUSERS_TABLE, "username", $username);
	
	if($dbpassword===$md5password) $result=true;
	return $result;
}


//
//
//
function deletesession($sid)
{
	global $db;
	$query="DELETE FROM ".PUBLICSESSIONS_TABLE;
	$query.=" where session_id='".$db->setstring($sid)."';";
	//  print($query.'<p>');
	return $db->singlequery($query);
}

//
//
//
function publiclogout($sid)
{
	return deletesession($sid);
}

//
//
//
function updatepubliclogindate($user,$ip)
{
	global $db;
	$user=$db->setinteger($user);
	
	$now=strtotime('now');
	
	$sid=getsidforpublicuser($user,$ip);
	
	if($sid)
	{
		$query=("update ");
		$query.=(PUBLICSESSIONS_TABLE." set ");
		$query.="session_time=";
		$query.="'".date(DATETIMEFORMAT, $now)."'";
		$query.=" where session_id = '".$sid."';";
		//  print($query.'<p>');
		$sql=$db->singlequery($query);
		
		$retries=getpublicretries($user,$ip);
		
		$query=("update ");
		$query.=(PUBLICSESSIONS_TABLE." set ");
		$query.="retries=";
		$query.="'".($retries+1)."'";
		$query.=" where session_id = '".$sid."';";
		//  print($query.'<p>');
		$sql=$db->singlequery($query);
	}
	else
	{
		createpublicsession($user,$ip,0);
	}
}


//
//
//
function createpublicsession($user,$ip,$session_valid)
{
	global $db;
	$user=$db->setinteger($user);
	$session_valid=$db->setinteger($session_valid);
	
	$result="";
	
	$now=strtotime('now');
	
	mt_srand(make_seed());
	$sid = md5("".mt_rand());
	
	clearpublicsessions();
	
	$lastsession=getsidforpublicuser($user,$ip);
	
	if($lastsession)
	{
		deletesession($lastsession);
	}
	
	$values=array();
	$values[]=$sid;
	$values[]=$user;
	$values[]=date(DATETIMEFORMAT, $now);
	$values[]=$ip;
	$values[]=$session_valid;
	$values[]=0;
	
	$query="insert into ".PUBLICSESSIONS_TABLE." values(";
	for($i=0;$i<count($values)-1;$i++)
	{
		$query.="'".$values[$i]."', ";
	}
	$query.="'".$values[count($values)-1]."');";
	$db->singlequery($query);
	return $sid;
}


//
//
//
function clearpublicsessions()
{
	global $db;
	$time=strtotime('-1 hours');
	$query="DELETE FROM ".PUBLICSESSIONS_TABLE;
	$query.=" where session_time < '".date(DATETIMEFORMAT, $time)."'";
	$db->singlequery($query);
}

//
//
//
function publictimeout($sid)
{
	global $db;
	$sid=$db->setstring($sid);
	
	$result=false;
	
	$sessiontime=getdbelement("session_time",PUBLICSESSIONS_TABLE, "session_id", $sid);
	
	if(!$sessiontime)
	{
		$result=true;
	}
	else
	{
		$time=date(DATETIMEFORMAT, strtotime('-1 hours'));
		
		if($sessiontime<$time)
		{
			deletesession($sid);
			$result=true;
		}
		else
		{
			$now=date(DATETIMEFORMAT, strtotime('now'));
			
			$query=("update ");
			$query.=(PUBLICSESSIONS_TABLE." set ");
			$query.="session_time=";
			$query.="'".$now."'";
			$query.=" where session_id = '".$sid."';";
			//  print($query.'<p>');
			$db->singlequery($query);
		}
	}
	return $result;
}

//
//
//
function checkpublicsession($page)
{
	global $db;
	global $_GET, $sid;
	$isvalid=$sid && ispublicsessionvalid($db->setstring($sid));
	//  $user=getpublicsiduser($_GET["sid"]);
	if(!$sid) $hasaccess = false;
	else $hasaccess = hasaccesssession($page);

	if(!$isvalid || publictimeout($sid) || !$hasaccess)
	// todo: replace ip check with browser agent check
	//if(!$isvalid || publictimeout($sid) || !checkpublicip($sid) || !$hasaccess)
	{
		if(!$hasaccess) $message=getlang("restricted_nopermission");
		else $message=getlang("restricted_expired");

		$contenturl="login.php".makelinkparameters($_GET);
	    $title=getlang("restricted_pagetitle");
	    $header = new HTMLHeader($title,$title,$message,$contenturl,getlang("restricted_pleaselogin"),true);
	    print($header->toHTML());
	
	    $footer = new HTMLFooter();
	    print($footer->toHTML());
	    $db->closedb();
	    exit;
    }
}



//
// todo bug
//
function getsidforpublicuser($user,$ip)
{
	global $db;
	$query="select session_id from ".PUBLICSESSIONS_TABLE." where session_user_id = '".$db->setinteger($user)."' AND session_ip = '".$db->setinteger($ip)."';";
	//print($query);
	return getdbresultsingle($query);
}

//
//
//
function getpublicretries($user,$ip)
{
	$sid=getsidforpublicuser($user,$ip);
	return getdbelement("retries",PUBLICSESSIONS_TABLE, "session_id", $sid);
}

//
//
//
function getlastpubliclogin($user,$ip)
{
	$sid=getsidforpublicuser($user,$ip);
	return getdbelement("session_time",PUBLICSESSIONS_TABLE, "session_id", $sid);
}

//
//
//
function getpublicsiduser($sid)
{
	global $db;
	return getdbelement("session_user_id",PUBLICSESSIONS_TABLE, "session_id", $db->setstring($sid));
}

//
//
//
function ispublicloggedin()
{
	global $sid;
	$result=false;
	if(strlen($sid) > 0) $result=getpublicsiduser($sid);
	return $result;
}


//
//
//
function ispublicsessionvalid($sid)
{
	global $db;
	return getdbelement("session_valid",PUBLICSESSIONS_TABLE, "session_id", $db->setstring($sid));
}


//
//
//
function ispublicuseripbanned($ip)
{
	// only for PHP 4 $ip=ip2long($ip);
	$dbip = getdbelement("ip",RESTRICTEDPAGESBANNEDIPS_TABLE, "ip", $ip);
	return $dbip == $ip;
}


// *************************** session data for who's online **************** //

//
//
//
function getallpublicsessions()
{
	return getcolumn("session_id",PUBLICSESSIONS_TABLE,"1");
}

//
//
//
function getpublicip($sid)
{
	global $db;
	return getdbelement("session_ip",PUBLICSESSIONS_TABLE, "session_id", $db->setstring($sid));
}
?>
