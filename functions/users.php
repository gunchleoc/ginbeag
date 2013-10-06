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
function getusername($user)
{
	global $db;
	return getdbelement("username",USERS_TABLE, "user_id", $db->setinteger($user));
}

//
//
//
function getuseremail($user)
{
	global $db;
	return getdbelement("email",USERS_TABLE, "user_id", $db->setinteger($user));
}

//
//
//
function getuserid($username)
{
	global $db;
	return getdbelement("user_id",USERS_TABLE, "username",$db->setstring($username));
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
function getiscontact($user)
{
	global $db;
	return getdbelement("iscontact",USERS_TABLE, "user_id",$db->setinteger($user));
}

//
//
//
function getcontactfunction($user)
{
	global $db;
	return getdbelement("contactfunction",USERS_TABLE, "user_id",$db->setinteger($user));
}

?>
