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
	global $db;
	$values=array();
	$values[]=0;
	$values[]=1;
	$values[]=$db->setstring($user);
	$values[]=md5($pass);
	
	return insertentry(PUBLICUSERS_TABLE,$values);
}


//
//
//
function changepublicuserpasswordadmin($userid,$newpass,$confirmpass)
{
	global $db;
	$result="Failed to change password";
	
	if(isadmin())
	{
		if(strlen($newpass)>7)
		{
			if($newpass===$confirmpass)
			{
				$sql=updatefield(PUBLICUSERS_TABLE,"password",$db->setstring(md5($newpass)),"user_id = '".$db->setinteger($userid)."'");
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
	global $db;
	updatefield(PUBLICUSERS_TABLE,"user_active",1,"user_id = '".$db->setinteger($userid)."'");
}

//
//
//
function deactivatepublicuser($userid)
{
	global $db;
	updatefield(PUBLICUSERS_TABLE,"user_active",0,"user_id = '".$db->setinteger($userid)."'");
}


//
//
//
function publicuserexists($username)
{
	global $db;
	return getdbelement("username", PUBLICUSERS_TABLE, "username", $db->setstring($username));
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
	global $db;
	$result = true;
	for($i=0;$i<count($userids);$i++)
	{
		$values=array();
		$values[]=0;
		$values[]=$db->setinteger($pageid);
		$values[]=$db->setinteger($userids[$i]);
		$result = $result & insertentry(RESTRICTEDPAGESACCESS_TABLE,$values);
	}
	return $result;
}

//
//
//
function removepageaccess($userids,$pageid)
{
	global $db;
	$result = true;
	for($i=0;$i<count($userids);$i++)
	{
		$result = $result & deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".$db->setinteger($pageid)."' AND publicuser_id ='".$db->setinteger($userids[$i])."'");
	}
	return $result;
}

//
//
//
function getallpublicuserswithaccessforpage($pageid)
{
	global $db;
	return getcolumn("publicuser_id",RESTRICTEDPAGESACCESS_TABLE,"page_id = '".$db->setinteger($pageid)."'");
}


// *************************** ip ban **************************** //

//
//
//
function addbannedipforrestrictedpages($ip)
{
	$result = true;
	$ip = ip2long($ip);
	$dbip= getdbelement("ip", RESTRICTEDPAGESBANNEDIPS_TABLE, "ip",$ip);
	if($ip != $dbip)
	{
		$result = $result & insertentry(RESTRICTEDPAGESBANNEDIPS_TABLE,array(0 => $ip));
	}
	return $result;
}

//
//
//
function removebannedipforrestrictedpageas($ip)
{
	return deleteentry(RESTRICTEDPAGESBANNEDIPS_TABLE,"ip = '".ip2long($ip)."'");
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
