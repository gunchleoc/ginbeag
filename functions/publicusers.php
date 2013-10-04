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
	global $db;
	return getdbelement("username",PUBLICUSERS_TABLE, "user_id", $db->setinteger($user_id));
}

//
//
//
function getpublicuserid($username)
{
	global $db;
	return getdbelement("user_id",PUBLICUSERS_TABLE, "username",$db->setstring($username));
}

//
//
//
function ispublicuseractive($user_id)
{
	global $db;
	return getdbelement("user_active",PUBLICUSERS_TABLE, "user_id", $db->setinteger($user_id));
}

?>
