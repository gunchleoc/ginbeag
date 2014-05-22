<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
//include_once($projectroot."includes/includes.php");
//include_once($projectroot."includes/functions.php");
//include_once($projectroot."includes/objects/elements.php");


################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
//
//
function createtoken()
{
	global $db;
	
	$success = false;
	$token = "";
	$useragent = substr($_SERVER["HTTP_USER_AGENT"], 0, 255);
	
	if(strlen($useragent) > 0)
	{
	  	$now=strtotime('now');
	
	  	mt_srand(make_seed());
	  	$token = md5("".mt_rand());

	  	cleartokens();
	  
		$values = array();
		$values[] = $token;
		$values[] = date(DATETIMEFORMAT, $now);
		$values[] = $useragent;
		insertentry(ANTISPAM_TOKENS_TABLE,$values);
	}
	else $token = "";
	return $token;
}


//
//
//
function cleartokens()
{
	global $db;
	deleteentry(ANTISPAM_TOKENS_TABLE,"session_time < '".date(DATETIMEFORMAT, strtotime('-1 hours'))."'");
}


//
//
//
function checktoken($token)
{
	global $SERVER, $db;
	
	$useragent = substr($_SERVER["HTTP_USER_AGENT"], 0, 255);

	$tokenagent = getdbelement("browseragent",ANTISPAM_TOKENS_TABLE, "token_id", $db->setstring($token));
	return ($useragent === $tokenagent);
}
?>
