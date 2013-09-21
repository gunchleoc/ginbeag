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
function getusername($user_id)
{
  return getdbelement("username",USERS_TABLE, "user_id", setinteger($user_id));
}

//
//
//
function getuseremail($user_id)
{
  return getdbelement("email",USERS_TABLE, "user_id", setinteger($user_id));
}

//
//
//
function getuserid($username)
{
  return getdbelement("user_id",USERS_TABLE, "username",setstring($username));
}


//
//
//
function getallcontacts()
{
  return getorderedcolumn("user_id",USERS_TABLE,"iscontact = '1'", "username", "ASC");
}

//
//
//
function getiscontact($user_id)
{
  return getdbelement("iscontact",USERS_TABLE, "user_id",setinteger($user_id));
}

//
//
//
function getcontactfunction($user_id)
{
  return getdbelement("contactfunction",USERS_TABLE, "user_id",setinteger($user_id));
}


?>
