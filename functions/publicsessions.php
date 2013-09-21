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
    $user_id=getpublicuserid($username);

    $result=array();
    $proceed=true;
    $retries=getpublicretries($user_id,$ip);

    if($retries>=3)
    {

      $time=date(DATETIMEFORMAT, strtotime('-15 minutes'));
      $lastlogin=getlastpubliclogin($user_id,$ip);
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
        $result['sid']=createpublicsession($user_id,$ip,1);
        if($result['sid'])
        {
          $result['message']=getlang("login_success");
        }
        else
        {
          $result['message']=getlang("login_error_sessionfail");
        }
      }
      else
      {
        $result['message']=getlang("login_error_username");
        updatepubliclogindate($user_id,$ip);
      }
    }
  }
  else
  {
    $result['message']=getlang("login_error_ipban");
  }
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

  if($dbpassword===$md5password)
  {
    $result=true;
  }
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
  $sql=$db->singlequery($query);
}

//
//
//
function publiclogout($sid)
{
  deletesession($sid);
}

//
//
//
function updatepubliclogindate($user_id,$ip)
{
	global $db;
  $user_id=$db->setinteger($user_id);

  $now=strtotime('now');
  
  $sid=getsidforpublicuser($user_id,$ip);
  
  if($sid)
  {
    $query=("update ");
    $query.=(PUBLICSESSIONS_TABLE." set ");
    $query.="session_time=";
    $query.="'".date(DATETIMEFORMAT, $now)."'";
    $query.=" where session_id = '".$sid."';";
//  print($query.'<p>');
    $sql=$db->singlequery($query);

    $retries=getpublicretries($user_id,$ip);

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
   createpublicsession($user_id,$ip,0);
  }
}


//
//
//
function createpublicsession($user_id,$ip,$session_valid)
{
	global $db;
  $user_id=$db->setinteger($user_id);
  $session_valid=$db->setinteger($session_valid);

  $result="";

  $now=strtotime('now');

  mt_srand(make_seed());
  $sid = md5("".mt_rand());

  clearpublicsessions();
  
  $lastsession=getsidforpublicuser($user_id,$ip);

  if($lastsession)
  {
    deletesession($lastsession);
  }

  $values=array();
  $values[]=$sid;
  $values[]=$user_id;
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
//  print('<p>'.$query);

  $sql=$db->singlequery($query);

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
      $sql=$db->singlequery($query);
    }
  }
  return $result;
}

//
//
//
function checkpublicsession($page_id)
{
	global $db;
	global $_GET;
	$isvalid=isset($_GET["sid"]) && ispublicsessionvalid($db->setstring($_GET["sid"]));
	//  $user_id=getpublicsiduser($_GET["sid"]);
	if(!isset($_GET["sid"])) $hasaccess = false;
	else $hasaccess =hasaccesssession($_GET["sid"], $page_id);

	if(!$isvalid || publictimeout($_GET["sid"]) || !$hasaccess)
	// todo: reinstate ip check
	//if(!$isvalid || publictimeout($_GET["sid"]) || !checkpublicip($_GET["sid"]) || !hasaccesssession($_GET["sid"],$page_id))
	{

		if(!$hasaccess)
		{
			$message=getlang("restricted_nopermission");
		}
		else
		{
		   $message=getlang("restricted_expired");
		}
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
//
//
function checkpublicip($sid)
{
	global $db;
  $sid=$db->setstring($sid);
  
  $clientip=getclientip();
  if($clientip)
  {
    $sessionip=getdbelement("session_ip",PUBLICSESSIONS_TABLE, "session_id", $sid);
    $clientprefix=(substr($clientip,0,6));
    $sessionprefix=(substr($sessionip,0,6));
    return($clientprefix===$sessionprefix);
  }
  else return false;

}

//
// todo bug
//
function getsidforpublicuser($user_id,$ip)
{
	global $db;
  $result=false;
  $query="select session_id from ".PUBLICSESSIONS_TABLE." where session_user_id = '".$db->setinteger($user_id)."' AND session_ip = '".$db->setinteger($ip)."';";
  //print($query);
  $sql=$db->singlequery($query);
  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }
  return $result;
}

//
//
//
/*function getloggedinusers()
{
	global $db;
  $result=array();
  
  $query="select username from ";
  $query.=USERS_TABLE." as users, ";
  $query.=SESSIONS_TABLE." as sessions";
  $query.=" where users.user_id = sessions.session_user_id";
  $query.=" order by users.username ASC";
    
  $sql=$db->singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);
    }
  }
  return $result;
}*/


//
//
//
function getpublicretries($user_id,$ip)
{
  $sid=getsidforpublicuser($user_id,$ip);
  return getdbelement("retries",PUBLICSESSIONS_TABLE, "session_id", $sid);
}

//
//
//
function getlastpubliclogin($user_id,$ip)
{
  $sid=getsidforpublicuser($user_id,$ip);
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
  global $_GET;
  $result=false;
  if(isset($_GET["sid"]))
  {
  
    $result=getpublicsiduser($_GET["sid"]);
  }
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
