<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/publicusers.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function addpublicuser($user,$pass)
{
  $values=array();
  $values[]=0;
  $values[]=1;
  $values[]=setstring($user);
  $values[]=md5($pass);

  return insertentry(PUBLICUSERS_TABLE,$values);
}


//
//
//
function changepublicuserpasswordadmin($userid,$newpass,$confirmpass,$sid)
{
  $result="Failed to change password";
  if(isadmin($sid))
  {
   if(strlen($newpass)>7)
   {
      if($newpass===$confirmpass)
      {
        $sql=updatefield(PUBLICUSERS_TABLE,"password",md5($newpass),"user_id = '".setinteger($userid)."'");
        if($sql)
        {
          $result="Password changed successfully";
        }
      }
      else
      {
        $result="Passwords did not match";
      }
    }
    else
    {
      $result="Your password must be at least 8 digits long";
    }
  }
  else
  {
    $result="Please hack someone else.";
  }
  return $result;
}


//
//
//
function activatepublicuser($userid)
{
  updatefield(PUBLICUSERS_TABLE,"user_active",1,"user_id = '".setinteger($userid)."'");
}

//
//
//
function deactivatepublicuser($userid)
{
  updatefield(PUBLICUSERS_TABLE,"user_active",0,"user_id = '".setinteger($userid)."'");
}


//
//
//
function publicuserexists($username)
{
  return getdbelement("username", PUBLICUSERS_TABLE, "username", setstring($username));
}


//
//
//
function getallpublicusers()
{
  return getorderedcolumn("user_id",PUBLICUSERS_TABLE,"1", "username","ASC");
}

// *************************** restricted access **************************** //

//
//
//
function addpageaccess($userids,$pageid)
{
  for($i=0;$i<count($userids);$i++)
  {
    $values=array();
    $values[]=0;
    $values[]=setinteger($pageid);
    $values[]=setinteger($userids[$i]);
    insertentry(RESTRICTEDPAGESACCESS_TABLE,$values);
  }
}

//
//
//
function removepageaccess($userids,$pageid)
{
  for($i=0;$i<count($userids);$i++)
  {
    deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".setinteger($pageid)."' AND publicuser_id ='".setinteger($userids[$i])."'");
  }
}

//
//
//
function getallpublicuserswithaccessforpage($pageid)
{
  return getcolumn("publicuser_id",RESTRICTEDPAGESACCESS_TABLE,"page_id = '".setinteger($pageid)."'");
}


// *************************** ip ban **************************** //

//
//
//
function addbannedipforrestrictedpages($ip)
{
  $ip = ip2long($ip);
  $dbip= getdbelement("ip", RESTRICTEDPAGESBANNEDIPS_TABLE, "ip",$ip);
  if($ip != $dbip)
  {
    insertentry(RESTRICTEDPAGESBANNEDIPS_TABLE,array(0 => $ip));
  }
}

//
//
//
function removebannedipforrestrictedpageas($ip)
{
  deleteentry(RESTRICTEDPAGESBANNEDIPS_TABLE,"ip = '".ip2long($ip)."'");
}

//
//
//
function getalladdbannedipforrestrictedpages()
{
  $longips= getorderedcolumn("ip",RESTRICTEDPAGESBANNEDIPS_TABLE,"1","ip","ASC");
  return array_map ("long2ip", $longips);
}

?>
