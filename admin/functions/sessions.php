<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");

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
  $username=setstring($username);
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
  $username=setstring($username);
  $md5password=setstring($md5password);
  
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
  unlockuserpages($sid);
  optimizetable(LOCKS_TABLE);
  optimizetable(NEWSITEMSECTIONS_TABLE);
  optimizetable(NEWSITEMS_TABLE);
  optimizetable(MONTHLYPAGESTATS_TABLE);
  deleteentry(SESSIONS_TABLE,"session_id='".setstring($sid)."'");
  optimizetable(SESSIONS_TABLE);
  
  clearoldpagecacheentries();
}


//
//
//
function clearoldpagecacheentries()
{
  // create date
  $adayago=date(DATETIMEFORMAT, strtotime('-1 day'));
  $query="DELETE FROM ".PAGECACHE_TABLE." where lastmodified < '".$adayago."';";
  $sql=singlequery($query);
}

//
//
//
function updatelogindate($username,$increasecount=false)
{
  $username=setstring($username);

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
  $user_id=setinteger($user_id);

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
  $sid=setstring($sid);

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
  global $_GET;
  if(timeout($sid) || !checkip($sid))
  {
    $header = new HTMLHeader("Access restricted","Webpage Building","",getprojectrootlinkpath().'admin/login.php'.makelinkparameters($_GET),'please log in',false);
    print($header->toHTML());

    $footer = new HTMLFooter();
    print($footer->toHTML());

    exit;
  }
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
function checkip($sid)
{
  $sid=setstring($sid);
  
  $clientip=getclientip();
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
  return getdbelement("session_user_id",SESSIONS_TABLE, "session_id", setstring($sid));
}

//
//
//
function getloggedinusers()
{
  $result=array();
  
  $query="select username from ";
  $query.=USERS_TABLE." as users, ";
  $query.=SESSIONS_TABLE." as sessions";
  $query.=" where users.user_id = sessions.session_user_id";
  $query.=" order by users.username ASC";
    
  $sql=singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);
    }
  }
  return $result;
}

//
//
//
function isactive($user_id)
{
  return getdbelement("user_active",USERS_TABLE, "user_id", setinteger($user_id));
}

//
//
//
function getretries($user_id)
{
  return getdbelement("retries",USERS_TABLE, "user_id", setinteger($user_id));
}

//
//
//
function getlastlogin($user_id)
{
  return getdbelement("last_login",USERS_TABLE, "user_id", setinteger($user_id));
}
?>
