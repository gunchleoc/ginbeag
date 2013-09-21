<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
//include_once($projectroot."functions/pagesmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/templates/elements.php");


################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
// returns array with sid and message
//
function publiclogin($username,$password)
{
  $username=setstring($username);
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
        $result['message']="You have entered the wrong password too often, so we have to lock you out for now. Please try again later.";
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
          $result['message']="login successful";
        }
        else
        {
          $result['message']="Failed to create session";
        }
      }
      else
      {
        $result['message']="Wrong username or password";
        updatepubliclogindate($user_id,$ip);
      }
    }
  }
  else
  {
    $result['message']="Your IP address has been banned";
  }
  return $result;
}

//
//
//
function checkpublicpassword($username,$md5password)
{
  $username=setstring($username);
  $md5password=setstring($md5password);

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
  $query="DELETE FROM ".PUBLICSESSIONS_TABLE;
  $query.=" where session_id='".setstring($sid)."';";
//  print($query.'<p>');
  $sql=singlequery($query);
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
  $user_id=setinteger($user_id);

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
    $sql=singlequery($query);

    $retries=getpublicretries($user_id,$ip);

    $query=("update ");
    $query.=(PUBLICSESSIONS_TABLE." set ");
    $query.="retries=";
    $query.="'".($retries+1)."'";
    $query.=" where session_id = '".$sid."';";
//  print($query.'<p>');
    $sql=singlequery($query);
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
  $user_id=setinteger($user_id);
  $session_valid=setinteger($session_valid);

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

  $sql=singlequery($query);

  return $sid;
}


//
//
//
function clearpublicsessions()
{
  $time=strtotime('-1 hours');
  $query="DELETE FROM ".PUBLICSESSIONS_TABLE;
  $query.=" where session_time < '".date(DATETIMEFORMAT, $time)."'";
  singlequery($query);
}

//
//
//
function publictimeout($sid)
{
  $sid=setstring($sid);

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
      $sql=singlequery($query);
    }
  }
  return $result;
}

//
//
//
function checkpublicsession($page_id)
{
  global $_GET;
  $isvalid=isset($_GET["sid"]) && ispublicsessionvalid(setstring($_GET["sid"]));
//  $user_id=getpublicsiduser($_GET["sid"]);

  if(!$isvalid || publictimeout($_GET["sid"]) || !checkpublicip($_GET["sid"]) || !hasaccessarray($page_id))
  {
    $contenturl="login.php".makelinkparameters($_GET);

    if(!hasaccessarray($page_id))
    {
      $message='You do not have permission do view this page.';
    }
    else
    {
      $message='Your session has expired.';
    }
    $header = new HTMLHeader("Access restricted","Access restricted",$message,$contenturl,"please log in",true);
    print($header->toHTML());

    $footer = new HTMLFooter();
    print($footer->toHTML());
    exit;
  }
}


//
//
//
function checkpublicip($sid)
{
  $sid=setstring($sid);
  
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
//
//
function getsidforpublicuser($user_id,$ip)
{
  $result=false;
  $query="select session_id from ".PUBLICSESSIONS_TABLE." where session_user_id = '".setinteger($user_id)."' AND session_ip = '".setinteger($ip)."';";
  $sql=singlequery($query);
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
  return getdbelement("session_user_id",PUBLICSESSIONS_TABLE, "session_id", setstring($sid));
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
  return getdbelement("session_valid",PUBLICSESSIONS_TABLE, "session_id", setstring($sid));
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
  return getdbelement("session_ip",PUBLICSESSIONS_TABLE, "session_id", setstring($sid));
}
?>
