<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function getpublicusername($user_id)
{
  return getdbelement("username",PUBLICUSERS_TABLE, "user_id", setinteger($user_id));
}

//
//
//
function getpublicuserid($username)
{
  return getdbelement("user_id",PUBLICUSERS_TABLE, "username",setstring($username));
}

//
//
//
function ispublicuseractive($user_id)
{
  return getdbelement("user_active",PUBLICUSERS_TABLE, "user_id", setinteger($user_id));
}


?>
