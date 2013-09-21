<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/users.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function register($user,$pass,$email)
{
  list($usec, $sec) = explode(' ', microtime());
  $activationkey= (float) $sec + ((float) $usec * 100000);
  $activationkey=md5($activationkey);

  $values=array();
  $values[]=0;
  $values[]=0;
  $values[]=setstring($user);
  $values[]=md5($pass);
  $values[]=setstring($email);
  $values[]=USERLEVEL_USER;
  $values[]=0;
  $values[]="";
  $values[]=$activationkey;
  $values[]=date(DATETIMEFORMAT, strtotime('now'));
  $values[]=0;

  $success = insertentry(USERS_TABLE,$values);
  if($success) return $activationkey;
  else return false;
}

//
//
//
function changeuserpassword($userid,$oldpass,$newpass,$confirmpass)
{
  $result="Failed to change password";
  if(checkpassword(getusername($userid),md5($oldpass)))
  {
    if(strlen($newpass)>7)
   {
      if($newpass===$confirmpass)
      {
        $sql=updatefield(USERS_TABLE,"password",md5($newpass),"user_id = '".setinteger($userid)."'");
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
    $result="Wrong password";
  }
  return $result;
}


//
//
//
function changeuserpasswordadmin($userid,$newpass,$confirmpass,$sid)
{
  $result="Failed to change password";
  if(isadmin($sid))
  {
   if(strlen($newpass)>7)
   {
      if($newpass===$confirmpass)
      {
        $sql=updatefield(USERS_TABLE,"password",md5($newpass),"user_id = '".setinteger($userid)."'");
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
function makepassword($userid)
{
  $letters=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,
  10=>"a",11=>"b",12=>"c",13=>"d",14=>"e",15=>"f",16=>"g",17=>"h",
  18=>"i",19=>"j",20=>"k",21=>"l",22=>"m",22=>"n",23=>"o",24=>"p",
  25=>"q",26=>"r",27=>"s",28=>"t",29=>"u",30=>"v",31=>"w",32=>"x",
  33=>"y",34=>"z",
  35=>"A",36=>"B",37=>"C",38=>"D",39=>"E",40=>"F",41=>"G",42=>"H",
  43=>"I",44=>"J",45=>"K",46=>"L",47=>"M",48=>"N",49=>"O",50=>"P",
  51=>"Q",52=>"R",53=>"S",54=>"T",55=>"U",56=>"V",57=>"W",58=>"X",
  59=>"Y",60=>"Z",
  );
  
  list($usec, $sec) = explode(' ', microtime());
  $seed = (float) $sec + ((float) $usec * 100000);
  srand($seed);

  $newpass="";
  for($i=0;$i<8;$i++)
  {
    $newpass.=$letters[rand(0,60)];
  }

  $sql=updatefield(USERS_TABLE,"password",md5($newpass),"user_id = '".setinteger($userid)."'");
  return $newpass;
}


//
//
//
function changeuseremail($userid,$email)
{
  updatefield(USERS_TABLE,"email",setstring($email),"user_id = '".setinteger($userid)."'");
}

//
//
//
function setuserlevel($userid,$userlevel)
{
  updatefield(USERS_TABLE,"userlevel",setinteger($userlevel),"user_id = '".setinteger($userid)."'");
}

//
//
//
function getuserlevel($userid)
{
  return getdbelement("userlevel", USERS_TABLE, "user_id",setinteger($userid));
}

//
//
//
function changeiscontact($userid,$iscontact)
{
  updatefield(USERS_TABLE,"iscontact",setinteger($iscontact),"user_id = '".setinteger($userid)."'");
}


//
//
//
function changecontactfunction($userid,$contactfunction)
{
  updatefield(USERS_TABLE,"contactfunction",setstring($contactfunction),"user_id = '".setinteger($userid)."'");
}

//
//
//
function activateuser($username)
{
  updatefield(USERS_TABLE,"user_active",1,"username = '".setstring($username)."'");
  updatefield(USERS_TABLE,"activationkey",'',"username = '".setstring($username)."'");
}

//
//
//
function deactivateuser($username)
{
  updatefield(USERS_TABLE,"user_active",0,"username = '".setstring($username)."'");
}

//
//
//
function hasactivationkey($username,$activationkey)
{
  return $activationkey === getdbelement("activationkey", USERS_TABLE, "username",setstring($username));
}

//
//
//
function userexists($username)
{
  return getdbelement("username", USERS_TABLE, "username", setstring($username));
}

//
//
//
function emailexists($email,$user_id=false)
{
  $emailuser= getdbelement("user_id", USERS_TABLE, "email", setstring($email));
  if($user_id)
  {
    return ($emailuser && ($user_id !== $emailuser));
  }
  else
  {
    return ($emailuser);
  }
}

//
//
//
function getallusers()
{
  return getorderedcolumn("user_id",USERS_TABLE,"1", "username","ASC");
}

?>
