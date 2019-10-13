<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/cookies.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/objects/elements.php");

//
// returns array with sid and message
// todo check if retires are created properly
//
function publiclogin($username, $password) {
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
				if($result['sid']) {
					$result['message']=getlang("login_success");
					set_session_cookie(false,$result['sid'],$ip);
				}
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
function checkpublicpassword($username,$md5password) {
	$sql = new SQLSelectStatement(PUBLICUSERS_TABLE, 'password', array('username'), array($username), 's');
	return ($md5password===$sql->fetch_value());
}


//
//
//
function deletesession($sid) {
	set_session_cookie(false,"","");
	$sql = new SQLDeleteStatement(PUBLICSESSIONS_TABLE, array('session_id'), array($sid), 's');
	return $sql->run();
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
function updatepubliclogindate($user, $ip) {
	$sid = getsidforpublicuser($user, $ip);

	if ($sid) {
		$retries = getpublicretries($user, $ip);

		$sql = new SQLUpdateStatement(PUBLICSESSIONS_TABLE,
			array('session_time', 'retries'), array('session_id'),
			array(date(DATETIMEFORMAT, strtotime('now')), $retries + 1, $sid), 'sis');
		$sql->run();
	} else {
		createpublicsession($user, $ip, 0);
	}
}


//
//
//
function createpublicsession($user,$ip,$session_valid) {
	mt_srand(make_seed());
	$sid = md5("".mt_rand());

	clearpublicsessions();

	$lastsession = getsidforpublicuser($user, $ip);

	if ($lastsession) {
		deletesession($lastsession);
	}

	if (!$ip > 0) return "";

	$sql = new SQLInsertStatement(
		PUBLICSESSIONS_TABLE,
		array('session_id', 'session_user_id', 'session_time', 'session_ip', 'session_valid', 'retries'),
		array($sid, $user, date(DATETIMEFORMAT, strtotime('now')), $ip, $session_valid, 0),
		'sisiii');
	$sql->run();
	return $sid;
}


//
//
//
function clearpublicsessions() {
	$sql = new SQLDeleteStatement(PUBLICSESSIONS_TABLE, array(), array(date(DATETIMEFORMAT, strtotime('-1 hours'))), 's', 'session_time < ?');
	return $sql->run();
}

//
//
//
function publictimeout($sid) {
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_time', array('session_id'), array($sid), 's');
	$sessiontime = $sql->fetch_value();

	if (!$sessiontime) {
		return true;
	}

	if ($sessiontime < date(DATETIMEFORMAT, strtotime('-1 hours'))) {
		deletesession($sid);
		return true;
	}

	$sql = new SQLUpdateStatement(PUBLICSESSIONS_TABLE,
		array('session_time'), array('session_id'),
		array(date(DATETIMEFORMAT, strtotime('now')), $sid), 'ss');
	$sql->run();
	return false;
}

//
//
//
function checkpublicsession($page) {
	global $_GET, $sid;
	$isvalid=$sid && ispublicsessionvalid($sid);
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
	    exit;
    }
}



//
// todo bug
//
function getsidforpublicuser($user,$ip) {
	if (empty($user) || !$ip) return "";
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_id', array('session_user_id', 'session_ip'), array($user, $ip), 'si');
	return $sql->fetch_value();
}

//
//
//
function getpublicretries($user,$ip) {
	$sid = getsidforpublicuser($user, $ip);
	if (empty($sid)) return 0;
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'retries', array('session_id'), array($sid), 's');
	return $sql->fetch_value();
}

//
//
//
function getlastpubliclogin($user,$ip) {
	$sid = getsidforpublicuser($user,$ip);
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_time', array('session_id'), array($sid), 's');
	return $sql->fetch_value();
}

//
//
//
function getpublicsiduser($sid) {
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_user_id', array('session_id'), array($sid), 's');
	return $sql->fetch_value();
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
function ispublicsessionvalid($sid) {
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_valid', array('session_id'), array($sid), 's');
	return $sql->fetch_value();
}


//
//
//
function ispublicuseripbanned($ip) {
	if ($ip=="") return false;
	$sql = new SQLSelectStatement(RESTRICTEDPAGESBANNEDIPS_TABLE, 'ip', array('ip'), array($ip), 's');
	return $sql->fetch_value() == $ip;
}


// *************************** session data for who's online **************** //

//
//
//
function getallpublicsessions()
{
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_id');
	return $sql->fetch_column();
}

//
//
//
function getpublicip($sid) {
	$sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_ip', array('session_id'), array($sid), 's');
	return $sql->fetch_value();
}
?>
